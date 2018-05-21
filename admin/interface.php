<?php
add_action( 'admin_menu', function() {
	// Define Submenu
	add_options_page( 'Doximity Credentials', 'Doximity&reg; Authentication', 'manage_options', 'doxauth_credentials', 'doxauth_manage_credentials' );
	
	// Ensure all settings are registered
	add_action( 'admin_init', 'doxauth_register_credentials');
} );

add_action( 'admin_post_doxauth_process_form', function() {

	// Verify User Creds
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	// Check Nonce
	if(!isset($_POST['_wpnonce'])) { exit; }
	if(!wp_verify_nonce($_POST['_wpnonce'], 'doxauth_process_form')) { exit;}
	
	// Sanitize and persist settings if entered.
	if(isset($_REQUEST['doximity_id'])) {
		update_option('doximity_id', sanitize_text_field($_REQUEST['doximity_id']));
	}
	
	if(isset($_REQUEST['doximity_secret'])) {
		update_option('doximity_secret', sanitize_text_field($_REQUEST['doximity_secret']));
	}
	
	if(isset($_REQUEST['doximity_redirect'])) {
		update_option('doximity_redirect', sanitize_text_field($_REQUEST['doximity_redirect']));
	}
	
	if(isset($_REQUEST['doximity_active']) && "true" === $_REQUEST['doximity_active'] ) {
        update_option('doximity_active', true);
    } else {
        update_option('doximity_active', false);
    }
  
    // Redirects
	if(isset($_REQUEST['doxauth_already_logged_in'])) {
		update_option('doxauth_already_logged_in', sanitize_text_field($_REQUEST['doxauth_already_logged_in']));
	}
	
	if(isset($_REQUEST['doxauth_invalid_state'])) {
		update_option('doxauth_invalid_state', sanitize_text_field($_REQUEST['doxauth_invalid_state']));
	}
	
	if(isset($_REQUEST['doxauth_provider_issue'])) {
		update_option('doxauth_provider_issue', sanitize_text_field($_REQUEST['doxauth_provider_issue']));
	}
	
	if(isset($_REQUEST['doxauth_relationship_exists'])) {
		update_option('doxauth_relationship_exists', sanitize_text_field($_REQUEST['doxauth_relationship_exists']));
	}
	
	if(isset($_REQUEST['doxauth_relationship_multiple'])) {
		update_option('doxauth_relationship_multiple', sanitize_text_field($_REQUEST['doxauth_relationship_multiple']));
	}
	
	if(isset($_REQUEST['doxauth_general_redirect'])) {
		update_option('doxauth_general_redirect', sanitize_text_field($_REQUEST['doxauth_general_redirect']));
	}
	
	if(isset($_REQUEST['doxauth_requires_login'])) {
		update_option('doxauth_requires_login', sanitize_text_field($_REQUEST['doxauth_requires_login']));
	}

	if(isset($_REQUEST['doxauth_requires_relationship'])) {
		update_option('doxauth_requires_relationship', sanitize_text_field($_REQUEST['doxauth_requires_relationship']));
	}
	
	if(isset($_REQUEST['doxauth_requires_settings'])) {
		update_option('doxauth_requires_settings', sanitize_text_field($_REQUEST['doxauth_requires_settings']));
	}
	
	if(isset($_REQUEST['doxauth_requires_type'])) {
		update_option('doxauth_requires_type', sanitize_text_field($_REQUEST['doxauth_requires_type']));
	}
	
	if(isset($_REQUEST['doxauth_success_login'])) {
		update_option('doxauth_success_login', sanitize_text_field($_REQUEST['doxauth_success_login']));
	}
	
	if(isset($_REQUEST['doxauth_success_registration'])) {
		update_option('doxauth_success_registration', sanitize_text_field($_REQUEST['doxauth_success_registration']));
	}
	
	if(isset($_REQUEST['doxauth_success_verification'])) {
		update_option('doxauth_success_verification', sanitize_text_field($_REQUEST['doxauth_success_verification']));
	}
	
	if(isset($_REQUEST['doxauth_general_redirect'])) {
		update_option('doxauth_general_redirect', sanitize_text_field($_REQUEST['doxauth_general_redirect']));
	}
	
	wp_redirect( home_url( $_POST['_wp_http_referer'] ) );
	exit;
} );

