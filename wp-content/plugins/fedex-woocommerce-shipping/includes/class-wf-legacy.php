<?php
if(!class_exists('wf_order') && class_exists('WC_Order')){
	class wf_order extends WC_Order{
		
		/**
		 * ID
		 */
		public $id;
		/**
		 * Shipping Variables
		 */
		public $shipping_country, $shipping_first_name, $shipping_last_name, $shipping_company, $shipping_address_1, $shipping_address_2, $shipping_city, $shipping_state, $shipping_postcode;
		/**
		 * Billing Variables
		 */
		public $billing_email, $billing_phone, $billing_address_1, $billing_address_2, $billing_city, $billing_postcode, $billing_country, $billing_state, $billing_company, $billing_first_name, $billing_last_name;
		/**
		 * WC Version
		 */
		public $wc_version;
		/**
		 * Settings
		 */
		public $settings;
		
		public function __construct( $order ){
			global $woocommerce;
			$this->wc_version = WC()->version;
			$this->set_order( $order );
		}

		public function set_order( $order ){
			if( is_numeric( $order) ){
				parent::__construct( $order );
			}elseif( is_object( $order) ){
				parent::__construct( $this->get_id_from_order_obj( $order ) );
			}
			$this->set_order_properties();
		}

		private function get_id_from_order_obj( $order_obj ){
			return ( $this->wc_version < '2.7.0' ) ? $order_obj->id : $order_obj->get_id();
		}

		private function set_order_properties(){
			$this->id 					= ( $this->wc_version < '2.7.0' ) ? $this->id : $this->get_id();
			$this->shipping_country 	= ( $this->wc_version < '2.7.0' ) ? $this->shipping_country : $this->get_shipping_country();
			$this->shipping_first_name 	= ( $this->wc_version < '2.7.0' ) ? $this->shipping_first_name : $this->get_shipping_first_name();
			$this->shipping_last_name 	= ( $this->wc_version < '2.7.0' ) ? $this->shipping_last_name : $this->get_shipping_last_name();
			$this->shipping_company 	= ( $this->wc_version < '2.7.0' ) ? $this->shipping_company : $this->get_shipping_company();
			$this->shipping_address_1 	= ( $this->wc_version < '2.7.0' ) ? $this->shipping_address_1 : $this->get_shipping_address_1();
			$this->shipping_address_2 	= ( $this->wc_version < '2.7.0' ) ? $this->shipping_address_2 : $this->get_shipping_address_2();
			$this->shipping_city 		= ( $this->wc_version < '2.7.0' ) ? $this->shipping_city : $this->get_shipping_city();
			$this->shipping_state 		= ( $this->wc_version < '2.7.0' ) ? $this->shipping_state : $this->get_shipping_state();
			$this->shipping_postcode 	= ( $this->wc_version < '2.7.0' ) ? $this->shipping_postcode : $this->get_shipping_postcode();
			$this->billing_email 		= ( $this->wc_version < '2.7.0' ) ? $this->billing_email : $this->get_billing_email();
			$this->billing_phone 		= ( $this->wc_version < '2.7.0' ) ? $this->billing_phone : $this->get_billing_phone();
			$this->billing_address_1 	= ( $this->wc_version < '2.7.0' ) ? $this->billing_address_1 : $this->get_billing_address_1();
			$this->billing_address_2 	= ( $this->wc_version < '2.7.0' ) ? $this->billing_address_1 : $this->get_billing_address_2();
			$this->billing_city 		= ( $this->wc_version < '2.7.0' ) ? $this->billing_city : $this->get_billing_city();
			$this->billing_postcode 	= ( $this->wc_version < '2.7.0' ) ? $this->billing_postcode  : $this->get_billing_postcode();
			$this->billing_country 		= ( $this->wc_version < '2.7.0' ) ? $this->billing_country  : $this->get_billing_country();
			$this->billing_state 		= ( $this->wc_version < '2.7.0' ) ? $this->billing_state  : $this->get_billing_state();
			$this->billing_company 		= ( $this->wc_version < '2.7.0' ) ? $this->billing_company  : $this->get_billing_company();
			$this->billing_first_name 	= ( $this->wc_version < '2.7.0' ) ? $this->billing_first_name  : $this->get_billing_first_name();
			$this->billing_last_name 	= ( $this->wc_version < '2.7.0' ) ? $this->billing_last_name  : $this->get_billing_last_name();

			$this->settings = get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null );

			if ( empty( $this->billing_phone ) && isset( $this->settings['default_recipient_phone_num'] ) && isset( $this->settings['default_recipient_phone'] ) && $this->settings['default_recipient_phone'] == 'yes' ) {

				$this->billing_phone = !empty( $this->settings['default_recipient_phone_num'] ) ? $this->settings['default_recipient_phone_num'] : '';
			}
		}

		public function get_order_currency(){
			return ( $this->wc_version < '2.7.0' ) ? parent::get_order_currency() : $this->get_currency();
		}
		public function get_shipping_country($context = 'view'){
			return ( $this->wc_version < '2.7.0' ) ? $this->shipping_country : parent::get_shipping_country();
		}
	}
}


if( !class_exists('wf_product') ){
	class wf_product{
		public $id;
		public $length;
		public $width;
		public $height;
		public $weight;
		public $variation_id;
		public $obj;
		public $wc_version;
		public $product_type;
		public function __construct( $item ){
			$this->wc_version 	= WC()->version;
			$this->obj 		= is_object($item) ? $item : wc_get_product( $item );
			$this->set_item_properties();
		}

		public function __call( $method_name, $args ){
			return method_exists($this, $method_name) ? $this->$method_name() : $this->obj->$method_name();;
		}


		private function set_item_properties(){
			$this->id 			= ( $this->wc_version < '2.7.0' ) ? $this->obj->id : $this->obj->get_id();
			$this->length 		= ( $this->wc_version < '2.7.0' ) ? $this->obj->length : $this->obj->get_length();
			$this->width 		= ( $this->wc_version < '2.7.0' ) ? $this->obj->width : $this->obj->get_width();
			$this->height 		= ( $this->wc_version < '2.7.0' ) ? $this->obj->height : $this->obj->get_height();
			$this->weight 		= ( $this->wc_version < '2.7.0' ) ? $this->obj->weight : $this->obj->get_weight();
			$this->variation_id	= ( $this->wc_version < '2.7.0' ) ? $this->obj->variation_id : $this->obj->get_id(); //get_id will always be the variation ID if this is a variation
			$this->product_type	= ( $this->wc_version < '2.7.0' ) ? $this->obj->product_type : $this->obj->get_type(); 
		}

		public function get_type(){
			return $this->product_type;
		}
		public function get_weight(){
			return $this->weight;
		}
		public function get_id(){
			return $this->id;
		}
		public function get_length(){
			return $this->length;
		}
		public function get_width(){
			return $this->width;
		}
		public function get_height(){
			return $this->height;
		}

	}
}
