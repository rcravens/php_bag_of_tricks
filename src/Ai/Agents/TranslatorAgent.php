<?php

namespace Cravens\Php\Ai\Agents;

use Cravens\Php\Ai\LlmInterface;

class TranslatorAgent extends AgentBase
{
	private const string  ROLE = <<<ROLE
Language Translator
ROLE;

	private const string  BACKGROUND = <<<BACKGROUND
You are an expert translating english to another language.
BACKGROUND;

	private const string  GOAL = <<<GOAL
Follow the instructions provided and create translation.
GOAL;

	private const ?string INSTRUCTIONS = <<<INSTRUCTIONS
You have developed the following process that generates the best possible results:
1. You will be provided the language code of the language you are translating from in a variable called 'from_language'.
2. You will be provided a list of phrases in the from_language in the variable 'english_texts'. Each phrase will be on a new line. For example:
This is phrase to be translated.
This ia another phrase to be translated.
3. You will be provided the language code of the language you are translating to in a variable called 'to_language'.
4. For each phrase, create a translation from 'from_language' to 'to_language'.
INSTRUCTIONS;

	private const ?string OUTPUT_FORMAT = <<<OUTPUT_FORMAT
Your output should be only the translated text.
You should format the output in JSON format.
Be sure to include a comma after each translated line of text except for the last line.
Here is an example of the desired output format:
{
    "This is phrase to be translated.": "This is the translated text.",
    "This ia another phrase to be translated.": "This is the translated text.",
}
OUTPUT_FORMAT;

	private const bool    IS_JSON = true;
	private array $context;

	public function __construct( array $english_texts, string $from_language, string $to_language )
	{
		$this->context                    = [];
		$this->context[ 'from_language' ] = $from_language;
		$this->context[ 'to_language' ]   = $to_language;
		$this->context[ 'english_text' ]  = implode( PHP_EOL, $english_texts );

		parent::__construct( self::ROLE, self::BACKGROUND, self::GOAL, $this->context, self::INSTRUCTIONS, self::OUTPUT_FORMAT, self::IS_JSON );
	}

	public function translate( LlmInterface $llm )
	{
		$translations = $this->work( $llm );

		// Fix up some common issues in the output formatting.
		//
		$fixed_translations = str_replace( ",\n}", "\n}", $translations );

		$data = json_decode( $fixed_translations, true );

//        if ( json_last_error() !== JSON_ERROR_NONE )
//        {
//            dd( $fixed_translations, $data, json_last_error_msg() );
//        }

		return $data;
	}
}
