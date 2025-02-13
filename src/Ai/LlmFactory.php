<?php

namespace Cravens\Php\Ai;

use Cravens\Php\Ai\OpenAi\OpenAiLlm;

class LlmFactory
{
	public static function create( string $api_key ): LlmInterface
	{
		return new OpenAiLlm( $api_key );
	}
}
