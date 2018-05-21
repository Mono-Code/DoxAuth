<?php

class DoxAuth_Wrapper {
	
	protected $authenticator = 'doximity';
	protected $provider;
	protected $clientId;
	protected $clientSecret;
	protected $redirectUri;
	protected $accessToken;
	
	public function __construct() {
		
		// Collect data from DB
		$this->clientId = get_option( $this->authenticator . '_id');
		$this->clientSecret = get_option( $this->authenticator . '_secret' );
		$this->redirectUri = get_option( $this->authenticator . '_redirect' );
		
		// Ensure provider necessary arguments are specified before applying a provider
		if( empty( $this->clientId ) ||
		    empty( $this->clientSecret ) ||
		    empty( $this->redirectUri ) ) {
			$this->terminate_process( 'requires_settings' );
		}
		
		// Assign provider
		$this->provider = new DoxAuth_Provider( array(
			'clientId'      => $this->clientId,
			'clientSecret'  => $this->clientSecret,
			'redirectUri'   => $this->redirectUri
		) );
		
		// Use $_SESSION to persist required registration information.
		session_start();
		
	}
	
	public function route_request() {
		
		$type = $this->get_type();
		
		$this->get_redirect();
		
		switch( $type ) {
			case 'register':
				
				$this->process_registration_request();
				
				break;
				
			case 'verify':
				
				$this->process_verification_request();
				
				break;
				
			case 'login':
				
				$this->process_login_request();
				
				break;
			
			case 'unlink':
				
				$this->process_unlink_request();
				
				break;
				
			default:
				
				$this->terminate_process( 'requires_type' );
				
				break;
		}
		
	}
	
	protected function process_registration_request() {
		
		if( is_user_logged_in() ) {
			
			$this->terminate_process( 'already_logged_in' );
			
		}
		
		// If No Code is specified, get it.
		if( ! isset( $_GET[ 'code' ] ) ) {
			
			$this->request_access_from_third_party();
			
		}
		
		// Output Error if State is not provided or does not verify
		if( empty( $_GET[ 'state' ] ) ||
		    ( isset( $_SESSION[ 'doximity_oauth2state' ] ) && $_GET[ 'doximity_state' ] !== $_SESSION[ 'doximity_oauth2state' ] ) ) {
			
			$this->terminate_process( 'invalid_state' );
			
		}
		
		// Request Tokens, then request resource.
		$profile = $this->access_profile();
		
		if( !is_array( $profile ) ||
		    !isset( $profile['id'] ) ||
		    $this->account_relationship_exists( $profile['id'] ) ) {
			
			$this->terminate_process( 'relationship_exists' );
			
		}
		
		$_SESSION['doximity_query_string'] = http_build_query( $profile );
		
		$this->terminate_process('success_registration' );
		
	}
	
	protected function process_verification_request() {
		
		if( !is_user_logged_in() ) {
			
			$this->terminate_process( 'requires_login' );
			
		}
		
		// If No Code is specified, get it.
		if( ! isset( $_GET[ 'code' ] ) ) {
			
			$this->request_access_from_third_party();
			
		}
		
		// Output Error if State is not provided or does not verify
		if( empty( $_GET[ 'state' ] ) ||
		    ( isset( $_SESSION[ 'doximity_oauth2state' ] ) && $_GET[ 'doximity_state' ] !== $_SESSION[ 'doximity_oauth2state' ] ) ) {
			
			$this->terminate_process( 'invalid_state' );
			
		}
		
		// Request Tokens, then request resource.
		$profile = $this->access_profile();
		
		if( !is_array( $profile ) ||
		    !isset( $profile['id'] ) ||
		    $this->account_relationship_exists( $profile['id'] ) ) {
			
			$this->terminate_process( 'relationship_exists' );
			
		}
		
		$this->build_relationship( $profile['id'] );
		
		$this->terminate_process('success_verification' );
		
	}
	
	protected function process_login_request() {
		
		if( is_user_logged_in() ) {
			
			$this->terminate_process( 'already_logged_in' );
			
		}
		
		// If No Code is specified, get it.
		if( ! isset( $_GET[ 'code' ] ) ) {
			
			$this->request_access_from_third_party();
			
		}
		
		// Output Error if State is not provided or does not verify
		if( empty( $_GET[ 'state' ] ) ||
		    ( isset( $_SESSION[ 'doximity_oauth2state' ] ) && $_GET[ 'doximity_state' ] !== $_SESSION[ 'doximity_oauth2state' ] ) ) {
			
			$this->terminate_process( 'invalid_state' );
			
		}
		
		// Request Tokens, then request resource.
		$profile = $this->access_profile();
		
		if( !is_array( $profile ) ||
		    !isset( $profile['id'] ) ||
		    !$this->account_relationship_exists( $profile['id'] ) ) {
			
			$this->terminate_process( 'requires_relationship' );
			
		}
		
		$this->login_user( $profile['id'] );
		
		$this->terminate_process('success_login' );
		
	}
	
