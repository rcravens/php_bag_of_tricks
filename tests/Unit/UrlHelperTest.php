<?php

use Cravens\Php\Utilities\UrlHelper;

test( 'build returns expected result with no params', function () {
	$base_url = 'https://example.com';
	$params   = [];

	$url = UrlHelper::build( $base_url, $params );

	expect( $url )->toBe( $base_url );
} );

test( 'build returns expected result with new params and no existing query string', function () {
	$base_url = 'https://example.com';
	$params   = [
		'foo' => 'bar',
		'bar' => 'baz',
	];

	$url = UrlHelper::build( $base_url, $params );

	expect( $url )->toBe( $base_url . '?foo=bar&bar=baz' );
} );

test( 'build returns expected result with new params and existing query string', function () {
	$base_url = 'https://example.com?one=two';

	$params = [
		'foo' => 'bar',
		'bar' => 'baz',
	];

	$url = UrlHelper::build( $base_url, $params );

	expect( $url )->toBe( $base_url . '&foo=bar&bar=baz' );
} );

test( 'normalize returns expected when no numeric parameters found', function () {
	$base_url = 'https://example.com/posts';

	$url = UrlHelper::normalize( $base_url );

	expect( $url )->toBe( $base_url );
} );

test( 'normalize returns expected when numeric parameters found', function () {
	$base_url = 'https://example.com/posts/12/comments/32';

	$url = UrlHelper::normalize( $base_url );

	expect( $url )->toBe( 'https://example.com/posts/:id/comments/:id' );
} );
