# AM2-WP-regenerate-image-sizes
This code snippet will regenerate image sizes in WP on the fly.
Case 1: New image sizes are introduced and old images need to be regenerated
Case 2: We have a bunch of image sizes and we do not want to generate all of them for every post type, so we generate just a few image sizes

USAGE:
am2_generate_image_item( $att_id, 'thumbnail', 2, 80, false);
--- This will regenerate attachment with id $att_id, it will check if 'thumbnail' size exists and if not it will create it.
It will use 80 for jpeg quality. On page load it will run maximum 2 times.

am2_generate_image_item( $att_id, 'thumbnail', 99, 80, true);
--- This will regenerate attachment with id $att_id, it will force regenerate regardless if size exists.
It will use 80 for jpeg quality. On page load it will tun maximum 99 times.

*/
