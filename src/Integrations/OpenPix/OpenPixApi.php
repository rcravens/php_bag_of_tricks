<?php

namespace Cravens\Php\Integrations\OpenPix;

use Cravens\Php\Utilities\GenericResult;
use GuzzleHttp\Client;

class OpenPixApi
{
	/*
	 * Sources:
	 * - https://developers.openpix.com.br/en/docs/charge/how-to-create-charge-using-api
	 * - https://developers.openpix.com.br/docs/charge/refund/charge-refund-create-api
	 * - https://openpix.com.br/en/articles/webhook-para-cobrancas-e-pagamentos-com-a-woovi-tudo-o-que-voce-precisa-saber/
	 * - https://developers.openpix.com.br/en/docs/webhook/api/webhook-api
	 */
	private        $client;
	private string $client_id;
	private string $api_key;
	private string $app_id;

	public function __construct( string $client_id, string $api_key, string $app_id )
	{
		$this->client_id = $client_id;
		$this->api_key   = $api_key;
		$this->app_id    = $app_id;

		$this->client = new Client( [
			                            'base_uri' => 'https://api.openpix.com.br',
			                            'headers'  => [
				                            'Authorization' => "{$this->app_id}",
				                            'Content-Type'  => 'application/json',
			                            ]
		                            ] );
	}

	public function create_charge( string $correlation_id, int $amount_cents, string $comment ): GenericResult
	{
		$payload = [
			'correlationID' => $correlation_id,
			'value'         => $amount_cents,
			'comment'       => $comment,
		];

		return $this->send_request( 'api/v1/charge?return_existing=true', $payload );
	}

	public function create_refund( string $correlation_id, int $value, ?string $comment = null ): GenericResult
	{
		$payload = [
			'correlationID' => $correlation_id,
			'value'         => $value,
		];

		if ( $comment )
		{
			$payload[ 'comment' ] = $comment;
		}

		return $this->send_request( 'api/v1/charge' . $correlation_id . '/refund', $payload );
	}

	public function transfer_funds( int $amount_cents, string $destination_pix_key, string $description = '' ): GenericResult
	{
		$payload = [
			'value'       => $amount_cents,
			'pixKey'      => $destination_pix_key,
			'description' => $description,
		];

		return $this->send_request( 'api/v1/transfer', $payload );
	}

	private function send_request( string $endpoint, array $payload ): GenericResult
	{
		try
		{
			$response = $this->client->post( $endpoint, [
				'json' => $payload,
			] );

			if ( $response->getStatusCode() != 200 )
			{
				return GenericResult::error( $response->getStatusCode() );
			}

			$data = json_decode( $response->getBody(), false );

			return GenericResult::data( $data );
		}
		catch( \Exception $e )
		{
			return GenericResult::error( $e->getMessage() );
		}
	}
}
