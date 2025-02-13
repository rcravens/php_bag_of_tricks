<?php
//
//use App\Code\Utilities\GeoLocationHelper;
//use App\Models\Address;
//
//test( 'can determine lat / lng for an address', function () {
//    $address              = new Address();
//    $address->street1     = '1600 Pennsylvania Avenue NW';
//    $address->city        = 'Washington';
//    $address->state       = 'DC';
//    $address->postal_code = '20500';
//    $address->country     = 'United States';
//
//    $helper = new GeoLocationHelper();
//    $result = $helper->get_lat_lng( $address->street1, $address->city, $address->state, $address->postal_code, $address->country );
//
//    expect( $result )->not()->toBeNull()
//                     ->toHaveProperty( 'latitude' )
//                     ->toHaveProperty( 'longitude' );
//} );
