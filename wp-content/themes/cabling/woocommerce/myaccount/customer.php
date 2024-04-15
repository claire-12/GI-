<?php
$user_id = get_current_user_id();
$customer_users = cabling_get_user_by_customer($user_id);
?>
<div class="row mb-3">
	<div class="col-12">
		<div class="d-flex justify-content-between">
			<h3><?php _e('Users management','cabling'); ?></h3>
			<button type="button" class="btn btn-primary btn-add_customer" data-bs-toggle="modal" data-bs-target="#create_customer"><?php _e('Add new','cabling'); ?></button>
		</div>
	</div>
</div>
<?php if ( $customer_users ): ?>
<table class="table table-bordered child-customer">
	<thead>
		<tr>
			<th><?php _e('ID','cabling'); ?></th>
			<th><?php _e('Name','cabling'); ?></th>
			<th><?php _e('Email','cabling'); ?></th>
			<th><?php _e('Phone','cabling'); ?></th>
			<th><?php _e('Department','cabling'); ?></th>
			<th><?php _e('Last login','cabling'); ?></th>
            <th><?php _e('Active','cabling'); ?></th>
			<th><?php _e('Actions','cabling'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($customer_users as $user): ?>
		<?php
			$has_approve_val = get_user_meta( $user->ID, 'has_approve', true );
			$wc_last_active = get_user_meta( $user->ID, 'wc_last_active', true );
			$wc_last_active = empty($wc_last_active) ? '---' : date('Y-m-d', $wc_last_active);
		?>
		<tr>
			<td><?php echo $user->ID; ?></td>
			<td><?php printf('%s %s', $user->first_name, $user->last_name); ?></td>
			<td><a href="mailto:<?php echo $user->data->user_email; ?>"><?php echo $user->data->user_email; ?></a></td>
			<td><?php echo get_user_phone_number($user->ID); ?></td>
            <td><?php echo get_user_meta( $user->ID, 'user_department', true ); ?></td>
            <td><?php echo $wc_last_active; ?></td>
            <td>
                <?php if ('true' == $has_approve_val): ?>
                    <strong>
                        <?php echo __('Verified', 'cabling'); ?>
                    </strong>
                <?php else: ?>
                    <a href="#" class="resend-verify_email" data-action="<?php echo $user->ID; ?>" data-email="<?php echo $user->data->user_email; ?>">
                        <?php echo __('Resend email','cabling'); ?>
                    </a>
                <?php endif ?>
            </td>
            <td style="font-size: larger">
                <span class="delete-child text-danger" data-action="<?php echo $user->data->user_email; ?>">
                    <i class="fa-solid fa-trash"></i>
                </span>
                <span class="edit-child text-success" data-action="<?php echo $user->data->user_email; ?>">
                    <i class="fa-solid fa-square-pen"></i>
                </span>
            </td>
		</tr>
		<?php endforeach ?>

	</tbody>
</table>
<?php endif ?>
