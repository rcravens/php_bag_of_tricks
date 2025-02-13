<?php

namespace Cravens\Php\Integrations\Stripe;

use Cravens\Php\Utilities\GenericResult;
use Exception;
use Stripe\StripeClient;

class StripeApi
{
	private string       $secret_api_key;
	private StripeClient $client;

	public function __construct( string $secret_api_key )
	{
		$this->secret_api_key = $secret_api_key;

		$this->client = new StripeClient( $this->secret_api_key );
	}

	public function create_customer( string $email, string $full_name, string $description, array $meta_data = [] ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			$customer     = $this->client->customers->create( [
				                                                  'email'       => $email,
				                                                  'name'        => $full_name,
				                                                  'description' => $description,
				                                                  'metadata'    => $meta_data,
			                                                  ] );
			$result->data = $customer;
		}
		catch( \Exception $e )
		{
			$result->is_error = false;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function retrieve_customer( string $customer_id ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			$customer     = $this->client->customers->retrieve( $customer_id );
			$result->data = $customer;
		}
		catch( Exception $e )
		{
			$result->is_error = false;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function create_payment_intent_session( string $customer_id, string $success_url, string $failed_url ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			$url_join_char = parse_url( $success_url, PHP_URL_QUERY ) ? '&' : '?';
			$success_url   = $success_url . $url_join_char . 'session_id={CHECKOUT_SESSION_ID}';

			$session = $this->client->checkout->sessions->create( [
				                                                      'mode'        => 'setup',
				                                                      'currency'    => 'usd',
				                                                      'customer'    => $customer_id,
				                                                      'success_url' => $success_url,
				                                                      'cancel_url'  => $failed_url,
			                                                      ] );

			$result->data = $session;
		}
		catch( Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function get_payment_method_from_session( string $session_id ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			$session = $this->client->checkout->sessions->retrieve( $session_id, [] );

			$setup_intent_id = $session->setup_intent;
			$setup_intent    = $this->client->setupIntents->retrieve( $setup_intent_id, [] );

			$payment_method_id     = $setup_intent->payment_method;
			$stripe_payment_method = $this->client->paymentMethods->retrieve( $payment_method_id, [] );

			$result->data = $stripe_payment_method;
		}
		catch( Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function detach_payment_method( string $payment_method_id ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			$this->client->paymentMethods->detach( $payment_method_id );
		}
		catch( Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function create_payment_intent( int $amount_cents, string $currency, string $customer_id, string $description, string $payment_method_id, string $payment_method_type ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			$payment_intent = $this->client->paymentIntents->create( [
				                                                         'amount'               => $amount_cents,
				                                                         'currency'             => $currency,
				                                                         'customer'             => $customer_id,
				                                                         'description'          => $description,
				                                                         'payment_method'       => $payment_method_id,
				                                                         'payment_method_types' => [ $payment_method_type ],
				                                                         'confirm'              => true,
			                                                         ] );


			$result->data = $payment_intent;
		}
		catch( Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function create_refund( string $transaction_id ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			$args = [ 'payment_intent' => $transaction_id ];

			$refund       = $this->client->refunds->create( $args );
			$result->data = $refund;

			if ( $refund->status != 'succeeded' )
			{
				$result->is_error = true;
				$result->error    = $refund->status;
			}
		}
		catch( Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}
}
