<?php

/*
USAGE:
am2_generate_image_item( $att_id, 'thumbnail', 2, 80, false);
--- This will regenerate attachment with id $att_id, it will check if 'thumbnail' size exists and if not it will create it.
It will use 80 for jpeg quality. On page load it will run maximum 2 times.

am2_generate_image_item( $att_id, 'thumbnail', 99, 80, true);
--- This will regenerate attachment with id $att_id, it will force regenerate regardless if size exists.
It will use 80 for jpeg quality. On page load it will tun maximum 99 times.

*/


function am2_generate_image_item( $attachment_id, $image_size, $max_per_page_load, $jpeg_quality, $force_resize = false ){

	if(empty($attachment_id)) return;
	if(empty($image_size)) return;

	if(empty($max_per_page_load)) $max_per_page_load = 2;
	if(empty($jpeg_quality)) $jpeg_quality = 90;

	global $_wp_additional_image_sizes;
	if(empty($_wp_additional_image_sizes[$image_size])) return;


	global $am2_count_resize;
    if(empty($am2_count_resize)){ $am2_count_resize = 0; }
	if($am2_count_resize >= $max_per_page_load) return;

	//$post_thumbnail_id = get_post_thumbnail_id( $post_id );
	$current_attachment = wp_get_attachment_metadata( $attachment_id );
	if(empty($current_attachment)){
		include_once(ABSPATH .'admin/includes/image.php' );
		$image = wp_get_attachment_image_src($attachment_id, 'full');
		$att_metadata = wp_generate_attachment_metadata( $attachment_id, $image[0]);
		wp_update_attachment_metadata( $attachment_id, $att_metadata );
		$current_attachment = wp_get_attachment_metadata( $attachment_id );
	}

	if(
		($_wp_additional_image_sizes[$image_size]['width'] != 9999 && $current_attachment['width'] < $_wp_additional_image_sizes[$image_size]['width']) ||
		($_wp_additional_image_sizes[$image_size]['height'] != 9999 && $current_attachment['height'] < $_wp_additional_image_sizes[$image_size]['height'])
		){
		//original image is too small
		return;
	}

	/*
	$image_attributes = wp_get_attachment_image_src( $attachment_id, $image_size );
    $image_src = $image_attributes[0];
    $image_width = $image_attributes[1];
    $image_height = $image_attributes[2];
    */
    if((empty($current_attachment['sizes'][$image_size]) || $force_resize == true)) { // resize only 2 images at a time

        include_once(ABSPATH .'admin/includes/image.php' );

        $am2_count_resize++;

		global $am2_resize_images_to;
		foreach($current_attachment['sizes'] as $size => $value){
			$am2_resize_images_to[] = $size;
		}
		$am2_resize_images_to[] = $image_size;

		add_filter('intermediate_image_sizes_advanced', 'am2_handle_media_image_sizes');
		add_filter( 'jpeg_quality', create_function( '', 'return '.$jpeg_quality.';' ) );

        $file = get_attached_file( $attachment_id );
        $att_metadata = wp_generate_attachment_metadata( $attachment_id, $file);
        wp_update_attachment_metadata( $attachment_id, $att_metadata );

    }

    return;
}
?>