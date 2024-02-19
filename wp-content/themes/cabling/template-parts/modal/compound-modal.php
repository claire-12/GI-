<?php
$compound = $args['compound'];
$content = get_post_field('post_content', $compound);


$compounddetailsid=get_post_id_by_slug( get_the_title($compound), $post_type = "page" );

if($compounddetailsid!=""){
$postdat=get_post($compounddetailsid);
}

if (empty($content)){
    $content = '[contact-form-7 id="22b8df7" title="Contact Us Form"]';
}
?>
<div class="modal fade" id="compoundModal<?php echo $compound ?>" tabindex="-1"
     aria-labelledby="compoundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body compound-modal-content">
                <div class="row">
                    <div class="col-12">
                        <h3 class="pre-heading heading-center">
							<?php //echo get_the_title($compound) ?>
						<?php echo $postdat->post_title;?>
						</h3>
                    </div>
                    <div class="col-12 compoundtablewrap">
                        <?php
                        //echo apply_filters('the_content', $content);
                        echo apply_filters('the_content',$postdat->post_content);
                        ?>
                    </div>
                    <!--<div class="col-12 col-lg-6">
                        <?php
/*                        $key_benefits = get_field('key_benefits', $compound);
                        $use_with = get_field('use_with', $compound);
                        $do_not_use_with = get_field('do_not_use_with', $compound);
                        $typical_values_for_compound = get_field('typical_values_for_compound', $compound);

                        wc_get_template('single-product/product-simple.php', [
                            'key_benefits' => $key_benefits,
                            'use_with' => $use_with,
                            'do_not_use_with' => $do_not_use_with,
                            'typical_values_for_compound' => $typical_values_for_compound,
                        ]);
                        */?>
                        <a href="<?php /*echo get_the_permalink($compound) */?>" class="block-button"><?php /*echo __('View Solutions') */?></a>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
