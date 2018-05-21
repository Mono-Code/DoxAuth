<?php
add_action( 'doxauth_success_registration', function( $session_info ) {

    // Build Query String
    $session_query_string = '';
    if( isset( $session_info['doximity_query_string'] ) ) {
        $session_query_string = "?" . $session_info['doximity_query_string'];
    }

    $custom_redirect = get_option( 'doxauth_success_registration' );
    if( !empty( $custom_redirect ) ) {
        wp_redirect( $custom_redirect . $session_query_string );
        exit();
    }

    wp_redirect( get_site_url() . $session_query_string );

    exit();

}, 10, 1 );