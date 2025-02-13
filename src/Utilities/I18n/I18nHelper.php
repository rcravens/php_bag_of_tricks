<?php

namespace Cravens\Php\Utilities\I18n;

class I18nHelper
{
	public static function currency_codes(): array
	{
		return array_keys( get_object_vars( self::currencies() ) );
	}

	public static function currency_info( string|array $currency_code )
	{
		if ( is_string( $currency_code ) )
		{
			return property_exists( self::currencies(), $currency_code ) ? self::currencies()->{$currency_code} : null;
		}
		$result = [];
		foreach ( $currency_code as $code )
		{
			if ( property_exists( self::currencies(), $code ) )
			{
				$result[ $code ] = self::currencies()->{$code};
			}
		}

		return $result;
	}

	public static function currencies()
	{
		$key = 'i18n_currencies';
		if ( ! cache()->has( $key ) )
		{
			$raw = file_get_contents( __DIR__ . '/currencies.json' );

			$data = json_decode( $raw );
			cache()->put( $key, $data, now()->addDay() );
		}

		return cache()->get( $key );
	}

	public static function countries()
	{
		$key = 'i18n_countries';
		if ( ! cache()->has( $key ) )
		{
			$raw = file_get_contents( __DIR__ . '/countries.json' );
			$raw = preg_replace( '/^\xEF\xBB\xBF/', '', $raw );

			$countries = json_decode( $raw );
			cache()->put( $key, $countries, now()->addDay() );
		}

		return cache()->get( $key );
	}

	public static function country_by_code( $code ): ?object
	{
		if ( ! array_key_exists( $code, self::countries() ) )
		{
			return null;
		}

		return self::countries()->{$code};
	}

	public static function country_by_name( $name ): ?object
	{
		foreach ( self::countries() as $code => $data )
		{
			if ( $name == $data->country_name )
			{
				return $data;
			}
		}

		return null;
	}

	public static function states( string $country_code ): array
	{
		$country = self::country_by_code( $country_code );
		if ( is_null( $country ) )
		{
			return [];
		}

		return $country->states;
	}

	public static function cost_str( string $currency_code, float $amount = 0 ): string
	{
		$bc              = $currency_code;
		$currency_before = '';
		$currency_after  = '';

		if ( $bc == 'GBP' )
		{
			$currency_before = '&pound;';
		}
		if ( $bc == 'EUR' )
		{
			$currency_before = '&euro;';
		}
		if ( $bc == 'BRL' )
		{
			$currency_before = 'R$';
		}
		if ( $bc == 'CNY' || $bc == 'JPY' )
		{
			$currency_before = '&yen;';
		}
		if ( $bc == 'CRC' )
		{
			$currency_before = '&cent;';
		}
		if ( $bc == 'HRK' )
		{
			$currency_after = ' kn';
		}
		if ( $bc == 'CZK' )
		{
			$currency_after = ' kc';
		}
		if ( $bc == 'DKK' )
		{
			$currency_before = 'DKK ';
		}
		if ( $bc == 'HKD' )
		{
			$currency_before = 'HK$';
		}
		if ( $bc == 'HUF' )
		{
			$currency_after = ' Ft';
		}
		if ( $bc == 'ISJ' || $bc == 'SEK' )
		{
			$currency_after = ' kr';
		}
		if ( $bc == 'INR' )
		{
			$currency_before = 'Rs. ';
		}
		if ( $bc == 'IDR' )
		{
			$currency_before = 'Rp. ';
		}
		if ( $bc == 'ILS' )
		{
			$currency_after = ' NIS';
		}
		if ( $bc == 'LVL' )
		{
			$currency_before = 'Ls ';
		}
		if ( $bc == 'LTL' )
		{
			$currency_after = ' Lt';
		}
		if ( $bc == 'MYR' )
		{
			$currency_before = 'RM';
		}
		if ( $bc == 'NOK' )
		{
			$currency_before = 'kr ';
		}
		if ( $bc == 'PHP' )
		{
			$currency_before = 'PHP';
		}
		if ( $bc == 'PLN' )
		{
			$currency_after = ' z';
		}
		if ( $bc == 'ROK' )
		{
			$currency_after = ' lei';
		}
		if ( $bc == 'ZAL' )
		{
			$currency_before = 'R ';
		}
		if ( $bc == 'KRW' )
		{
			$currency_before = 'W';
		}
		if ( $bc == 'CHF' )
		{
			$currency_before = 'SFr. ';
		}
		if ( $bc == 'SYP' )
		{
			$currency_after = ' SYP';
		}
		if ( $bc == 'THB' )
		{
			$currency_after = ' Bt';
		}
		if ( $bc == 'TTD' )
		{
			$currency_before = 'TT$';
		}
		if ( $bc == 'TRL' )
		{
			$currency_after = ' TL';
		}
		if ( $bc == 'AED' )
		{
			$currency_before = 'Dhs. ';
		}
		if ( $bc == 'VEB' )
		{
			$currency_before = 'Bs. ';
		}

		if ( $currency_before == '' && $currency_after == '' )
		{
			$currency_before = '$';
		}

		return $currency_before . number_format( $amount, 2 ) . $currency_after;
	}
}
