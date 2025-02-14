<?php

use Cravens\Php\Utilities\I18n\I18nHelper;

test( 'currency_codes returns expected values', function () {

	$currency_codes = I18nHelper::currency_codes();

	expect( $currency_codes )->toBeArray()
	                         ->toContain( 'USD' );
} );

test( 'currency_info returns expected values', function () {

	$currency_info = I18nHelper::currency_info( 'USD' );

	expect( $currency_info )->toBeObject()
	                        ->toHaveProperties( [ 'symbol', 'name', 'symbol_native', 'decimal_digits', 'rounding', 'code', 'name_plural' ] )
	                        ->and( $currency_info->symbol )->toBe( '$' )
	                        ->and( $currency_info->name )->toBe( 'US Dollar' )
	                        ->and( $currency_info->code )->toBe( 'USD' );
} );

test( 'currency_info returns expected values when fed an array', function () {

	$currency_info = I18nHelper::currency_info( [ 'USD', 'EUR' ] );

	expect( $currency_info )->toBeArray()
	                        ->toHaveKeys( [ 'USD', 'EUR' ] )
	                        ->and( $currency_info[ 'USD' ] )->toHaveProperties( [ 'symbol', 'name', 'symbol_native', 'decimal_digits', 'rounding', 'code', 'name_plural' ] )
	                        ->and( $currency_info[ 'USD' ]->symbol )->toBe( '$' )
	                        ->and( $currency_info[ 'USD' ]->name )->toBe( 'US Dollar' )
	                        ->and( $currency_info[ 'USD' ]->code )->toBe( 'USD' );
} );

test( 'currencies returns expected values', function () {

	$currencies = I18nHelper::currencies();

	expect( $currencies )->toBeObject()
	                     ->toHaveProperties( [ 'USD', 'EUR' ] )
	                     ->and( $currencies->USD )->toHaveProperties( [ 'symbol', 'name', 'symbol_native', 'decimal_digits', 'rounding', 'code', 'name_plural' ] )
	                     ->and( $currencies->USD->symbol )->toBe( '$' )
	                     ->and( $currencies->USD->name )->toBe( 'US Dollar' )
	                     ->and( $currencies->USD->code )->toBe( 'USD' );
} );

test( 'countries returns expected values', function () {

	$countries = I18nHelper::countries();

	expect( $countries )->toBeObject()
	                    ->toHaveProperty( 'US' )
	                    ->and( $countries->US )->toBeObject()
	                    ->and( $countries->US )->toHaveProperties( [ 'country_iso_2char', 'country_iso_3char', 'country_iso_number', 'country_name', 'currency_iso_3char', 'currency_name', 'state_name', 'states' ] )
	                    ->and( $countries->US->country_iso_3char )->toBe( 'USA' )
	                    ->and( $countries->US->country_name )->toBe( 'United States' )
	                    ->and( $countries->US->currency_iso_3char )->toBe( 'USD' )
	                    ->and( $countries->US->states )->toBeObject()
	                    ->and( count( get_object_vars( $countries->US->states ) ) )->toBe( 51 )
	                    ->and( $countries->US->states->WI )->toBe( 'Wisconsin' )
	                    ->and( $countries->US->states->DC )->toBe( 'District Of Columbia' );
} );

test( 'country_by_code returns expected values', function () {

	$country = I18nHelper::country_by_code( 'KLKJLS' );
	expect( $country )->toBeNull();

	$country = I18nHelper::country_by_code( 'US' );
	expect( $country )->toBeObject()
	                  ->and( $country )->toHaveProperties( [ 'country_iso_2char', 'country_iso_3char', 'country_iso_number', 'country_name', 'currency_iso_3char', 'currency_name', 'state_name', 'states' ] )
	                  ->and( $country->country_iso_3char )->toBe( 'USA' )
	                  ->and( $country->country_name )->toBe( 'United States' )
	                  ->and( $country->currency_iso_3char )->toBe( 'USD' )
	                  ->and( $country->states )->toBeObject()
	                  ->and( count( get_object_vars( $country->states ) ) )->toBe( 51 )
	                  ->and( $country->states->WI )->toBe( 'Wisconsin' )
	                  ->and( $country->states->DC )->toBe( 'District Of Columbia' );
} );

test( 'country_by_name returns expected values', function () {

	$country = I18nHelper::country_by_name( 'KLKJLS' );
	expect( $country )->toBeNull();

	$country = I18nHelper::country_by_name( 'United States' );
	expect( $country )->toBeObject()
	                  ->and( $country )->toHaveProperties( [ 'country_iso_2char', 'country_iso_3char', 'country_iso_number', 'country_name', 'currency_iso_3char', 'currency_name', 'state_name', 'states' ] )
	                  ->and( $country->country_iso_3char )->toBe( 'USA' )
	                  ->and( $country->country_name )->toBe( 'United States' )
	                  ->and( $country->currency_iso_3char )->toBe( 'USD' )
	                  ->and( $country->states )->toBeObject()
	                  ->and( count( get_object_vars( $country->states ) ) )->toBe( 51 )
	                  ->and( $country->states->WI )->toBe( 'Wisconsin' )
	                  ->and( $country->states->DC )->toBe( 'District Of Columbia' );
} );

test( 'states returns expected values', function () {

	$states = I18nHelper::states( 'KLKJLS' );
	expect( $states )->toBeArray()->and( count( $states ) )->toBe( 0 );

	$states = I18nHelper::states( 'US' );
	expect( $states )->toBeArray()
	                 ->and( count( $states ) )->toBe( 51 )
	                 ->and( $states[ 'WI' ] )->toBe( 'Wisconsin' )
	                 ->and( $states[ 'DC' ] )->toBe( 'District Of Columbia' );
} );

test( 'cost_str returns expected values', function () {

	$cost_str = I18nHelper::cost_str( 'KLKJLS', 123.45 );
	expect( $cost_str )->toBeString()->toBe( '$123.45' );

	$cost_str = I18nHelper::cost_str( 'US', 123.45 );
	expect( $cost_str )->toBeString()->toBe( '$123.45' );

	$cost_str = I18nHelper::cost_str( 'GBP', 123.45 );
	expect( $cost_str )->toBeString()->toBe( '&pound;123.45' );

	$cost_str = I18nHelper::cost_str( 'EUR', 123.45 );
	expect( $cost_str )->toBeString()->toBe( '&euro;123.45' );

	$cost_str = I18nHelper::cost_str( 'BRL', 123.45 );
	expect( $cost_str )->toBeString()->toBe( 'R$123.45' );

	$cost_str = I18nHelper::cost_str( 'THB', 123.45 );
	expect( $cost_str )->toBeString()->toBe( '123.45 Bt' );
} );