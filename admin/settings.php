<?php
function doxauth_register_credentials(){
	register_setting( 'doximity_settings', 'doximity_id' );
	register_setting( 'doximity_settings', 'doximity_secret' );
	register_setting( 'doximity_settings', 'doximity_redirect' );
	register_setting( 'doximity_settings', 'doximity_active' );
	
	register_setting( 'doximity_settings', 'doxauth_already_logged_in' );
	register_setting( 'doximity_settings', 'doxauth_invalid_state' );
	register_setting( 'doximity_settings', 'doxauth_provider_issue' );
	register_setting( 'doximity_settings', 'doxauth_relationship_exists' );
	register_setting( 'doximity_settings', 'doxauth_relationship_multiple' );
	register_setting( 'doximity_settings', 'doxauth_requires_login' );
	register_setting( 'doximity_settings', 'doxauth_requires_relationship' );
	register_setting( 'doximity_settings', 'doxauth_requires_settings' );
	register_setting( 'doximity_settings', 'doxauth_requires_type' );
	register_setting( 'doximity_settings', 'doxauth_success_login' );
	register_setting( 'doximity_settings', 'doxauth_success_registration' );
	register_setting( 'doximity_settings', 'doxauth_success_verification' );
	register_setting( 'doximity_settings', 'doxauth_general_redirect' );
}