<div class="serminar-content">
	<div class="row">
		<div class="col-md-8">
			<div class="serminar-detail">
				<h1 class="post-title"><?php printf( __('Seminar: %s','cabling'), get_the_title() ); ?></h1>

				<div class="serminar-meta">
					<p class="date"><?php the_field('_training_date') ?></p>
					<p class="place"><?php printf( __('Place: %s','cabling'), get_field('_training_place') ); ?></p>
					<p class="time"><?php printf( __('Time: %s','cabling'), get_field('_training_time') ); ?></p>
					<p class="price"><?php printf( __('Regular price: %s','cabling'), get_field('_training_price') ); ?></p>
				</div>
				<div class="s-content"><?php the_content(); ?></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="blue-linkbox">
				<a href="<?php echo add_query_arg( 'data', base64_encode( get_the_ID() ), home_url('/training-registration/') ); ?>">
					<div style="csc-header">
						<h2><?php _e('Reserve now!','cabling') ?></h2>
					</div>
					<!-- <p class="csc-subheader">Find the answers here</p> -->
				</a>
			</div>
			<div class="blue-linkbox">
				<a href="<?php echo home_url('/contact/'); ?>">
					<div style="csc-header">
						<h2><?php _e('Questions?','cabling') ?></h2>
					</div>
					<!-- <p class="csc-subheader">Find the answers here</p> -->
				</a>
			</div>
			<div class="serminar-contact">
				<h5><?php _e('Contact','cabling') ?></h5>
				<?php the_field('_training_contact') ?>
			</div>
		</div>
	</div>
</div>