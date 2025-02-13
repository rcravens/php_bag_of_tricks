<?php

namespace Cravens\Php\Utilities;

class UnitHelper
{
	public string $country_code2;
	public bool   $is_metric;

	public function __construct( string $country_code2 )
	{
		$this->country_code2 = $country_code2;

		$this->is_metric = UnitHelper::is_metric( $this->country_code2 );
	}

	public static function is_metric( $country ): bool
	{
		if ( is_null( $country ) )
		{
			return false;
		}

		$standard_unit_countries = [ "US", "LR", "MM", "BS", "BZ", "KY", "PW", "GB", "UK" ];

		return ! in_array( $country, $standard_unit_countries );
	}

	public static function convert_miles_to_kilometers( $miles ): float
	{
		return $miles * 1.609344;
	}

	public static function convert_kilometers_to_miles( $kilometers ): float
	{
		return $kilometers / 1.609344;
	}

	public static function convert_feet_to_meters( $feet ): float
	{
		return $feet * 0.3048;
	}

	public static function convert_meters_to_feet( $meters ): float
	{
		return $meters / 0.3048;
	}

	public static function convert_pounds_to_kilograms( $pounds ): float
	{
		return $pounds * 0.453592;
	}

	public static function convert_kilograms_to_pounds( $kilograms ): float
	{
		return $kilograms / 0.453592;
	}

	public static function convert_miles_to_feet( $miles ): float
	{
		return $miles * 5280;
	}

	public static function convert_feet_to_miles( $feet ): float
	{
		return $feet / 5280;
	}

	public function localized_distance_str( float $distance_in_miles ): string
	{
		if ( $this->is_metric )
		{
			return number_format( UnitHelper::convert_miles_to_kilometers( $distance_in_miles ), 2 ) . ' ' . __x( 'kilometers' );
		}

		return number_format( $distance_in_miles, 2 ) . ' ' . __x( 'miles' );
	}

	public function localized_altitude_str( float $altitude_in_feet ): string
	{
		if ( $this->is_metric )
		{
			return number_format( UnitHelper::convert_feet_to_meters( $altitude_in_feet ), 2 ) . ' ' . __x( 'meters' );
		}

		return number_format( $altitude_in_feet, 2 ) . ' ' . __x( 'feet' );
	}

	public function localized_elevation_change_str( float $elevation_change_in_feet ): string
	{
		if ( $this->is_metric )
		{
			return number_format( UnitHelper::convert_feet_to_meters( $elevation_change_in_feet ), 2 ) . ' ' . __x( 'meters' );
		}

		return number_format( $elevation_change_in_feet, 2 ) . ' ' . __x( 'feet' );
	}
}
