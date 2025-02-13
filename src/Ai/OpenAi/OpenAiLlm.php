<?php

namespace Cravens\Php\Ai\OpenAi;

use Carbon\Carbon;
use Cravens\Php\Ai\LlmInterface;
use Cravens\Php\Ai\PromptBuilder;
use Cravens\Php\Traits\CachableTrait;
use OpenAI;

class OpenAiLlm implements LlmInterface
{
	use CachableTrait;

	private string $api_key;

	public function __construct( string $open_ai_api_key )
	{
		$this->api_key = $open_ai_api_key;
	}

	public function get_response( PromptBuilder $prompt ): string
	{
		$key      = md5( serialize( $prompt->messages() ) );
		$response = self::cache_get_value( $key );

		if ( is_null( $response ) )
		{
			$client     = OpenAI::client( $this->api_key );
			$chat_reply = $client->chat()->create( [
				                                       'model'    => 'gpt-3.5-turbo',
				                                       'messages' => $prompt->messages(),
			                                       ] );

			$response = $chat_reply->choices[ 0 ]->message->content;

			self::cache_set_value( $key, $response, Carbon::now()->addMinutes( 60 ) );
		}

		return $response;
	}
}