	protected function process_unlink_request() {
		
		if( !is_user_logged_in() ) {
			
			$this->terminate_process( 'requires_login' );
			
		}
		
		// check if Doximity Account ID exists in current user meta
		if(!empty(get_user_meta(get_current_user_id(), 'doximity_account_id'))) {
			delete_user_meta(get_current_user_id(), 'doximity_account_id');
		} else {
			$this->terminate_process( 'requires_relationship' );
		}
		
		$this->terminate_process('success_removal' );
		
	}
	
	public function account_relationship_exists( $account_id ) {
		
		$account_holders = $this->get_account_holder( $account_id );
		
		if( empty( $account_holders ) ) {
			return false;
		}
		
		return true;
		
	}
	
	protected function get_account_holder( $account_id ) {
		
		return get_users(
			
			array(
				'meta_key' => 'doximity_account_id',
				'meta_value' => $account_id,
				'meta_compare' => '='
			)
			
		);
		
	}
	
	protected function login_user( $account_id ){
		
		$users = $this->get_account_holder( $account_id );
		
		if( 0 === count( $users ) ) { $this->terminate_process( 'requires_relationship'); }
		
		if( 1 !== count( $users ) ) { $this->terminate_process( 'relationship_multiple'); }
		
		$user_id = $users[0]->ID;
		
		wp_set_auth_cookie( $user_id, true );
		
	}
	
	protected function build_relationship( $account_id ) {
		
		$user_id = get_current_user_id();
		
		if( 0 === $user_id ) {
			$this->terminate_process( 'requires_login' );
		}
		
		update_user_meta( $user_id, 'doximity_account_id', $account_id );
		
	}
	
	public function registration_entry( $user_id, $account_id, $NPINumber=null) {
	
		if( $this->account_relationship_exists( $account_id ) ) { return; }
		
		update_user_meta( $user_id, 'doximity_account_id', $account_id );
	
	}
	
	protected function request_access_from_third_party() {
		
		$authorizationUrl = $this->provider->getAuthorizationUrl( array(
			'type'  =>  'login'
		) );
		
		$_SESSION['oauth2state'] = $this->provider->getState();
		
		header('Location: ' . $authorizationUrl );
		
		exit;
		
	}
	
	protected function get_type() {
		
		if( isset( $_GET['doximity_type'] ) ) {
			
			$_SESSION['doximity_type'] = sanitize_text_field( $_GET['doximity_type'] );
			
		}
		
		if( !isset( $_SESSION['doximity_type'] ) ) {
			
			$this->terminate_process( 'requires_type' );
			
		}
		
		return $_SESSION['doximity_type'];
		
	}
	
	protected function get_redirect() {
		
		if( isset( $_GET['doximity_redirect'] ) ) {
			
			$_SESSION['doximity_redirect'] = sanitize_text_field( $_GET['doximity_redirect'] );
			
		}
		
		if( !isset( $_SESSION['doximity_redirect'] ) ) {
			
			$_SESSION['doximity_redirect'] = get_site_url();
			
		}
		
		return $_SESSION['doximity_redirect'];
		
	}
	
	protected function access_profile() {
		
		try {
			
			// Try to get an access token using the authorization code grant.
			$this->accessToken = $this->provider->getAccessToken('authorization_code', [
				'code' => $_GET['code']
			]);
			
			// The provider provides a way to get an authenticated API request for
			// the service, using the access token; it returns an object conforming
			// to Psr\Http\Message\RequestInterface.
			$request = $this->provider->getAuthenticatedRequest(
				'GET',
				$this->provider->getCurrentUserInfoUrl(),
				$this->accessToken->getToken()
			);
			
			return $this->provider->getParsedResponse( $request );
			
		} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			
			// Failed to get the access token or user details.
			$this->terminate_process( 'provider_issue' );
			
		}
		
		exit();
		
	}
	
	protected function terminate_process( $reason ) {
		
		$session_info = $_SESSION;
		
		if ( isset( $_SESSION['doximity_oauth2state'] ) ) {
			
			unset( $_SESSION['doximity_oauth2state'] );
			
		}
		
		if ( isset( $_SESSION['doximity_type'] ) ) {
			
			unset( $_SESSION['doximity_type'] );
			
		}
		
		if ( isset( $_SESSION['doximity_redirect'] ) ) {
			
			unset( $_SESSION['doximity_redirect'] );
			
		}
		
		if ( isset( $_SESSION['doximity_query_string'] ) ) {
			
			unset( $_SESSION['doximity_query_string'] );
			
		}
		
		session_abort();
		
		do_action( 'doxauth_' . $reason, $session_info );
		
		exit();
	}
}