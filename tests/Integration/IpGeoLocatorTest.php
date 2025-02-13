<?php


use Cravens\Php\Utilities\IpGeoLocator;


test( 'can geolocate based on ip address', function () {
	$helper = new IpGeoLocator( $_ENV[ 'IPGEOLOCATION_API_KEY' ], '75.100.3.111' );

	expect( $helper )->not()->toBeNull()
	                 ->toHaveProperty( 'ip_address' )
	                 ->toHaveProperty( 'continent_name' )
	                 ->toHaveProperty( 'country_name' )
	                 ->toHaveProperty( 'state_prov' )
	                 ->toHaveProperty( 'city' )
	                 ->toHaveProperty( 'postal_code' )
	                 ->toHaveProperty( 'latitude' )
	                 ->toHaveProperty( 'longitude' )
	                 ->and( $helper->ip_address )->toBe( '75.100.3.111' )
	                 ->and( $helper->continent_name )->toBe( 'North America' )
	                 ->and( $helper->country_name )->toBe( 'United States' )
	                 ->and( $helper->state_prov )->toBe( 'Wisconsin' )
	                 ->and( $helper->city )->toBe( 'Madison' )
	                 ->and( $helper->postal_code )->toBe( '53715' );
} );
