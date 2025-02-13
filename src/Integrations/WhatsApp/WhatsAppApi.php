<?php

namespace App\Code\Integrations\WhatsApp;

use Cravens\Php\Utilities\GenericResult;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class WhatsAppApi
{
	protected string $api_url;
	protected string $api_token;

	public function __construct( string $api_url, string $api_token )
	{
		$this->api_url   = $api_url;
		$this->api_token = $api_token;
	}

	public function send_message( $to, $message ): GenericResult
	{
		$args = [
			'messaging_product' => 'WHATSAPP',
			'recipient_type'    => 'individual',
			'to'                => $to,
			'type'              => 'text',
			'text'              => [
				'body' => $message,
			],
		];

		$url = "$this->api_url/messages";

		return $this->http_post( $url, $args );
	}

	public function send_template( $to, $template_name ): GenericResult
	{
		$args = [
			'messaging_product' => 'WHATSAPP',
			'recipient_type'    => 'individual',
			'to'                => $to,
			'type'              => 'template',
			'template'          => [
				'name'     => $template_name,
				'language' => [
					'code' => 'en_US'
				]
			],
		];

		$url = "$this->api_url/messages";

		return $this->http_post( $url, $args );
	}

	public function test(): GenericResult
	{
		$args = [
			'cc'           => '1',
			'phone_number' => '6083201824',
			'method'       => 'sms',
			'cert'         => 'CmUKIQj3v5u8o9vfAhIGZW50OndhIghEaWcgTGFic1Cg9ce6BhpAB4nD8KICpOCUlU87oHP2qmRuhiDudaInQVSqD931nFcID2t/hjz762ZE87KlP4V9rGuzp0EE3tphyFRjGy/rDRItbTRw98vrl0vgQ4+0mKRuK5Vb7OdY7WDogXaHaI3zi2milaidtMQh3/fVqlRl',
		];

		$url = 'https://graph.facebook.com/v1/account';

		return $this->http_post( $url, $args );
	}

	private function http_post( $url, $args ): GenericResult
	{
		try
		{
			$client = new Client();

			$response = $client->post( $url, [
				'headers' => [
					'Authorization' => "Bearer {$this->api_token}",
					'Accept'        => 'application/json',
				],
				'json'    => $args, // Send the payload as JSON
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
