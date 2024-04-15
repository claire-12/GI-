<?php
/**
 * Template Name: Auto Upload File
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

$dir = '/home/ext.infolabix/ftp_files/';

clearstatcache();
if (is_dir($dir)) {
    $files = scandir($dir);
    $files = array_diff(scandir($dir), array('.', '..'));

	if (count($files)) {
		global $wpdb;
		
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		foreach ($files as $file_name) {
			$file = $dir . $file_name;
			$file_info = pathinfo($file);

			$file_array = array();

			// Get filename and store it into $file_array
			// Add more file types if necessary
			//preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png|pdf)\b/i', $file, $matches );
			$file_array['name'] = $file_name;
			$file_array['tmp_name'] = $file;

			//check if file existed
			$attachment = $wpdb->get_var( $wpdb->prepare( 
				"
					SELECT COUNT(*) 
					FROM $wpdb->posts
					WHERE post_title = %s
				", 
				$file_info['filename']
			) );
			//var_dump($attachment);
			if( !$attachment ){
				// Store and validate
				$id = media_handle_sideload( $file_array, 0, $file_info['filename'] );

				// Unlink if couldn't store permanently
				if ( is_wp_error( $id ) ) {
					echo "<strong>" . $file . "</strong> -- " . $id->get_error_messages()[0] . "<br><br>";
				}else{
					echo "<strong>" . $file . '</strong>: attachment ID: ' . $id . "<br><br>";
				}
			}else{
				unlink($file);
				echo "<strong>" . $file . "</strong>: was existed<br><br>";
			}
			
    	}
        
    }else{
    	echo 'Nothing to do';
    }
}
