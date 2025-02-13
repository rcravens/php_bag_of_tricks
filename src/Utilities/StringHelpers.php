<?php

namespace Cravens\Php\Utilities;

class StringHelpers
{
	public static function shorten( string $str, int $max_length = 40, string $spacer = '.....', $num_end_chars = 10 ): string
	{
		if ( strlen( $str ) < $max_length )
		{
			return $str;
		}

		if ( $num_end_chars == 0 )
		{
			return substr( $str, 0, $max_length ) . $spacer;
		}

		return substr( $str, 0, $max_length - $num_end_chars ) . $spacer . substr( $str, - $num_end_chars );
	}
}
