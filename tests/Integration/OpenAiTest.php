<?php

use Cravens\Php\Ai\Agents\TranslatorAgent;
use Cravens\Php\Ai\LlmFactory;
use Cravens\Php\Ai\PromptBuilder;

test( 'llm helper to generate response.', function () {
	$prompt = new PromptBuilder();
	$prompt->add_system_message( 'You will answer the following question as if your are a technology expert.' );
	$prompt->add_user_message( 'What are the top five back-end languages for web development? What are the top 5 front-end frameworks?' );

	$api_key = $_ENV[ 'OPENAI_API_KEY' ];
	$llm     = LlmFactory::create( $api_key );

	$response = $llm->get_response( $prompt );

	expect( $response )->not->toBeNull();
} )->skip();

test( 'expect translator agent to translate texts', function () {
	$texts = [
		'Hello, what is your name?',
		'My name is Bob.',
		'Goodbye, enjoy the rest of your day.',
	];

	$from_language = 'en';

	$languages = [
		'en' => 'English',
		'pt' => 'Português',
		'es' => 'Español',
		'fr' => 'Français',
	];

	$supported_languages = array_keys( $languages );

	$api_key = $_ENV[ 'OPENAI_API_KEY' ];
	$llm     = LlmFactory::create( $api_key );

	$translations = [];
	foreach ( $supported_languages as $to_language )
	{
		$translation_agent            = new TranslatorAgent( $texts, $from_language, $to_language );
		$translations[ $to_language ] = $translation_agent->translate( $llm );
	}

	$expected_count = count( $texts );

	expect( count( $translations ) )->toBe( count( $supported_languages ) );
	foreach ( $supported_languages as $to_language )
	{
		expect( $translations )->toHaveKey( $to_language );
		$this_language_translations = $translations[ $to_language ];
		$count_translated           = count( $this_language_translations );
		expect( $count_translated )->toBe( $expected_count );
		foreach ( $texts as $text )
		{
			expect( $translations[ $to_language ] )->toHaveKey( $text );
		}
	}
} )->skip();
