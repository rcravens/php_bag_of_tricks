<?php

namespace Cravens\Php\Ai;

interface LlmInterface
{
	public function __construct( string $open_ai_api_key );

	public function get_response( PromptBuilder $prompt ): string;
}