<?php

use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class DoxAuth_Provider extends League\OAuth2\Client\Provider\AbstractProvider {
	
	const BASE_URL = 'https://www.doximity.com';
	const BASE_AUTH_URL = 'https://auth.doximity.com';
	
	public function getBaseAuthorizationUrl() {
		
		return static::BASE_AUTH_URL . '/oauth/authorize';
		
	}
	
	
	public function getBaseAccessTokenUrl(array $params) {
		
		return static::BASE_AUTH_URL . '/oauth/token';
		
	}
	
	public function getResourceOwnerDetailsUrl(AccessToken $token) {
		
		$this->getCurrentUserInfoUrl();
		
	}
	
	public function getCurrentUserInfoUrl() {
		
		return static::BASE_URL . '/api/v1/users/current';
		
	}
	
	public function getCurrentUserInfo( $token ) {
		
		$headers = $this->getAuthorizationheaders( $token );
		
		$response = $this->getHttpClient()->request('GET', $this->getCurrentUserInfoUrl(), [ 'headers' => $headers, 'debug' => false ] );
		
		$body = $response->getBody();
		
		echo $body;
		
		return $response;
	}
	
	protected function getAuthorizationHeaders($token = null) {
		
		if( is_null( $token ) ) { return array(); }
		
		return array(
			'Authorization' => 'Bearer ' . $token,
			'Cache-Control' => 'no-cache'
		);
		
	}
	
	protected function getDefaultScopes() {
		
		return array( 'basic' );
		
	}
	
	protected function checkResponse(ResponseInterface $response, $data) {
		
		if( $response->getStatusCode() < 400 ) { return; }
		
		throw new IdentityProviderException(
			$response->getReasonPhrase(),
			$response->getStatusCode(),
			$response
		);
		
	}
	
	protected function createResourceOwner(array $response, AccessToken $token) {

	    //
		
	}
	
}