<?php
/**
 * Plugin Name: Custom API Endpoint
 * Description: Adds a custom API endpoint to retrieve the URL of the last uploaded image.
 * Version: 1.0
 * Author: Hasan Qazi
 * Author URI: amazonhoster.com
 */

// Add the custom API endpoint
add_action('rest_api_init', 'register_custom_api_endpoint');
function register_custom_api_endpoint() {
    register_rest_route('my-api/v1', '/last-uploaded-image', array(
        'methods' => 'GET',
        'callback' => 'get_last_uploaded_image_url',
    ));
}

// Callback function to retrieve the last uploaded image URL
function get_last_uploaded_image_url(WP_REST_Request $request) {
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'orderby' => 'post_date',
        'order' => 'DESC',
    );

    $latest_images = get_posts($args);

    if (empty($latest_images)) {
        return new WP_REST_Response(array('error' => 'No images found.'), 404);
    }

    $attachment_id = $latest_images[0]->ID;
    $image_url = wp_get_attachment_url($attachment_id);

    return $image_url;
}
