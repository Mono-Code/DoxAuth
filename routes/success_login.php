<?php
add_action( 'doxauth_success_login', function( $session_info ){

    if( isset( $session_info['doximity_redirect'] ) && !empty( $session_info['doximity_redirect'] ) ) {
        wp_redirect( $session_info['doximity_redirect'] );
        exit;
    }

    $custom_redirect = get_option( 'doxauth_success_login' );
    if( !empty( $custom_redirect ) ) {
        wp_redirect( $custom_redirect );
        exit;
    }

    wp_redirect('/');

    exit();

}, 10, 1 );