<?php

use Cravens\Php\Utilities\AlertHelper;
use Cravens\Php\Utilities\I18n\I18nHelper;


if ( ! function_exists( 'cost' ) )
{
	function cost( string $currency, float $amount ): string
	{
		return I18nHelper::cost_str( $currency, $amount );
	}
}

if ( ! function_exists( 'selected' ) )
{
	function selected( bool $is_selected ): string
	{
		return $is_selected ? 'selected = "selected"' : '';
	}
}

if ( ! function_exists( 'checked' ) )
{
	function checked( bool $is_checked ): string
	{
		return $is_checked ? 'checked = "checked"' : '';
	}
}

if ( ! function_exists( 'alert' ) )
{
	function alert( $title = null, $message = null )
	{
		if ( function_exists( 'app' ) )
		{
			$alert = app( AlertHelper::class );

			if ( func_num_args() == 0 )
			{
				return $alert;
			}

			return $alert->info( $title, $message );
		}

		return null;
	}
}

