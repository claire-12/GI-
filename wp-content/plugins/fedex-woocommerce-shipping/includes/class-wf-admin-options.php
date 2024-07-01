<?php
if( !class_exists('WF_Admin_Options') ){
	class WF_Admin_Options{

		/**
		 *Freight Classes
		 */
		public $freight_classes;
		/**
		 * Settings
		 */
		public $settings;

		function __construct(){
			$this->freight_classes  =   include( 'data-wf-freight-classes.php' );
			$this->init();
		}

		function init(){
			$this->settings = get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null );

			if( is_admin() ){
				// Add a custome field in product page variation level
				// add_action( 'woocommerce_product_after_variable_attributes', array($this,'wf_variation_settings_fields'), 10, 3 );
				// Save a custome field in product page variation level
				// add_action( 'woocommerce_save_product_variation', array($this,'wf_save_variation_settings_fields'), 10, 2 );

				// Add a custome field in product page variation level
				add_action( 'woocommerce_product_after_variable_attributes', array($this, 'wf_add_custome_product_fields_at_variation'), 10, 3 );
				// Save a custome field in product page variation level
				add_action( 'woocommerce_save_product_variation', array( $this, 'wf_save_custome_product_fields_at_variation'), 10, 2 );

				//add a custome field in product page
				add_action( 'woocommerce_product_options_shipping', array($this,'wf_custome_product_page')  );
				//Saving the values
				add_action( 'woocommerce_process_product_meta', array( $this, 'wf_save_custome_product_fields' ) );
			}

			//add_action( 'woocommerce_product_options_shipping', array($this, 'admin_add_frieght_class'));
			//add_action( 'woocommerce_process_product_meta',       array( $this, 'admin_save_frieght_class' ));

			// add_action( 'woocommerce_product_after_variable_attributes', array($this, 'wf_add_custome_product_fields_at_variation'), 10, 3 );
			// add_action( 'woocommerce_save_product_variation', array( $this, 'wf_save_custome_product_fields_at_variation'), 10, 2 );
		}

		function wf_custome_product_page() {


			$post_id 			= get_the_ID();
			$hazmatEnabled 		= get_post_meta($post_id ,'_hazmat_products', true);
			$dangerousEnabled 	= get_post_meta($post_id ,'_dangerous_goods', true);
			$dangerousOptions 	= get_post_meta($post_id ,'_ph_fedex_dg_option', true);

			if ( isset($dangerousOptions) && !empty($dangerousOptions) ) {

				$dgOptionValue = $dangerousOptions;

			} else if ( $hazmatEnabled ) {
				
				$dgOptionValue = 'HAZARDOUS_MATERIALS';

			} else if ( !$hazmatEnabled && $dangerousEnabled ) {
				
				$dgOptionValue = 'LIMITED_QUANTITIES_COMMODITIES';

			} else {

				$dgOptionValue = 'NONE';
			}

			?><hr style="border-top: 1px solid #eee;" /><p class="ph_fedex_other_details">FedEx Shipping Details<span class="toggle_symbol" aria-hidden="true"></span></p><div class="ph_fedex_hide_show_product_fields"><?php

			//HS code field
			woocommerce_wp_text_input( array(
				'id' => '_wf_hs_code',
				'label' => __('HS Tariff Number','ph-fedex-woocommerce-shipping'),
				'description' => __('HS Code is a standardized system of names and numbers to classify products.','ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
				'placeholder' => __('Harmonized Code','ph-fedex-woocommerce-shipping')
			) );

			// Commodity Description
			woocommerce_wp_textarea_input( array(
				'id' => '_ph_commodity_description',
				'label' => __('Commodity Description','ph-fedex-woocommerce-shipping'),
				'description' => __('Enter Commodity Description <br/> NOTE: Commodity description should match the Harmonized Code','ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
				'placeholder' => __('Commodity Description [3 to 450 characters required]','ph-fedex-woocommerce-shipping')
			) );

			//Country of manufacture
			woocommerce_wp_text_input( array(
				'id' => '_wf_manufacture_country',
				'label' => __('Country of Manufacture','ph-fedex-woocommerce-shipping'),
				'description' => __('Country Code of Manufacture','ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
				'placeholder' => __('Country Code','ph-fedex-woocommerce-shipping')
			) );

			if( isset($this->settings['dry_ice_enabled']) && $this->settings['dry_ice_enabled']=='yes' ){
				//dry ice
				woocommerce_wp_checkbox( array(
					'id' => '_wf_dry_ice',
					'label' => __('Dry Ice','ph-fedex-woocommerce-shipping'),
					'description' => __('Enable if this product requires Dry Ice shipment.','ph-fedex-woocommerce-shipping'),
					'desc_tip' => 'true',
				) );
				
				woocommerce_wp_text_input( array(
					'id' => '_wf_dry_ice_weight',
					'data_type'=> 'decimal',
					'label' => __('Dry Ice Weight','ph-fedex-woocommerce-shipping'),
					'description' => __('Enter the weight of Dry Ice.','ph-fedex-woocommerce-shipping'),
					'desc_tip' => 'true',
					'placeholder' => __('Dry Ice Weight','ph-fedex-woocommerce-shipping')
				) );
			}

			// Freight Class
			woocommerce_wp_select(array(
				'id' =>     '_wf_freight_class',
				'label' =>   __('Freight Class','ph-fedex-woocommerce-shipping'),
				'options' => array(''=>__('None'))+$this->freight_classes,
				'description' => __('FedEx Freight class for shipping calculation.','ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
			));

			// Special Services
			$is_alcohol_product = get_post_meta( $post_id ,'_wf_fedex_special_service_types', true );
			
			woocommerce_wp_checkbox( array(
				'id' 			=> '_wf_fedex_special_service_types',
				'label'     => __( 'Alcohol ', 'ph-fedex-woocommerce-shipping'),
				'value'	=> ( $is_alcohol_product == 'ALCOHOL' ? 'yes' : 'no'),
			));

			?><div class="ph_fedex_alcohol_recipient"><?php

			// Alcohol Recipient Type
			woocommerce_wp_select( array(
				'id'        => '_wf_fedex_sst_alcohal_recipient',
				'label'     => __( 'Alcohol Recipient type', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'Select the Alcohol Recipient types if applicable .', 'ph-fedex-woocommerce-shipping').'<br />'.__( 'CONSUMER - Select, if no license is required for recipient.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'LICENSEE - Select, if license is required for recipient.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'default'	=> 'LICENSEE',
				'options'   => array(
					'LICENSEE'  => __( 'Licensee', 'ph-fedex-woocommerce-shipping' ),
					'CONSUMER'  => __( 'Consumer', 'ph-fedex-woocommerce-shipping' ),
				),
			));

			?></div><?php

			// Signature Option PDS-179
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_signature_option',
				'label'     	=> __( 'Delivery Signature', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'FedEx Freight services are not eligible for Signature Service. Hence, Signature option will be ignored for Freight Shipments.', 'ph-fedex-woocommerce-shipping'),
				'desc_tip'  	=> true,
				'options'   	=> array(
					null        			=> __( 'Select Anyone', 'ph-fedex-woocommerce-shipping' ),
					'ADULT'	   				=> __( 'Adult', 'ph-fedex-woocommerce-shipping' ),
					'DIRECT'	  			=> __( 'Direct', 'ph-fedex-woocommerce-shipping' ),
					'INDIRECT'	  			=> __( 'Indirect', 'ph-fedex-woocommerce-shipping' ),
					'NO_SIGNATURE_REQUIRED' => __( 'No Signature Required', 'ph-fedex-woocommerce-shipping' ),
					'SERVICE_DEFAULT'	  	=> __( 'Service Default', 'ph-fedex-woocommerce-shipping' ),
				),
			));

			//Pre packed
			woocommerce_wp_checkbox( array(
				'id' => '_wf_fedex_pre_packed',
				'label' => __('Pre packed product ','ph-fedex-woocommerce-shipping'),
				'description' => __('Check this if the item comes in boxes. It will consider as a separate package and ship in its own box.', 'ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
			) );

			//Non-Standard Prducts
			woocommerce_wp_checkbox( array(
				'id' => '_wf_fedex_non_standard_product',
				'label' => __('Non-Standard product ','ph-fedex-woocommerce-shipping'),
				'description' => __('Check this if the product belongs to Non Standard Container. Non-Stantard product will be charged higher', 'ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
			) );

			//Customs declared value
			woocommerce_wp_text_input( array(
				'id'        => '_wf_fedex_custom_declared_value',
				'data_type' => 'decimal',
				'label'     => __( 'Custom Declared Value ', 'ph-fedex-woocommerce-shipping' ),
				'description'   => __('This amount will be reimbursed from FedEx if products get damaged and you have opt for Insurance.','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder'   => __( 'Insurance amount FedEx', 'ph-fedex-woocommerce-shipping')
			) );

			//Dangerous Goods Options
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_dg_option',
				'label'     	=> __( 'Dangerous Goods Option Type ', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'Select Dangerous Goods Option Type.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  	=> true,
				'value'  		=> $dgOptionValue,
				'options'   	=> array(
					'NONE'							=> __( 'None', 'ph-fedex-woocommerce-shipping' ),
					'LIMITED_QUANTITIES_COMMODITIES'=> __( 'Limited Quantities Commodities', 'ph-fedex-woocommerce-shipping' ),
					'HAZARDOUS_MATERIALS'    		=> __( 'Hazardous Materials', 'ph-fedex-woocommerce-shipping' ),
					'BATTERY'  						=> __( 'Battery', 'ph-fedex-woocommerce-shipping' ),
					'ORM_D'    						=> __( 'ORM D', 'ph-fedex-woocommerce-shipping' ),
					'SMALL_QUANTITY_EXCEPTION'		=> __( 'Small Quantity Exception', 'ph-fedex-woocommerce-shipping'),
				),
			));

			?><div class="ph_fedex_dangerous_goods_ormd"><?php

			//Dangerous Goods Regulations
			woocommerce_wp_select( array(
				'id'        => '_wf_fedex_dg_regulations',
				'label'     => __( 'Dangerous Goods Regulation ', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'Select the regulation .', 'ph-fedex-woocommerce-shipping').'<br />'.__( 'ADR - European Agreement concerning the International Carriage of Dangerous Goods by Road.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'DOT - U.S. Department of Transportation has primary responsibility for overseeing the transportation in commerce of hazardous materials, commonly called "HazMats".', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'IATA - International Air Transport Association Dangerous Goods.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'ORMD - Other Regulated Materials for Domestic transport only.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'options'   => array(
					'DOT'   => __( 'DOT', 'ph-fedex-woocommerce-shipping' ),
					'ADR'   => __( 'ADR', 'ph-fedex-woocommerce-shipping' ),
					'IATA'  => __( 'IATA', 'ph-fedex-woocommerce-shipping' ),
					'ORMD'  => __( 'ORMD', 'ph-fedex-woocommerce-shipping' )
				),
			));

			?></div><div class="ph_fedex_dangerous_goods_lqc"><?php

			//Dangerous Goods Accessibility
			woocommerce_wp_select( array(
				'id'        => '_wf_fedex_dg_accessibility',
				'label'     => __( 'Dangerous Goods Accessibility ', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'Select the accessibility type .', 'ph-fedex-woocommerce-shipping').'<br />'.__( 'ACCESSIBLE - Dangerous Goods shipments must be accessible to the flight crew in-flight.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'INACCESSIBLE - Inaccessible Dangerous Goods (IDG) do not need to be loaded so they are accessible to the flight crew in-flight.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'options'   => array(
					'INACCESSIBLE'  => __( 'Inaccessible', 'ph-fedex-woocommerce-shipping' ),
					'ACCESSIBLE'    => __( 'Accessible', 'ph-fedex-woocommerce-shipping' ),
				),
			));

			//Dangerous Goods Cargo Aircraft Only
			woocommerce_wp_checkbox( array(
				'id' 			=> '_ph_fedex_dg_cargo_aircraft_only',
				'label' 		=> __('Dangerous Goods Cargo Aircraft Only ','ph-fedex-woocommerce-shipping'),
			));

			?></div><div class="ph_fedex_hazardous_materials"><?php

			//Hazmat Identification Number
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_id_num',
				'label'     => __( 'Identificaton No. ', 'ph-fedex-woocommerce-shipping' ),
				'description'   => __('Hazardous material regulatory commodity identifier referred to as Department of Transportation (DOT) location ID number (UN or NA).','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder'   => __( 'UN1088', 'ph-fedex-woocommerce-shipping'),
			) );

			//Hazmat Packaging Group
			woocommerce_wp_select( array(
				'id'        => '_ph_fedex_hp_packaging_group',
				'label'     => __( 'Packaging Group ', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'Hazardous material packaging group.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'options'   => array(
					'DEFAULT'   => __( 'Default', 'ph-fedex-woocommerce-shipping' ),
					'I'         => __( 'I', 'ph-fedex-woocommerce-shipping' ),
					'II'        => __( 'II', 'ph-fedex-woocommerce-shipping' ),
					'III'       => __( 'III', 'ph-fedex-woocommerce-shipping' )
				),
			));

			//Hazmat Proper Shipping Name
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_proper_shipping_name',
				'label'     => __( 'Proper Shipping Name ', 'ph-fedex-woocommerce-shipping' ),
				'description'   => __('Hazardous material proper shipping name. Up to three description lines of 50 characters each are allowed for a HazMat shipment. These description elements are formatted on the OP950 form in 25-character columns (up to 6 printed lines).','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder'   => __( 'Acetal', 'ph-fedex-woocommerce-shipping')
			) );

			//Hazmat Hazard Class
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_hazard_class',
				'label'     => __( 'Hazard Class ', 'ph-fedex-woocommerce-shipping' ),
				'description'   => __('DOT hazardous material class or division.','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder'   => __( '3', 'ph-fedex-woocommerce-shipping')
			) );

			//Hazmat Subsidiary Classes
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_subsidiary_classes',
				'label'     => __( 'Subsidiary Classes ', 'ph-fedex-woocommerce-shipping' ),
				'description'   => __('Hazardous material subsidiary classes.','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
			) );

			//Hazmat Label Text
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_label_text',
				'label'     => __( 'Label Text ', 'ph-fedex-woocommerce-shipping' ),
				'description'   => __('DOT diamond hazard label type. Can also include limited quantity or exemption number.','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder' =>  __( 'FLAMMABLE LIQUID', 'ph-fedex-woocommerce-shipping')
			) );

			?></div><?php

			//Battery Products Checkbox
			woocommerce_wp_checkbox( array(
				'id' 			=> '_battery_products',
				'label' 		=> __('Battery Product ','ph-fedex-woocommerce-shipping'),
				'description' 	=> __('Check this to mark the product as a Battery Product.','ph-fedex-woocommerce-shipping'),
				'desc_tip' 		=> 'true',
			));

			?><div class="ph_fedex_battery_materials"><?php

			//Battery Material Type
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_battery_material_type',
				'label'     	=> __( 'Battery Material Type ', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'Describes the material composition of a battery or cell.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  	=> true,
				'options'   	=> array(
					'LITHIUM_ION'   => __( 'Lithium Ion', 'ph-fedex-woocommerce-shipping' ),
					'LITHIUM_METAL' => __( 'Lithium Metal', 'ph-fedex-woocommerce-shipping' )
				),
			));

			//Battery Packing Type
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_battery_packing_type',
				'label'     	=> __( 'Battery Packing Type ', 'ph-fedex-woocommerce-shipping'),
				'description'   => __( 'Describes the packing arrangement of a battery or cell with respect to other items within the same package.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  	=> true,
				'options'   	=> array(
					'CONTAINED_IN_EQUIPMENT' => __( 'Contained In Equipment', 'ph-fedex-woocommerce-shipping' ),
					'PACKED_WITH_EQUIPMENT'  => __( 'Packed With Equipment', 'ph-fedex-woocommerce-shipping' )
				),
			));

			?></div></div><?php
			
		}

		// public function wf_variation_settings_fields( $loop, $variation_data, $variation ){
		//  $is_pre_packed_var = get_post_meta( $variation->ID, '_wf_fedex_pre_packed_var', true );
		//  if( empty( $is_pre_packed_var ) ){
		//      $is_pre_packed_var = get_post_meta( wp_get_post_parent_id($variation->ID), '_wf_fedex_pre_packed', true );
		//  }
		//  woocommerce_wp_checkbox( array(
		//      'id' => '_wf_fedex_pre_packed_var[' . $variation->ID . ']',
		//      'label' => __(' Pre packed product', 'ph-fedex-woocommerce-shipping'),
		//      'description' => __('Check this if the item comes in boxes. It will override global product settings', 'ph-fedex-woocommerce-shipping'),
		//      'desc_tip' => 'true',
		//      'value'         => $is_pre_packed_var,
		//  ) );
		// }

		// public function wf_save_variation_settings_fields( $post_id ){
		//  $checkbox = isset( $_POST['_wf_fedex_pre_packed_var'][ $post_id ] ) ? 'yes' : 'no';
		//  update_post_meta( $post_id, '_wf_fedex_pre_packed_var', $checkbox );
		// }

		function wf_save_custome_product_fields( $post_id ) {

			//HS code value
			if ( isset( $_POST['_wf_hs_code'] ) && !is_array( $_POST['_wf_hs_code'] ) ) {
				update_post_meta( $post_id, '_wf_hs_code', esc_attr( $_POST['_wf_hs_code'] ) );
			}
			//Commodity Descriptiom
			if ( isset( $_POST['_ph_commodity_description'] ) ) {
				update_post_meta( $post_id, '_ph_commodity_description', esc_attr( $_POST['_ph_commodity_description'] ) );
			}
			//dryice weight
			if( isset( $_POST['_wf_dry_ice_weight'] )  )
			{
				$dry_ice_weight=$_POST['_wf_dry_ice_weight'];
				update_post_meta( $post_id, '_wf_dry_ice_weight', $dry_ice_weight );
			}
			//dry ice
			$is_dry_ice =  ( isset( $_POST['_wf_dry_ice'] ) && esc_attr($_POST['_wf_dry_ice']=='yes')  ) ? esc_attr($_POST['_wf_dry_ice']) : false;
			update_post_meta( $post_id, '_wf_dry_ice', $is_dry_ice );

			// Country of manufacture
			if ( isset( $_POST['_wf_manufacture_country'] ) ) {
				update_post_meta( $post_id, '_wf_manufacture_country', esc_attr( $_POST['_wf_manufacture_country'] ) );
			}
			
			// Freight Class
			if ( isset( $_POST['_wf_freight_class']) && !is_array($_POST['_wf_freight_class']) ) {
				update_post_meta( $post_id, '_wf_freight_class', esc_attr( $_POST['_wf_freight_class'] ) );
			}

			// Alcohol Product
			$alcohol_products =  ( isset( $_POST['_wf_fedex_special_service_types'] ) && !is_array($_POST['_wf_fedex_special_service_types']) && esc_attr($_POST['_wf_fedex_special_service_types'])=='yes') ? 'ALCOHOL'  : false;
			update_post_meta( $post_id, '_wf_fedex_special_service_types', $alcohol_products );

			// Alcohol recipient type
			if( isset($_POST['_wf_fedex_sst_alcohal_recipient']) && !is_array($_POST['_wf_fedex_sst_alcohal_recipient']) ) {
				update_post_meta( $post_id, '_wf_fedex_sst_alcohal_recipient', $_POST['_wf_fedex_sst_alcohal_recipient'] );
			}

			// Signature Option PDS-179
			if( isset($_POST['_ph_fedex_signature_option']) && !is_array($_POST['_ph_fedex_signature_option']) ) {
				update_post_meta( $post_id, '_ph_fedex_signature_option', $_POST['_ph_fedex_signature_option'] );
			}

			//Save Dangerous goods regulation
			if( ! empty ($_POST['_wf_fedex_dg_regulations']) && !is_array($_POST['_wf_fedex_dg_regulations']) ) {
				update_post_meta( $post_id, '_wf_fedex_dg_regulations', $_POST['_wf_fedex_dg_regulations'] );
			}

			//Save dangerous goods accessibility
			if( ! empty( $_POST['_wf_fedex_dg_accessibility']) && !is_array($_POST['_wf_fedex_dg_accessibility']) ) {
				update_post_meta( $post_id, '_wf_fedex_dg_accessibility', $_POST['_wf_fedex_dg_accessibility'] );
			}

			//Save dangerous goods Options
			if( ! empty( $_POST['_ph_fedex_dg_option']) && !is_array($_POST['_ph_fedex_dg_option']) ) {
				update_post_meta( $post_id, '_ph_fedex_dg_option', $_POST['_ph_fedex_dg_option'] );
			}

			//Save dangerous goods Cargo Aircraft Only
			$cargo_aircraft =  ( isset( $_POST['_ph_fedex_dg_cargo_aircraft_only'] ) && !is_array($_POST['_ph_fedex_dg_cargo_aircraft_only']) && esc_attr($_POST['_ph_fedex_dg_cargo_aircraft_only'])=='yes') ? esc_attr($_POST['_ph_fedex_dg_cargo_aircraft_only'])  : false;
			update_post_meta( $post_id, '_ph_fedex_dg_cargo_aircraft_only', $cargo_aircraft );


			// Save Battery Products
			$battery_products =  ( isset( $_POST['_battery_products'] ) && !is_array($_POST['_battery_products']) && esc_attr($_POST['_battery_products'])=='yes') ? esc_attr($_POST['_battery_products'])  : false;
			update_post_meta( $post_id, '_battery_products', $battery_products );

			// Save Battery Material Type
			if( isset($_POST['_ph_fedex_battery_material_type']) && !is_array($_POST['_ph_fedex_battery_material_type']) ) {
				update_post_meta( $post_id, '_ph_fedex_battery_material_type', esc_attr( $_POST['_ph_fedex_battery_material_type'] ) );
			}

			// Save Battery Packing Type
			if( isset($_POST['_ph_fedex_battery_packing_type']) && !is_array($_POST['_ph_fedex_battery_packing_type']) ) {
				update_post_meta( $post_id, '_ph_fedex_battery_packing_type', esc_attr( $_POST['_ph_fedex_battery_packing_type'] ) );
			}

			// Save Hazmat Identification Number
			if( isset($_POST['_ph_fedex_hp_id_num']) && !is_array($_POST['_ph_fedex_hp_id_num']) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_id_num', esc_attr( $_POST['_ph_fedex_hp_id_num'] ) );
			}

			//Save Hazmat Packaging Group
			if( ! empty ($_POST['_ph_fedex_hp_packaging_group']) && !is_array($_POST['_ph_fedex_hp_packaging_group']) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_packaging_group', $_POST['_ph_fedex_hp_packaging_group'] );
			}

			// Save Hazmat Proper Shipping Name
			if( isset($_POST['_ph_fedex_hp_proper_shipping_name']) && !is_array($_POST['_ph_fedex_hp_proper_shipping_name']) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_proper_shipping_name', esc_attr( $_POST['_ph_fedex_hp_proper_shipping_name'] ) );
			}

			// Save Hazmat Hazard Class
			if( isset($_POST['_ph_fedex_hp_hazard_class']) && !is_array($_POST['_ph_fedex_hp_hazard_class']) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_hazard_class', esc_attr( $_POST['_ph_fedex_hp_hazard_class'] ) );
			}

			// Save Hazmat Subsidiary Classes
			if( isset($_POST['_ph_fedex_hp_subsidiary_classes']) && !is_array($_POST['_ph_fedex_hp_subsidiary_classes']) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_subsidiary_classes', esc_attr( $_POST['_ph_fedex_hp_subsidiary_classes'] ) );
			}

			// Save Hazmat Label Text
			if( isset($_POST['_ph_fedex_hp_label_text']) && !is_array($_POST['_ph_fedex_hp_label_text']) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_label_text', esc_attr( $_POST['_ph_fedex_hp_label_text'] ) );
			}

			// Pre packed
			if ( isset( $_POST['_wf_fedex_pre_packed']) ) {
				update_post_meta( $post_id, '_wf_fedex_pre_packed', esc_attr( $_POST['_wf_fedex_pre_packed'] ) );
			} else {
				update_post_meta( $post_id, '_wf_fedex_pre_packed', '' );
			}
			
			//non-standard product
			$non_standard_product =  ( isset( $_POST['_wf_fedex_non_standard_product'] ) && !is_array($_POST['_wf_fedex_non_standard_product']) && esc_attr($_POST['_wf_fedex_non_standard_product'])=='yes') ? esc_attr($_POST['_wf_fedex_non_standard_product'])  : false;
			update_post_meta( $post_id, '_wf_fedex_non_standard_product', $non_standard_product );

			// Update the Insurance amount on individual product page
			 if( isset($_POST['_wf_fedex_custom_declared_value'] ) && !is_array( $_POST['_wf_fedex_custom_declared_value'] )) {
                update_post_meta( $post_id, '_wf_fedex_custom_declared_value', esc_attr( $_POST['_wf_fedex_custom_declared_value'] ) );
            }
			
		}
		
		// function admin_add_frieght_class() {
		//  woocommerce_wp_select(array(
		//      'id' =>     '_wf_freight_class',
		//      'label' =>   __('Freight Class (FedEx)','ph-fedex-woocommerce-shipping'),
		//      'options' => array(''=>__('None'))+$this->freight_classes,
		//      'description' => __('FedEx Freight class for shipping calculation.','ph-fedex-woocommerce-shipping'),
		//      'desc_tip' => 'true',
		//  ));
		// }

		//Function to add option in products at variation level
		function wf_add_custome_product_fields_at_variation($loop, $variation_data, $variation){

			$hazmatEnabled 		= get_post_meta($variation->ID ,'_hazmat_products', true);
			$dangerousEnabled 	= get_post_meta($variation->ID ,'_dangerous_goods', true);
			$dangerousOptions 	= get_post_meta($variation->ID ,'_ph_fedex_dg_option', true);

			if ( isset($dangerousOptions) && !empty($dangerousOptions) ) {

				$dgOptionValue = $dangerousOptions;

			} else if ( $hazmatEnabled ) {
				
				$dgOptionValue = 'HAZARDOUS_MATERIALS';

			} else if ( !$hazmatEnabled && $dangerousEnabled ) {
				
				$dgOptionValue = 'LIMITED_QUANTITIES_COMMODITIES';
				
			} else{

				$dgOptionValue = 'NONE';
			}
			
			?><hr style="border-top: 1px solid #eee;" /><p class="ph_fedex_var_other_details">FedEx Shipping Details<span class="var_toggle_symbol" aria-hidden="true"></span></p><div class="ph_fedex_hide_show_var_product_fields"><?php

			// HS code field
			woocommerce_wp_text_input( array(
				'id' => '_wf_hs_code['.$variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_select',
				'label' => __('HS Tariff Number','ph-fedex-woocommerce-shipping'),
				'value'     => get_post_meta( $variation->ID, '_wf_hs_code', true ),
				'description' => __('HS Code is a standardized system of names and numbers to classify products.','ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
				'placeholder' => __('Harmonized Code','ph-fedex-woocommerce-shipping')
			) );

			// Freight Class Dropdown
			woocommerce_wp_select( 
				array( 
					'id'        => '_wf_freight_class[' . $variation->ID . ']',
					'class'     => 'ph_fedex_variation_class_select',
					'label'     => __( 'Freight Class ', 'ph-fedex-woocommerce-shipping' ), 
					'value'     => get_post_meta( $variation->ID, '_wf_freight_class', true ),
					'options'   =>  array(''=>__('Default','ph-fedex-woocommerce-shipping'))+$this->freight_classes,
					'description'   => __('Leaving default will inherit parent FedEx Freight class.','ph-fedex-woocommerce-shipping'),
					'desc_tip'  => 'true',
				)
			);
			
			// Special Services
			$is_alcohol_product = get_post_meta( $variation->ID ,'_wf_fedex_special_service_types', true );

			woocommerce_wp_checkbox( array(
				'id' 			=> '_wf_fedex_special_service_types[' . $variation->ID . ']',
				'class'     	=> 'ph_fedex_variation_alcohol_product',
				'label'     => __( 'Alcohol ', 'ph-fedex-woocommerce-shipping'),
				'value'     	=> ( $is_alcohol_product == 'ALCOHOL' ? 'yes' : 'no'),
			));

			?><div class="ph_fedex_var_alcohol_recipient"><?php

			// Alcohol Recipient Type
			woocommerce_wp_select( array(
				'id'        => '_wf_fedex_sst_alcohal_recipient[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_alcohol_recipient_type',
				'label'     => __( 'Alcohol Recipient type', 'ph-fedex-woocommerce-shipping'),
				'value'     => get_post_meta( $variation->ID, '_wf_fedex_sst_alcohal_recipient', true ),
				'description'   => __( 'Select the Alcohol Recipient types if applicable .', 'ph-fedex-woocommerce-shipping').'<br />'.__( 'CONSUMER - Select, if no license is required for recipient.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'LICENSEE - Select, if license is required for recipient.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'default'	=> 'LICENSEE',
				'options'   => array(
					'LICENSEE'  => __( 'Licensee', 'ph-fedex-woocommerce-shipping' ),
					'CONSUMER'  => __( 'Consumer', 'ph-fedex-woocommerce-shipping' ),
				),
			));
			?></div><?php

			// Signature Option PDS-179
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_signature_option[' . $variation->ID . ']',
				'class'     	=> 'ph_fedex_variation_class_select',
				'label'     	=> __( 'Delivery Signature', 'ph-fedex-woocommerce-shipping'),
				'value'     	=> get_post_meta( $variation->ID, '_ph_fedex_signature_option', true ),
				'description'   => __( 'FedEx Freight services are not eligible for Signature Service. Hence, Signature option will be ignored for Freight Shipments.', 'ph-fedex-woocommerce-shipping'),
				'desc_tip'  	=> true,
				'options'   	=> array(
					null        			=> __( 'Select Anyone', 'ph-fedex-woocommerce-shipping' ),
					'ADULT'	   				=> __( 'Adult', 'ph-fedex-woocommerce-shipping' ),
					'DIRECT'	  			=> __( 'Direct', 'ph-fedex-woocommerce-shipping' ),
					'INDIRECT'	  			=> __( 'Indirect', 'ph-fedex-woocommerce-shipping' ),
					'NO_SIGNATURE_REQUIRED' => __( 'No Signature Required', 'ph-fedex-woocommerce-shipping' ),
					'SERVICE_DEFAULT'	  	=> __( 'Service Default', 'ph-fedex-woocommerce-shipping' ),
				),
			));

			// Pre-Packed
			$is_pre_packed_var = get_post_meta( $variation->ID, '_wf_fedex_pre_packed_var', true );
			if( empty( $is_pre_packed_var ) ){
				$is_pre_packed_var = get_post_meta( wp_get_post_parent_id($variation->ID), '_wf_fedex_pre_packed', true );
			}
			woocommerce_wp_checkbox( array(
				'id' => '_wf_fedex_pre_packed_var[' . $variation->ID . ']',
				'label' => __(' Pre packed product', 'ph-fedex-woocommerce-shipping'),
				'description' => __('Check this if the item comes in boxes. It will override global product settings', 'ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
				'value'         => $is_pre_packed_var,
			) );

			//Non-Standard Prducts
			woocommerce_wp_checkbox(
				array(
					'id'            => '_wf_fedex_non_standard_product[' . $variation->ID . ']',
					'label'         => __('Non-Standard product ','ph-fedex-woocommerce-shipping'),
					'value'         => get_post_meta( $variation->ID, '_wf_fedex_non_standard_product', true ),
					'description'   => __('Check this if the product belongs to Non Standard Container. Non-Stantard product will be charged heigher', 'ph-fedex-woocommerce-shipping'),
					'desc_tip'      => 'true',
				)
			);

			// Custom Declared Value field
			woocommerce_wp_text_input( array(
				'id' => '_wf_fedex_custom_declared_value['.$variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_select',
				'label' => __('Custom Declared Value','ph-fedex-woocommerce-shipping'),
				'value'     => get_post_meta( $variation->ID, '_wf_fedex_custom_declared_value', true ),
				'description' => __('This amount will be reimbursed from FedEx if products get damaged and you have opt for Insurance.','ph-fedex-woocommerce-shipping'),
				'desc_tip' => 'true',
				'placeholder' => __('Insurance amount FedEx','ph-fedex-woocommerce-shipping')
			) );

			//Dangerous Goods Options
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_dg_option[' . $variation->ID . ']',
				'class'     	=> 'ph_fedex_variation_dg_option ph_fedex_variation_class_select',
				'label'     	=> __( 'Dangerous Goods Option Type ', 'ph-fedex-woocommerce-shipping'),
				'value'     	=> $dgOptionValue,
				'description'   => __( 'Select Dangerous Goods Option Type.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  	=> true,
				'options'   	=> array(
					'NONE'   						=> __( 'None', 'ph-fedex-woocommerce-shipping' ),
					'LIMITED_QUANTITIES_COMMODITIES'=> __( 'Limited Quantities Commodities', 'ph-fedex-woocommerce-shipping' ),
					'HAZARDOUS_MATERIALS'    		=> __( 'Hazardous Materials', 'ph-fedex-woocommerce-shipping' ),
					'BATTERY'  						=> __( 'Battery', 'ph-fedex-woocommerce-shipping' ),
					'ORM_D'    						=> __( 'ORM D', 'ph-fedex-woocommerce-shipping' ),
					'SMALL_QUANTITY_EXCEPTION'		=> __( 'Small Quantity Exception', 'ph-fedex-woocommerce-shipping'),
				),
			));

			?><div class="ph_fedex_var_dangerous_goods_ormd"><?php

			//Dangerous Goods Regulations
			woocommerce_wp_select( array(
				'id'        => '_wf_fedex_dg_regulations[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_select',
				'label'     => __( 'Dangerous Goods Regulation ', 'ph-fedex-woocommerce-shipping'),
				'value'     => get_post_meta( $variation->ID, '_wf_fedex_dg_regulations', true ),
				'description'   => __( 'Select the regulation .', 'ph-fedex-woocommerce-shipping').'<br />'.__( 'ADR - European Agreement concerning the International Carriage of Dangerous Goods by Road.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'DOT - U.S. Department of Transportation has primary responsibility for overseeing the transportation in commerce of hazardous materials, commonly called "HazMats".', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'IATA - International Air Transport Association Dangerous Goods.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'ORMD - Other Regulated Materials for Domestic transport only.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'options'   => array(
					'DOT'   => __( 'DOT', 'ph-fedex-woocommerce-shipping' ),
					'ADR'   => __( 'ADR', 'ph-fedex-woocommerce-shipping' ),
					'IATA'  => __( 'IATA', 'ph-fedex-woocommerce-shipping' ),
					'ORMD'  => __( 'ORMD', 'ph-fedex-woocommerce-shipping' )
				),
			));
			
			?></div><div class="ph_fedex_var_dangerous_goods_lqc"><?php

			//Dangerous Goods Accessibility
			woocommerce_wp_select( array(
				'id'        => '_wf_fedex_dg_accessibility[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_select',
				'label'     => __( 'Dangerous Goods Accessibility ', 'ph-fedex-woocommerce-shipping'),
				'value'     => get_post_meta( $variation->ID, '_wf_fedex_dg_accessibility', true ),
				'description'   => __( 'Select the accessibility type .', 'ph-fedex-woocommerce-shipping').'<br />'.__( 'ACCESSIBLE - Dangerous Goods shipments must be accessible to the flight crew in-flight.', 'ph-fedex-woocommerce-shipping' ).'<br />'.__( 'INACCESSIBLE - Inaccessible Dangerous Goods (IDG) do not need to be loaded so they are accessible to the flight crew in-flight.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'options'   => array(
					'INACCESSIBLE'  => __( 'Inaccessible', 'ph-fedex-woocommerce-shipping' ),
					'ACCESSIBLE'    => __( 'Accessible', 'ph-fedex-woocommerce-shipping' ),
				),
			));

			//Dangerous Goods Cargo Aircraft Only
			woocommerce_wp_checkbox( array(
				'id' 		  => '_ph_fedex_dg_cargo_aircraft_only[' . $variation->ID . ']',
				'label' 	  => __('Dangerous Goods Cargo Aircraft Only','ph-fedex-woocommerce-shipping'),
				'value'       => get_post_meta( $variation->ID, '_ph_fedex_dg_cargo_aircraft_only', true ),
			));

			?></div><div class="ph_fedex_var_hazardous_materials"><?php

			//Hazmat Identification Number
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_id_num[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_text',
				'label'     => __( 'Identificaton No. ', 'ph-fedex-woocommerce-shipping' ),
				'value'     => get_post_meta( $variation->ID, '_ph_fedex_hp_id_num', true ),
				'description'   => __('Hazardous material regulatory commodity identifier referred to as Department of Transportation (DOT) location ID number (UN or NA).','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder'   => __( 'UN1088', 'ph-fedex-woocommerce-shipping')
			) );

			//Hazmat Packaging Group
			woocommerce_wp_select( array(
				'id'        => '_ph_fedex_hp_packaging_group[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_select',
				'label'     => __( 'Packaging Group ', 'ph-fedex-woocommerce-shipping'),
				'value'     => get_post_meta( $variation->ID, '_ph_fedex_hp_packaging_group', true ),
				'description'   => __( 'Hazardous material packaging group.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  => true,
				'options'   => array(
					'DEFAULT'   => __( 'Default', 'ph-fedex-woocommerce-shipping' ),
					'I'         => __( 'I', 'ph-fedex-woocommerce-shipping' ),
					'II'        => __( 'II', 'ph-fedex-woocommerce-shipping' ),
					'III'       => __( 'III', 'ph-fedex-woocommerce-shipping' )
				),
			));

			//Hazmat Proper Shipping Name
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_proper_shipping_name[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_text',
				'label'     => __( 'Proper Shipping Name ', 'ph-fedex-woocommerce-shipping' ),
				'value'     => get_post_meta( $variation->ID, '_ph_fedex_hp_proper_shipping_name', true ),
				'description'   => __('Hazardous material proper shipping name. Up to three description lines of 50 characters each are allowed for a HazMat shipment. These description elements are formatted on the OP950 form in 25-character columns (up to 6 printed lines).','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder'   => __( 'Acetal', 'ph-fedex-woocommerce-shipping')
			) );

			//Hazmat Hazard Class
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_hazard_class[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_text',
				'label'     => __( 'Hazard Class ', 'ph-fedex-woocommerce-shipping' ),
				'value'     => get_post_meta( $variation->ID, '_ph_fedex_hp_hazard_class', true ),
				'description'   => __('DOT hazardous material class or division.','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder'   => __( '3', 'ph-fedex-woocommerce-shipping')
			) );

			//Hazmat Subsidiary Classes
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_subsidiary_classes[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_text',
				'label'     => __( 'Subsidiary Classes ', 'ph-fedex-woocommerce-shipping' ),
				'value'     => get_post_meta( $variation->ID, '_ph_fedex_hp_subsidiary_classes', true ),
				'description'   => __('Hazardous material subsidiary classes.','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
			) );

			//Hazmat Label Text
			woocommerce_wp_text_input( array(
				'id'        => '_ph_fedex_hp_label_text[' . $variation->ID . ']',
				'class'     => 'ph_fedex_variation_class_text',
				'label'     => __( 'Label Text ', 'ph-fedex-woocommerce-shipping' ),
				'value'     => get_post_meta( $variation->ID, '_ph_fedex_hp_label_text', true ),
				'description'   => __('DOT diamond hazard label type. Can also include limited quantity or exemption number.','ph-fedex-woocommerce-shipping'),
				'desc_tip'  => 'true',
				'placeholder' =>  __( 'FLAMMABLE LIQUID', 'ph-fedex-woocommerce-shipping')
			) );

			?></div><?php

			//Battery Products Checkbox
			woocommerce_wp_checkbox( array(
				'id' 			=> '_battery_products[' . $variation->ID . ']',
				'class'     	=> 'ph_fedex_variation_battery_product',
				'label' 		=> __('Battery Product ','ph-fedex-woocommerce-shipping'),
				'value'     	=> get_post_meta( $variation->ID, '_battery_products', true ),
				'description' 	=> __('Check this to mark the product as a Battery Product.','ph-fedex-woocommerce-shipping'),
				'desc_tip' 		=> 'true',
			));

			?><div class="ph_fedex_var_battery_materials"><?php

			//Battery Material Type
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_battery_material_type[' . $variation->ID . ']',
				'class'     	=> 'ph_fedex_variation_class_select',
				'label'     	=> __( 'Battery Material Type ', 'ph-fedex-woocommerce-shipping'),
				'value'     	=> get_post_meta( $variation->ID, '_ph_fedex_battery_material_type', true ),
				'description'   => __( 'Describes the material composition of a battery or cell.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  	=> true,
				'options'   	=> array(
					'LITHIUM_ION'   => __( 'Lithium Ion', 'ph-fedex-woocommerce-shipping' ),
					'LITHIUM_METAL' => __( 'Lithium Metal', 'ph-fedex-woocommerce-shipping' )
				),
			));

			//Battery Packing Type
			woocommerce_wp_select( array(
				'id'        	=> '_ph_fedex_battery_packing_type[' . $variation->ID . ']',
				'class'     	=> 'ph_fedex_variation_class_select',
				'label'     	=> __( 'Battery Packing Type ', 'ph-fedex-woocommerce-shipping'),
				'value'     	=> get_post_meta( $variation->ID, '_ph_fedex_battery_packing_type', true ),
				'description'   => __( 'Describes the packing arrangement of a battery or cell with respect to other items within the same package.', 'ph-fedex-woocommerce-shipping' ),
				'desc_tip'  	=> true,
				'options'   	=> array(
					'CONTAINED_IN_EQUIPMENT' => __( 'Contained In Equipment', 'ph-fedex-woocommerce-shipping' ),
					'PACKED_WITH_EQUIPMENT'  => __( 'Packed With Equipment', 'ph-fedex-woocommerce-shipping' )
				),
			));

			?></div></div><?php

		}

		// function admin_save_frieght_class( $post_id ) {
		//  if ( isset( $_POST['_wf_freight_class'] ) ) {
		//      update_post_meta( $post_id, '_wf_freight_class', esc_attr( $_POST['_wf_freight_class'] ) );
		//  }
		// }

		function wf_save_custome_product_fields_at_variation( $post_id ) {
			
			// Save HS code field
			if( isset($_POST['_wf_hs_code'][$post_id] ) ) {
				update_post_meta( $post_id, '_wf_hs_code', esc_attr( $_POST['_wf_hs_code'][$post_id] ) );
			}

			$select = $_POST['_wf_freight_class'][ $post_id ];
			if( ! empty( $select ) ) {
				update_post_meta( $post_id, '_wf_freight_class', esc_attr( $select ) );
			}
			
			// Save Alcohol Product
			$alcohol_products =  ( isset( $_POST['_wf_fedex_special_service_types'][$post_id] ) && esc_attr($_POST['_wf_fedex_special_service_types'][$post_id])=='yes') ?  'ALCOHOL' : false;
			update_post_meta( $post_id, '_wf_fedex_special_service_types', $alcohol_products );
			
			
			// Save alcohal recipient types
			if( isset($_POST['_wf_fedex_sst_alcohal_recipient'][$post_id]) ) {
				update_post_meta( $post_id, '_wf_fedex_sst_alcohal_recipient', $_POST['_wf_fedex_sst_alcohal_recipient'][$post_id] );
			}

			// Signature Option PDS-179
			if( isset($_POST['_ph_fedex_signature_option'][$post_id]) ) {
				update_post_meta( $post_id, '_ph_fedex_signature_option', $_POST['_ph_fedex_signature_option'][$post_id] );
			}

			// Save dangerous goods regulations for variation
			if( ! empty($_POST['_wf_fedex_dg_regulations'][$post_id]) ) {
				update_post_meta( $post_id, '_wf_fedex_dg_regulations', $_POST['_wf_fedex_dg_regulations'][$post_id] );
			}
			
			// Save dangerous goods accessibility for variation
			if( ! empty($_POST['_wf_fedex_dg_accessibility'][$post_id]) ) {
				update_post_meta( $post_id, '_wf_fedex_dg_accessibility', $_POST['_wf_fedex_dg_accessibility'][$post_id] );
			}

			// Save dangerous goods options for variation
			if( ! empty($_POST['_ph_fedex_dg_option'][$post_id]) ) {
				update_post_meta( $post_id, '_ph_fedex_dg_option', $_POST['_ph_fedex_dg_option'][$post_id] );
			}

			// Save dangerous goods cargo aircraft only for variation
			$cargo_aircraft =  ( isset( $_POST['_ph_fedex_dg_cargo_aircraft_only'][$post_id] ) && esc_attr($_POST['_ph_fedex_dg_cargo_aircraft_only'][$post_id])=='yes') ? esc_attr($_POST['_ph_fedex_dg_cargo_aircraft_only'][$post_id])  : false;
			update_post_meta( $post_id, '_ph_fedex_dg_cargo_aircraft_only', $cargo_aircraft );

			// Pre-packed
			$checkbox = isset( $_POST['_wf_fedex_pre_packed_var'][ $post_id ] ) ? 'yes' : 'no';
			update_post_meta( $post_id, '_wf_fedex_pre_packed_var', $checkbox );

			// Save Non-Standard product for variation
			$non_standard_product =  ( isset( $_POST['_wf_fedex_non_standard_product'][$post_id] ) && esc_attr($_POST['_wf_fedex_non_standard_product'][$post_id])=='yes') ? esc_attr($_POST['_wf_fedex_non_standard_product'][$post_id])  : false;
			update_post_meta( $post_id, '_wf_fedex_non_standard_product', $non_standard_product );

			// Save Battery Products
			$battery_products =  ( isset( $_POST['_battery_products'][$post_id] ) && esc_attr($_POST['_battery_products'][$post_id])=='yes') ? esc_attr($_POST['_battery_products'][$post_id])  : false;
			update_post_meta( $post_id, '_battery_products', $battery_products );

			// Save Battery Material Type
			if( isset($_POST['_ph_fedex_battery_material_type'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_battery_material_type', esc_attr( $_POST['_ph_fedex_battery_material_type'][$post_id] ) );
			}

			// Save Battery Packing Type
			if( isset($_POST['_ph_fedex_battery_packing_type'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_battery_packing_type', esc_attr( $_POST['_ph_fedex_battery_packing_type'][$post_id] ) );
			}

			// Save Hazmat Identification Number
			if( isset($_POST['_ph_fedex_hp_id_num'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_id_num', esc_attr( $_POST['_ph_fedex_hp_id_num'][$post_id] ) );
			}

			//Save Hazmat Packaging Group
			if( ! empty ($_POST['_ph_fedex_hp_packaging_group'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_packaging_group', $_POST['_ph_fedex_hp_packaging_group'][$post_id] );
			}

			// Save Hazmat Proper Shipping Name
			if( isset($_POST['_ph_fedex_hp_proper_shipping_name'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_proper_shipping_name', esc_attr( $_POST['_ph_fedex_hp_proper_shipping_name'][$post_id] ) );
			}

			// Save Hazmat Hazard Class
			if( isset($_POST['_ph_fedex_hp_hazard_class'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_hazard_class', esc_attr( $_POST['_ph_fedex_hp_hazard_class'][$post_id] ) );
			}

			// Save Hazmat Subsidiary Classes
			if( isset($_POST['_ph_fedex_hp_subsidiary_classes'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_subsidiary_classes', esc_attr( $_POST['_ph_fedex_hp_subsidiary_classes'][$post_id] ) );
			}

			// Save Hazmat Label Text
			if( isset($_POST['_ph_fedex_hp_label_text'][$post_id] ) ) {
				update_post_meta( $post_id, '_ph_fedex_hp_label_text', esc_attr( $_POST['_ph_fedex_hp_label_text'][$post_id] ) );
			}

			// Save Custom Declared Value for Variations
			if( isset($_POST['_wf_fedex_custom_declared_value'][$post_id] ) ) {
				update_post_meta( $post_id, '_wf_fedex_custom_declared_value', esc_attr( $_POST['_wf_fedex_custom_declared_value'][$post_id] ) );
			}
		}
	}
	new WF_Admin_Options();
}