<?php


use Cravens\Php\Utilities\GeoLocationHelper;


test( 'can determine address from lat / lng', function () {
	$lat = 38.897957;
	$lng = - 77.036560;

	$helper = new GeoLocationHelper( $_ENV[ 'GOOGLE_MAPS_API_KEY' ] );
	$result = $helper->get_address( $lat, $lng );

	expect( $result )->not()->toBeNull()
	                 ->toHaveProperty( 'city' )
	                 ->toHaveProperty( 'state' )
	                 ->toHaveProperty( 'postal_code' )
	                 ->toHaveProperty( 'country' )
		//             ->and( $result->street_number )->toBe( '1600' )
		//->and( $result->street )->toBe( 'Pennsylvania Avenue Northwest' )
		             ->and( $result->city )->toBe( 'Washington' )
	                 ->and( $result->state )->toBe( 'District of Columbia' )
	                 ->and( $result->country )->toBe( 'United States' )
	                 ->and( $result->country_code )->toBe( 'US' );
} );

test( 'can determine lat / lng for an address', function () {
	$address              = new stdClass();
	$address->street1     = '1600 Pennsylvania Avenue NW';
	$address->city        = 'Washington';
	$address->state       = 'DC';
	$address->postal_code = '20500';
	$address->country     = 'United States';

	$helper = new GeoLocationHelper( $_ENV[ 'GOOGLE_MAPS_API_KEY' ] );
	$result = $helper->get_lat_lng( $address->street1, $address->city, $address->state, $address->postal_code, $address->country );

	$lat = 38.8948949;
	$lng = - 77.0371581;

	$lat_error        = abs( $result->latitude - $lat );
	$lng_error        = abs( $result->longitude - $lng );
	$acceptable_error = 0.0001;

	expect( $result )->not()->toBeNull()
	                 ->toHaveProperty( 'latitude' )
	                 ->toHaveProperty( 'longitude' )
	                 ->and( $lat_error )->toBeLessThan( $acceptable_error )
	                 ->and( $lng_error )->toBeLessThan( $acceptable_error );
} );


test( 'can get directions', function () {
	$from_address = '1600 Pennsylvania Avenue NW, Washington, DC 20500, United States';
	$to_address   = 'E Capitol St. & 1st St. NE, Washington, DC 20004, United States';

	$helper = new GeoLocationHelper( $_ENV[ 'GOOGLE_MAPS_API_KEY' ] );
	$result = $helper->get_directions( $from_address, $to_address );

	expect( $result )->not()->toBeNull()
	                 ->toHaveProperty( 'summary' )
	                 ->toHaveProperty( 'steps' )
	                 ->and( count( $result->steps ) )->toBeGreaterThan( 0 );
} );
