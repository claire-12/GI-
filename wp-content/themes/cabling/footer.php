<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cabling
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="footer-top">
			<div class="container">
			    <div class="row">
			        <div class="col-md-6 col-xs-12">
                        <?php if (is_active_sidebar( 'footer-2' ) ): ?>
			            	<?php dynamic_sidebar('footer-2'); ?>
			            <?php endif ?>
                        <div class="footer-brand">
                            <p class="heading"><?php _e('Our brands','cabling') ?></p>
                            <?php if (is_active_sidebar( 'footer-brand' ) ): ?>
                                <?php dynamic_sidebar('footer-brand'); ?>
                            <?php endif ?>
                        </div>
			        </div><!--.col-->

			        <div class="col-md-6 col-xs-12 footer-right">
                        <div class="footer-social">
                            <div class="datwyler-mobility">
                                <h5><a href="https://datwyler.com/" target="_blank"><?php echo __('Datwyler Group', 'cabling') ?></a></h5>
                                <ul>
                                    <li><a href="https://datwyler.com/mobility" target="_blank"><?php echo __('Mobility', 'cabling') ?></a></li>
                                    <li><a href="https://datwyler.com/healthcare" target="_blank"><?php echo __('Healthcare', 'cabling') ?></a></li>
                                    <li><a href="https://datwyler.com/connectors" target="_blank"><?php echo __('Connectors', 'cabling') ?></a></li>
                                    <li><a href="https://datwyler.com/food-beverage" target="_blank"><?php echo __('Food & Beverage', 'cabling') ?></a></li>
                                </ul>
                            </div>
							<!--
                            <div class="footer-brand">
                                <p class="heading"><?php _e('Follow us', 'cabling') ?></p>
                                <?php if (is_active_sidebar('footer-copyright')): ?>
                                    <?php dynamic_sidebar('footer-copyright'); ?>
                                <?php endif ?>
                            </div>
-->
							<div class="footer-brand">
								<p class="heading"><?php _e('Follow us', 'cabling') ?></p>
								<section id="block-17" class="widget widget_block">
<ul class="wp-block-social-links is-layout-flex wp-block-social-links-is-layout-flex">
									<li class="wp-social-link wp-social-link-linkedin  wp-block-social-link"><a target="new" href="https://www.linkedin.com/company/parcoinc/" class="wp-block-social-link-anchor"><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M19.7,3H4.3C3.582,3,3,3.582,3,4.3v15.4C3,20.418,3.582,21,4.3,21h15.4c0.718,0,1.3-0.582,1.3-1.3V4.3 C21,3.582,20.418,3,19.7,3z M8.339,18.338H5.667v-8.59h2.672V18.338z M7.004,8.574c-0.857,0-1.549-0.694-1.549-1.548 c0-0.855,0.691-1.548,1.549-1.548c0.854,0,1.547,0.694,1.547,1.548C8.551,7.881,7.858,8.574,7.004,8.574z M18.339,18.338h-2.669 v-4.177c0-0.996-0.017-2.278-1.387-2.278c-1.389,0-1.601,1.086-1.601,2.206v4.249h-2.667v-8.59h2.559v1.174h0.037 c0.356-0.675,1.227-1.387,2.526-1.387c2.703,0,3.203,1.779,3.203,4.092V18.338z"></path></svg><span class="wp-block-social-link-label screen-reader-text">LinkedIn</span></a>
									</li>
								</ul>
								</section>
							</div>
                        </div>
			        </div><!-- .col -->
			    </div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
