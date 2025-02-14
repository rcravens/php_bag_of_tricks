<?php

use Cravens\Php\Utilities\StringHelpers;

test( 'shorten returns expected result with short text', function () {
	$str = 'Test One Two Three';
	$len = strlen( $str );

	$result = StringHelpers::shorten( $str, $len + 10 );
	expect( $result )->toBe( $str );
} );

test( 'shorten returns expected result with long text', function () {
	$str = 'Test One Two Three';
	$len = strlen( $str );

	$result = StringHelpers::shorten( $str, 7, '...', 0 );
	expect( $result )->toBe( 'Test...' );
} );

test( 'shorten returns expected result with longer text', function () {
	$str = 'Test One Two Three';
	$len = strlen( $str );

	$result = StringHelpers::shorten( $str, 12, '...', 5 );
	expect( $result )->toBe( 'Test...Three' );
} );