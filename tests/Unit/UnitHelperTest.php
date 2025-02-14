<?php

use Cravens\Php\Utilities\UnitHelper;

test( 'constructor gets metric flag correct US', function () {
	$helper = new UnitHelper( 'US' );
	expect( $helper )->toBeInstanceOf( UnitHelper::class )
	                 ->and( $helper->is_metric )->toBeFalse();
} );

test( 'constructor gets metric flag correct RU', function () {
	$helper = new UnitHelper( 'RU' );
	expect( $helper )->toBeInstanceOf( UnitHelper::class )
	                 ->and( $helper->is_metric )->toBeTrue();
} );

test( 'convert_miles_to_kilometers returns expected result', function () {
	$km = UnitHelper::convert_miles_to_kilometers( 1 );

	$expected = 1.60934;

	$abs_error = abs( $km - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'convert_kilometers_to_miles returns expected result', function () {
	$mi = UnitHelper::convert_kilometers_to_miles( 1 );

	$expected = 0.621371;

	$abs_error = abs( $mi - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'convert_feet_to_meters returns expected result', function () {
	$m = UnitHelper::convert_feet_to_meters( 1 );

	$expected = 0.3048;

	$abs_error = abs( $m - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'convert_meters_to_feet returns expected result', function () {
	$ft = UnitHelper::convert_meters_to_feet( 1 );

	$expected = 3.28084;

	$abs_error = abs( $ft - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'convert_pounds_to_kilograms returns expected result', function () {
	$kg = UnitHelper::convert_pounds_to_kilograms( 1 );

	$expected = 0.453592;

	$abs_error = abs( $kg - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'convert_kilograms_to_pounds returns expected result', function () {
	$lbs = UnitHelper::convert_kilograms_to_pounds( 1 );

	$expected = 2.20462;

	$abs_error = abs( $lbs - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'convert_miles_to_feet returns expected result', function () {
	$ft = UnitHelper::convert_miles_to_feet( 1 );

	$expected = 5280;

	$abs_error = abs( $ft - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'convert_feet_to_miles returns expected result', function () {
	$mi = UnitHelper::convert_feet_to_miles( 1 );

	$expected = 0.000189394;

	$abs_error = abs( $mi - $expected );
	expect( $abs_error )->toBeLessThan( 0.00001 );
} );

test( 'localized_distance_str returns expected result in US', function () {
	$helper = new UnitHelper( 'US' );

	$str = $helper->localized_distance_str( 1 );

	expect( $str )->toBe( '1.00 miles' );
} );

test( 'localized_distance_str returns expected result in RU', function () {
	$helper = new UnitHelper( 'RU' );

	$str = $helper->localized_distance_str( 1 );

	expect( $str )->toBe( '1.61 kilometers' );
} );

test( 'localized_altitude_str returns expected result in US', function () {
	$helper = new UnitHelper( 'US' );

	$str = $helper->localized_altitude_str( 1000 );

	expect( $str )->toBe( '1,000.00 feet' );
} );

test( 'localized_altitude_str returns expected result in RU', function () {
	$helper = new UnitHelper( 'RU' );

	$str = $helper->localized_altitude_str( 1000 );

	expect( $str )->toBe( '304.80 meters' );
} );

test( 'localized_elevation_change_str returns expected result in US', function () {
	$helper = new UnitHelper( 'US' );

	$str = $helper->localized_elevation_change_str( 1000 );

	expect( $str )->toBe( '1,000.00 feet' );
} );

test( 'localized_elevation_change_str returns expected result in RU', function () {
	$helper = new UnitHelper( 'RU' );

	$str = $helper->localized_elevation_change_str( 1000 );

	expect( $str )->toBe( '304.80 meters' );
} );

