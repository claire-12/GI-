<?php

if (!defined('ABSPATH')) exit;

/**
 *
 */
class CFDB7_Form_Details
{
    private $form_id;
    private $form_post_id;


    public function __construct()
    {
        $this->form_post_id = isset($_GET['fid']) ? (int)$_GET['fid'] : 0;
        $this->form_id = isset($_GET['ufid']) ? (int)$_GET['ufid'] : 0;

        $this->form_details_page();
    }

    public function form_details_page()
    {
        global $wpdb;
        $cfdb = apply_filters('cfdb7_database', $wpdb);
        $table_name = $cfdb->prefix . 'db7_forms';
        $upload_dir = wp_upload_dir();
        $cfdb7_dir_url = $upload_dir['baseurl'] . '/cfdb7_uploads';
        $rm_underscore = apply_filters('cfdb7_remove_underscore_data', true);


        $results = $cfdb->get_results("SELECT * FROM $table_name WHERE form_post_id = $this->form_post_id AND form_id = $this->form_id LIMIT 1", OBJECT);


        if (empty($results)) {
            wp_die('Not valid contact form');
        }
        ?>
        <div class="wrap">
            <div id="welcome-panel" class="cfdb7-panel">
                <div class="cfdb7-panel-content">
                    <div class="welcome-panel-column-container">
                        <?php do_action('cfdb7_before_formdetails_title', $this->form_post_id); ?>
                        <h3><?php echo get_the_title($this->form_post_id); ?></h3>
                        <?php do_action('cfdb7_after_formdetails_title', $this->form_post_id); ?>

                        <p><strong>Date: </strong><?php echo $results[0]->form_date; ?></p>
                        <?php $form_data = unserialize($results[0]->form_value);
                        $email = '';
                        foreach ($form_data as $key => $data):

                            $matches = array();
                            $key = esc_html($key);

                            if ($key == 'cfdb7_status') continue;
                            if ($rm_underscore) preg_match('/^_.*$/m', $key, $matches);
                            if (!empty($matches[0])) continue;

                            if (strpos($key, 'cfdb7_file') !== false) {

                                $key_val = str_replace('cfdb7_file', '', $key);
                                $key_val = str_replace('your-', '', $key_val);
                                $key_val = str_replace(array('-', '_'), ' ', $key_val);
                                $key_val = ucwords($key_val);
                                echo '<p><b>' . $key_val . '</b>: <a href="' . $cfdb7_dir_url . '/' . $data . '">'
                                    . $data . '</a></p>';
                            } else {

                                if (is_array($data)) {

                                    $key_val = str_replace('your-', '', $key);
                                    $key_val = str_replace(array('-', '_'), ' ', $key_val);
                                    $key_val = ucwords($key_val);
                                    $arr_str_data = implode(', ', $data);
                                    $arr_str_data = esc_html($arr_str_data);
                                    echo '<p><b>' . $key_val . '</b>: ' . nl2br($arr_str_data) . '</p>';

                                } else {

                                    $key_val = str_replace('your-', '', $key);
                                    $key_val = str_replace(array('-', '_'), ' ', $key_val);

                                    $key_val = ucwords($key_val);
                                    $data = esc_html($data);
                                    echo '<p><b>' . $key_val . '</b>: ' . nl2br($data) . '</p>';
                                }

                                if ($key_val == 'Email') {
                                    $email = $arr_str_data ?? $data;
                                }
                            }

                        endforeach;
                        $form_id = $results[0]->form_id;
                        if ($form_data['cfdb7_status'] == 'unread') {
                            $form_data['cfdb7_status'] = 'read';
                            $form_data = serialize($form_data);

                            $cfdb->query("UPDATE $table_name SET form_value =
                            '$form_data' WHERE form_id = '$form_id' LIMIT 1"
                            );
                        }
                        ?>
                    </div>

                    <?php if (empty($form_data['cfdb7_status']) || ($form_data['cfdb7_status'] !== 'replied')): ?>
                    <p>
                        <button class="button button-primary" onclick="showContactReply()">
                            Reply
                        </button>
                    </p>
                    <?php else: ?>
                    <p><strong>REPLIED</strong></p>
                    <?php endif; ?>

                    <div id="reply-form" style="display: none">
                        <hr>
                        <h4>Reply to Contact Form Submission</h4>
                        <form method="post" enctype="multipart/form-data">
                            <?php wp_nonce_field('reply_form_contact', '_contact_nonce') ?>
                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                            <input type="hidden" name="form_post_id" value="<?php echo $this->form_post_id; ?>">
                            <p class="form-field">
                                <label for="to">To:</label><br>
                                <input type="email" name="to" id="to" value="<?php echo $email; ?>" required>
                            </p>
                            <p class="form-field">
                                <label for="subject">Subject:</label><br>
                                <input type="text" name="subject" id="subject" required>
                            </p>
                            <p class="form-field">
                                <label for="message">Message:</label><br>
                                <textarea name="message" id="message" rows="5" required></textarea>
                            </p>
                            <p class="form-field">
                                <label for="message">Attachments:</label><br>
                                <input type="file" name="attachments[]" id="attachments" multiple>
                            </p>
                            <br>
                            <input type="submit" class="button button-primary" value="Send Reply">
                        </form>
                    </div>
                </div>
                <script>
                    function showContactReply() {
                        document.getElementById('reply-form').style.display = 'block';
                    }
                </script>
            </div>
        </div>
        <?php
        do_action('cfdb7_after_formdetails', $this->form_post_id);
    }

}
