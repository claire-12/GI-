<?php
$compound = $args['compound'];
$content = get_post_field('post_content', $compound);

$compounddetailsid = get_post_id_by_slug(get_the_title($compound), $post_type = "page");

if ($compounddetailsid != "") {
    $postdat = get_post($compounddetailsid);
}

if (empty($content)) {
    $content = '[contact-form-7 id="22b8df7" title="Contact Us Form"]';
}
?>

<div class="modal fade" id="compoundModal<?php echo $compound ?>" tabindex="-1" aria-labelledby="compoundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body compound-modal-content">
                <div class="row">
                    <div class="col-12">
                        <h3 class="pre-heading heading-center">
                            <?php echo $postdat->post_title; ?>
                        </h3>
                    </div>
                    <div class="col-12 compoundtablewrap">
                        <?php
                        echo apply_filters('the_content', $postdat->post_content);
                        ?>
                        <button class="print-pdf-button" onclick="downloadPDF(<?php echo $compound; ?>, '<?php echo $postdat->post_title; ?>')">Print PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>