<?php

namespace Cravens\Php\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function App\Code\Traits\dd;

trait CachableTrait
{
	public static function cache_get_value( $key )
	{
		if ( function_exists( 'cache' ) )
		{
			return cache()->has( $key ) ? cache()->get( $key ) : null;
		}

		return null;
	}

	public static function cache_set_value( $key, $value, Carbon $timeout ): void
	{
		if ( function_exists( 'cache' ) )
		{
			cache()->put( $key, $value, $timeout );
		}
	}
}
