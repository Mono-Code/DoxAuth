<?php
/**
 * Plugin Name: Authentication Services
 * Plugin URI:  http://www.openminds.com
 * Description: Utilize Doximity's OAuth (2.0) as an authentication service.
 * Version:     1.0.0
 * Author:      Matthew Morrison
 * Author URI:  https://monocode.com
 * License:     GPLv2+
 * Text Domain: doxauth_
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'DOXAUTH_URL',     plugin_dir_url( __FILE__ ) );
define( 'DOXAUTH_PATH',    dirname( __FILE__ ) . '/' );

// -- Include Packages
require_once( DOXAUTH_PATH. 'vendor/autoload.php' );

// -- FUNCTIONALITY
    // Provider
    require_once(DOXAUTH_PATH . 'classes/DoxAuth_Provider.php');

    // Auth2.0 Doximity Provider Wrapper
    require_once(DOXAUTH_PATH . 'classes/DoxAuth_Wrapper.php');

// -- CMS ADMINISTRATIVE PAGES
    // Administrative Pages
    require_once(DOXAUTH_PATH . 'admin/interface.php');

    // Credentials & Settings
    require_once(DOXAUTH_PATH . 'admin/settings.php');

// -- Routes
    require_once(DOXAUTH_PATH . 'routes/already_logged_in.php');
    require_once(DOXAUTH_PATH . 'routes/invalid_state.php');
    require_once(DOXAUTH_PATH . 'routes/provider_issue.php');
    require_once(DOXAUTH_PATH . 'routes/relationship_exists.php');
    require_once(DOXAUTH_PATH . 'routes/relationship_multiple.php');
    require_once(DOXAUTH_PATH . 'routes/requires_login.php');
    require_once(DOXAUTH_PATH . 'routes/requires_relationship.php');
    require_once(DOXAUTH_PATH . 'routes/requires_settings.php');
    require_once(DOXAUTH_PATH . 'routes/requires_type.php');
    require_once(DOXAUTH_PATH . 'routes/success_login.php');
    require_once(DOXAUTH_PATH . 'routes/success_login.php');
    require_once(DOXAUTH_PATH . 'routes/success_registration.php');
    require_once(DOXAUTH_PATH . 'routes/success_verification.php');
    require_once(DOXAUTH_PATH . 'routes/success_removal.php');