<?php

use Cravens\Php\Utilities\GenericResult;

test( 'constructor defaults to expected values', function () {
	$result = new GenericResult();
	expect( $result )->toBeInstanceOf( GenericResult::class )
	                 ->and( $result->is_error )->toBeFalse()
	                 ->and( $result->error )->toBeNull()
	                 ->and( $result->data )->toBeObject()
	                 ->and( count( get_object_vars( $result->data ) ) )->toBe( 0 );
} );

test( 'error creates to expected values', function () {
	$result = GenericResult::error( 'This is some text' );
	expect( $result )->toBeInstanceOf( GenericResult::class )
	                 ->and( $result->is_error )->toBeTrue()
	                 ->and( $result->error )->toBe( 'This is some text' )
	                 ->and( $result->data )->toBeObject()
	                 ->and( count( get_object_vars( $result->data ) ) )->toBe( 0 );
} );

test( 'no_error creates to expected values', function () {
	$result = GenericResult::no_error();
	expect( $result )->toBeInstanceOf( GenericResult::class )
	                 ->and( $result->is_error )->toBeFalse()
	                 ->and( $result->error )->toBeNull()
	                 ->and( $result->data )->toBeObject()
	                 ->and( count( get_object_vars( $result->data ) ) )->toBe( 0 );
} );

test( 'data with null creates to expected values', function () {
	$data   = null;
	$result = GenericResult::data( $data );
	expect( $result )->toBeInstanceOf( GenericResult::class )
	                 ->and( $result->is_error )->toBeFalse()
	                 ->and( $result->error )->toBeNull()
	                 ->and( $result->data )->toBeNull();
} );

test( 'data with object creates to expected values', function () {
	$data        = new stdClass();
	$data->prop1 = 'Property 1';
	$data->prop2 = 'Property 2';

	$result = GenericResult::data( $data );
	expect( $result )->toBeInstanceOf( GenericResult::class )
	                 ->and( $result->is_error )->toBeFalse()
	                 ->and( $result->error )->toBeNull()
	                 ->and( $result->data )->toBeObject()
	                 ->and( $result->data )->toBe( $data );
} );

