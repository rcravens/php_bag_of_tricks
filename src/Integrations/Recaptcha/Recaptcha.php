<?php

namespace Cravens\Php\Integrations\Recaptcha;

class Recaptcha
{
	public function inspect( string $secret_key ): bool
	{
		$recaptcha_response = $_POST[ 'g-recaptcha-response' ] ?? null;
		if ( is_null( $recaptcha_response ) )
		{
			return false;
		}

		$verifyResponse = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $recaptcha_response );
		$responseData   = json_decode( $verifyResponse );

		if ( ! $responseData->success )
		{
			return false;
		}

		return true;
	}
}
