<?php

namespace Cravens\Php\Utilities;

class Translate
{
	public string $app_base_path;
	public string $default_language;
	public array  $default_translations;

	public function __construct( $app_base_path, $default_language = 'en' )
	{
		$this->app_base_path = rtrim( $app_base_path, DIRECTORY_SEPARATOR );

		$this->default_language = $default_language;

		$this->default_translations = $this->read_translations( $this->default_language );
	}

	public function translate( string $text = null ): string
	{
		if ( is_null( $text ) )
		{
			return $text;
		}

		if ( ! array_key_exists( $text, $this->default_translations ) )
		{
			$this->default_translations[ $text ] = $text;

			ksort( $this->default_translations );

			$this->update_translation_file( $this->default_language, $this->default_translations );
		}

		if ( function_exists( '__' ) && function_exists( 'app' ) )
		{
			return __( $text, [], app()->getLocale() );
		}

		return $text;
	}

	public function update_translation_file( string $language, array $translations ): void
	{
		$translation_file = $this->translation_file_path( $language );
		file_put_contents( $translation_file, json_encode( $translations, JSON_PRETTY_PRINT ) );
	}

	public function read_translations( string $language ): array
	{
		$translation_file = $this->translation_file_path( $language );

		return file_exists( $translation_file ) ?
			json_decode( file_get_contents( $translation_file ), true ) :
			[];
	}

	private function translation_file_path( string $language ): string
	{
		return $this->app_base_path . '/lang/' . $language . '.json';
	}
}
