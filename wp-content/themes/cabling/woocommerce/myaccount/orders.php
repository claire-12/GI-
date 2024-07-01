<?php
$current_user_id = get_current_user_id();
global $wpdb;
$query = "
    SELECT *
    FROM {$wpdb->prefix}posts AS p
    INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
    WHERE p.post_type = 'shop_order'
    AND p.post_status IN ('wc-completed', 'wc-processing','wc-on-hold','wc-refunded','wc-pending','confirming-order') 
    AND pm.meta_key = '_customer_user'
    AND pm.meta_value = $current_user_id
	ORDER BY p.ID DESC
";
$customer_orders = new stdClass();
$has_orders = $wpdb->get_results( $query );
$customer_orders->orders = $has_orders;
$customer_orders->max_num_pages = 1;


do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
<style>
	td.ct-order-actions {
		display: flex;
		gap: 10px;
	}
	#my-orders-table_wrapper .dt-length {
		display: none;
	}
	.dt-search label {
		position: absolute;
		left: 10px;
		top: 12px;
	}
	.dt-search .dt-input {
		padding-left: 60px !important;
	}
</style>
	<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
	<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

	<table id="my-orders-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<th>Order Number</th>
				<th>Date</th>
				<th>Status</th>
				<th>Total</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<td>
						<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
							<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
						</a>
					</td>
					<td>
						<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
					</td>
					<td>
						<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
					</td>
					<td>
						<?php
						/* translators: 1: formatted order total 2: total order items */
						echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
						?>
					</td>
					<td class="ct-order-actions">
					<?php
						$actions = wc_get_account_orders_actions( $order );

						if ( ! empty( $actions ) ) {
							foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
								echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button' . esc_attr( $wp_button_class ) . ' button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
							}
						}
						?>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<script>
		jQuery('#my-orders-table').DataTable({
			pageLength: 10,
			columnDefs: [
				{ orderable: false, targets: -1 }
			]
		});
	</script>

<?php else : ?>

	<?php wc_print_notice( esc_html__( 'No order has been made yet.', 'woocommerce' ) . ' <a class="woocommerce-Button wc-forward button' . esc_attr( $wp_button_class ) . '" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">' . esc_html__( 'Browse products', 'woocommerce' ) . '</a>', 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment ?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>