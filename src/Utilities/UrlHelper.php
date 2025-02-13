<?php

namespace Cravens\Php\Utilities;

class UrlHelper
{
	public static function build( string $base_url, array $params ): string
	{
		if ( count( $params ) == 0 )
		{
			return $base_url;
		}

		$terms = [];
		foreach ( $params as $key => $value )
		{
			$terms[] = $key . '=' . $value;
		}

		$new_query_string = implode( '&', $terms );

		if ( str_contains( $base_url, '?' ) )
		{
			return $base_url . '&' . $new_query_string;
		}

		return $base_url . '?' . $new_query_string;
	}

	public static function normalize( string $url ): string
	{
		return preg_replace( '/\b\d+\b/', ':id', $url );
	}
}
