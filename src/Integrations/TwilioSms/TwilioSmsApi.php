<?php

namespace Cravens\Php\Integrations\TwilioSms;

use Cravens\Php\Utilities\GenericResult;
use Twilio\Rest\Client;

class TwilioSmsApi
{
	private string $account_sid;
	private string $auth_token;
	private string $twilio_number;

	public function __construct( string $account_sid, string $auth_token, string $twilio_number )
	{
		$this->account_sid   = $account_sid;
		$this->auth_token    = $auth_token;
		$this->twilio_number = $twilio_number;
	}

	public function send_message( $phone_number, $message ): GenericResult
	{
		$result = new GenericResult();

		$args = [
			'from' => $this->twilio_number,
			'body' => $message,
		];

		try
		{
			$client = new Client( $this->account_sid, $this->auth_token );

			$message_instance = $client->messages->create( $phone_number, $args );

			$result->data = $message_instance;
		}
		catch( \Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}
}
