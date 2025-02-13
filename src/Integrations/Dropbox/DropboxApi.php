<?php

namespace Cravens\Php\Integrations\Dropbox;

use Cravens\Php\Utilities\GenericResult;
use GuzzleHttp\Client;

class DropboxApi
{
	private string $app_key;
	private string $app_secret;

	public function __construct( string $app_key, string $app_secret )
	{
		$this->app_key    = $app_key;
		$this->app_secret = $app_secret;
	}

	public function get_refresh_token( string $oauth_token ): GenericResult
	{
		// Before calling this:
		// https://www.dropbox.com/oauth2/authorize?client_id=<APP_KEY>&token_access_type=offline&response_type=code
		// go through the manual process and get a new o-auth-token that you can use below
		// These can only be used once,

		try
		{
			$client   = new Client();
			$response = $client->post( 'https://api.dropboxapi.com/oauth2/token', [
				'auth'        => [ $this->app_key, $this->app_secret ], // Basic Auth
				'form_params' => [
					'code'       => $oauth_token,
					'grant_type' => 'authorization_code',
				],
			] );

			$data = json_decode( $response->getBody(), false );

			return GenericResult::data( $data );
		}
		catch( \Exception $e )
		{
			return GenericResult::error( $e->getMessage() );
		}
	}

	public function get_access_token( string $refresh_token ): GenericResult
	{
		try
		{
			$client   = new Client();
			$response = $client->post( 'https://api.dropbox.com/oauth2/token', [
				'form_params' => [
					'refresh_token' => $refresh_token,
					'client_secret' => $this->app_secret,
					'client_id'     => $this->app_key,
					'grant_type'    => 'refresh_token',
				],
			] );

			$data = json_decode( $response->getBody(), false );

			return GenericResult::data( $data );
		}
		catch( \Exception $e )
		{
			return GenericResult::error( $e->getMessage() );
		}
	}
}