function doxauth_manage_credentials() {
	
	// Check User Creds
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} ?>
	
	<div class="wrap">
        
		<h2>Doximity&reg; Credentials</h2>
        <p>Will be provided from Doximity<sup>&reg;</sup> staff.</p>
        
		<form method="post" action="/wp-admin/admin-post.php">
            
			<table class="form-table">
				
				<!-- Modifiable Credentials -->
				<tr valign="top">
                         
                    <th scope="row">
                        <label for="doximity_id">Client ID:</label>
                    </th>
          
					<td>
						<input
                            title="Client ID provided by Doximity, inc."
                            type="text"
                            id="doximity_id"
                            name="doximity_id"
                            value="<?= get_option('doximity_id'); ?>" />
					</td>
     
				</tr>
				
				<tr valign="top">
     
                    <th scope="row">
                        <label for="doximity_secret">Client Secret:</label>
                    </th>
          
                    <td>
                        <input
                        title="Client Secret provided by Doximity, inc."
                        type="text"
                        name="doximity_secret"
                        value="<?= get_option('doximity_secret'); ?>" />
                    </td>
     
				</tr>

                <tr valign="top">
                
                    <th scope="row">
                        <label for="doximity_redirect">Redirect URL:</label>
                    </th>
                    
                    <td>
                        <input
                            title="Redirect URL."
                            type="text"
                            name="doximity_redirect"
                            value="<?= get_option('doximity_redirect'); ?>" />
                    </td>
                
                </tr>
				
				<tr valign="top">
     
                    <th scope="row">
                        <label for="doximity_active">Allow Login Functionality:</label>
                    </th>
          
					<td>
						<input
                            title="Enable Doximity as Authentication Service"
                            type="checkbox"
                            value="true"
                            name="doximity_active"
                            <?= ( boolval( get_option('doximity_active' ) ) ) ? "checked" : ""; ?> />
					</td>
     
				</tr>
				
			</table>
      
            <h3>Error Redirect Pages</h3>

            <table class="form-table">
            
                <tr valign="top">
                  <th scope="row">Already Logged In:</th>
                  <td>
                    <input type="text" name="doxauth_already_logged_in" value="<?= get_option( 'doxauth_already_logged_in' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Invalid State:</th>
                  <td>
                    <input type="text" name="doxauth_invalid_state" value="<?= get_option( 'doxauth_invalid_state' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Doximity Response Issue:</th>
                  <td>
                    <input type="text" name="doxauth_provider_issue" value="<?= get_option( 'doxauth_provider_issue' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Relationship Already Exists:</th>
                  <td>
                    <input type="text" name="doxauth_relationship_exists" value="<?= get_option( 'doxauth_relationship_exists' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Multiple Relationships Exist:</th>
                  <td>
                    <input type="text" name="doxauth_relationship_multiple" value="<?= get_option( 'doxauth_relationship_multiple' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Requires Login:</th>
                  <td>
                    <input type="text" name="doxauth_requires_login" value="<?= get_option( 'doxauth_requires_login' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Requires Relationship:</th>
                  <td>
                    <input type="text" name="doxauth_requires_relationship" value="<?= get_option( 'doxauth_requires_relationship' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Requires Settings:</th>
                  <td>
                    <input type="text" name="doxauth_requires_settings" value="<?= get_option( 'doxauth_requires_settings' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Requires Type:</th>
                  <td>
                    <input type="text" name="doxauth_requires_type" value="<?= get_option( 'doxauth_requires_type' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Successful Login:</th>
                  <td>
                    <input type="text" name="doxauth_success_login" value="<?= get_option( 'doxauth_success_login' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Successful Registration:</th>
                  <td>
                    <input type="text" name="doxauth_success_registration" value="<?= get_option( 'doxauth_success_registration' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Successful Verification:</th>
                  <td>
                    <input type="text" name="doxauth_success_verification" value="<?= get_option( 'doxauth_success_verification' ); ?>" />
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">General Error:</th>
                  <td>
                    <input type="text" name="doxauth_general_redirect" value="<?= get_option( 'doxauth_general_redirect' ); ?>" />
                  </td>
                </tr>
            
            </table>
      
            <?php wp_nonce_field( 'doxauth_process_form' ); ?>
            
            <input
                type="hidden"
                name="action"
                value="doxauth_process_form" />
            
            <input
                type="submit"
                class="button-primary"
                value="<?php _e( 'Update Credentials' ) ?>" />
      
		</form>
	</div>
	<?php
    
}