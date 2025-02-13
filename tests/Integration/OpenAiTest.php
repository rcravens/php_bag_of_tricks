<?php
//
//use App\Code\Ai\Agents\TranslatorAgent;
//use App\Code\Ai\LlmHelper;
//use App\Code\Ai\PromptBuilder;
//
//test( 'llm helper to generate response.', function () {
//    $prompt = new PromptBuilder();
//    $prompt->add_system_message( 'You will answer the following question as if your are a technology expert.' );
//    $prompt->add_user_message( 'What are the top five back-end languages for web development? What are the top 5 front-end frameworks?' );
//
//    $llm_helper = new LLMHelper();
//    $response   = $llm_helper->get_prompt_response( $prompt );
//
//    expect( $response )->not->toBeNull();
//} );
//
//test( 'expect translator agent to translate texts', function () {
//    $texts = [
//        'Hello, what is your name?',
//        'My name is Bob.',
//        'Goodbye, enjoy the rest of your day.',
//    ];
//
//    $from_language = 'en';
//
//    $supported_languages = array_keys( config( 'languages' ) );
//
//    $translations = [];
//    foreach ( $supported_languages as $to_language )
//    {
//        $translation_agent            = new TranslatorAgent( $texts, $from_language, $to_language );
//        $translations[ $to_language ] = $translation_agent->translate();
//    }
//
//    $expected_count = count( $texts );
//
//    expect( count( $translations ) )->toBe( count( $supported_languages ) );
//    foreach ( $supported_languages as $to_language )
//    {
//        expect( $translations )->toHaveKey( $to_language );
//        $this_language_translations = $translations[ $to_language ];
//        $count_translated           = count( $this_language_translations );
//        expect( $count_translated )->toBe( $expected_count );
//        foreach ( $texts as $text )
//        {
//            expect( $translations[ $to_language ] )->toHaveKey( $text );
//        }
//    }
//} );
