<?php
add_action( 'doxauth_provider_issue', function( $session_info ){

    $custom_redirect = get_option( 'doxauth_provider_issue' );
    if( !empty( $custom_redirect ) ) {
        wp_redirect( $custom_redirect );
        exit;
    }

    $general_redirect = get_option( 'doxauth_general_redirect' );
    if( !empty( $general_redirect ) ) {
        wp_redirect( $general_redirect );
        exit;
    }

    wp_redirect('/');

    exit();

}, 10, 1 );