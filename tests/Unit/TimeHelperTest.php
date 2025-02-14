<?php

use Cravens\Php\Utilities\TimeHelper;

test( 'returns expected result with only seconds', function () {
	$helper = new TimeHelper( 10 );
	$str    = $helper->to_str();

	expect( $str )->toBe( '10s' );
} );

test( 'returns expected result with minutes and seconds', function () {
	$helper = new TimeHelper( 72 );
	$str    = $helper->to_str();

	expect( $str )->toBe( '1m:12s' );
} );

test( 'returns expected result with hours, minutes and seconds', function () {
	$num_seconds   = 2;
	$num_minutes   = 22;
	$num_hours     = 3;
	$total_seconds = $num_hours * 60 * 60 + $num_minutes * 60 + $num_seconds;
	$helper        = new TimeHelper( $total_seconds );
	$str           = $helper->to_str();

	expect( $str )->toBe( '3h:22m:2s' );
} );

test( 'returns expected result with days, hours, minutes and seconds', function () {
	$num_seconds   = 22;
	$num_minutes   = 4;
	$num_hours     = 7;
	$num_days      = 4;
	$total_seconds = $num_days * 24 * 60 * 60 + $num_hours * 60 * 60 + $num_minutes * 60 + $num_seconds;
	$helper        = new TimeHelper( $total_seconds );
	$str           = $helper->to_str();

	expect( $str )->toBe( '4d:7h:4m:22s' );
} );
