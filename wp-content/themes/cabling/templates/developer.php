<?php
/**
 * Template Name: Developer
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */
if (isset($_REQUEST['your-product'])) {
    $name_title = get_name_title($_REQUEST['your-title']);
    $productofinterest = get_product_of_interests($_REQUEST['your-product']);
    $form = array(
            'email' => $_REQUEST['your-email'],
            'company' => $_REQUEST['your-company-sector'],
            'lastname' => $_REQUEST['your-name'],
            'mobile' => $_REQUEST['your-phone'],
            'jobtitle' => $name_title,
            'message' => $_REQUEST['your-message'],
            'product' => $productofinterest,
    );

    $crm = new CRMController();
    $lead = $crm->processContactUsSubmit($form);
    var_dump($lead);
    //exit();
}
get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="container">
                <h1>DEVELOPER</h1>
                <div class="row">
                    <div class="col-12">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="preName" class="form-label">Pre Name</label>
                                <select id="preName" class="form-select" name="your-title">
                                    <option selected disabled>Select Pre Name</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mr.">Mr.</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="your-name" required>
                            </div>
                            <div class="mb-3">
                                <label for="company" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="company" name="your-company-sector" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Professional Email <span
                                            class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="your-email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Professional Mobile Number <span
                                            class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="your-phone" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">What Datwyler product are you most interested in?</label>
                                <select class="form-select" name="your-product">
                                    <option selected disabled>Select Product</option>
                                    <option value="O-Ring">O-Ring</option>
                                    <option value="Custom Molded Rubber Seals">Custom Molded Rubber Seals</option>
                                    <option value="Rubber to Metal Bonded Seals">Rubber to Metal Bonded Seals</option>
                                    <option value="Rubber to Plastic Bonded Seals">Rubber to Plastic Bonded Seals
                                    </option>
                                    <option value="Custom Machined Metal Parts">Custom Machined Metal Parts</option>
                                    <option value="Machined Thermoplastic Parts">Machined Thermoplastic Parts</option>
                                    <option value="Molded Resins">Molded Resins</option>
                                    <option value="Surface Production Equipment">Surface Production Equipment</option>
                                    <option value="Wearable Sensors">Wearable Sensors</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="your-message" class="form-label">Message</label>
                                <textarea class="form-control" id="your-message" name="your-message"
                                          rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
