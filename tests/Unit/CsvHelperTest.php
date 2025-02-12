<?php

use Cravens\Php\Utilities\CsvHelper;

test( 'returns expected result for comma separated string values', function () {
	$data = [
		'red',
		'green',
		'blue'
	];
	$str  = implode( ',', $data );

	$result = CsvHelper::with( $str )->get();

	expect( $result )->toBeArray()->toHaveCount( count( $data ) );

	foreach ( $data as $d )
	{
		expect( $d )->toBeIn( $result );
	}
} );

test( 'returns expected result for other delimiter separated string values', function () {
	$data = [
		'red',
		'green',
		'blue'
	];
	$str  = implode( '|', $data );

	$result = CsvHelper::with( $str )->delimiter( '|' )->get();

	expect( $result )->toBeArray()->toHaveCount( count( $data ) );

	foreach ( $data as $d )
	{
		expect( $d )->toBeIn( $result );
	}
} );

test( 'returns expected result when no delimiter found', function () {
	$data = [
		'red',
		'green',
		'blue'
	];
	$str  = implode( '|', $data );

	$result = CsvHelper::with( $str )->get();

	expect( $result )->toBeArray()->toHaveCount( 1 )
	                 ->and( $result[ 0 ] )->toEqual( $str );
} );

test( 'to_lower returns expected results', function () {
	$data = [
		'Red',
		'GREEN',
		'bluE'
	];
	$str  = implode( ',', $data );

	$result = CsvHelper::with( $str )->to_lower()->get();


	expect( $result )->toBeArray()->toHaveCount( 3 );

	foreach ( $data as $d )
	{
		expect( strtolower( $d ) )->toBeIn( $result );
	}
} );


test( 'sort returns expected result', function () {
	$data = [
		'red',
		'green',
		'blue'
	];
	$str  = implode( ',', $data );

	$result = CsvHelper::with( $str )->sort()->get();

	expect( $result )->toBeArray()->toHaveCount( count( $data ) )
	                 ->and( $result[ 0 ] )->toBe( 'blue' )
	                 ->and( $result[ 1 ] )->toBe( 'green' )
	                 ->and( $result[ 2 ] )->toBe( 'red' );

} );

test( 'only_emails returns valid emails', function () {
	$valid_emails = [
		'test@example.com',
		'user+alias@sub.domain.co.uk',
		'firstname-lastname@domain.name',
		'user@veryveryveryverylongdomainnameexample.com'
	];

	$str = implode( ',', $valid_emails );

	$result = CsvHelper::with( $str )->only_emails()->get();

	expect( $result )->toBeArray()->toHaveCount( count( $valid_emails ) );

	foreach ( $valid_emails as $d )
	{
		expect( strtolower( $d ) )->toBeIn( $result );
	}
} );


test( 'only_emails rejects invalid emails', function () {
	$invalid_emails = [
		'plainaddress',
		'@missinguser.com ',
		'user@.com',
		'user@invalid_domain.com'
	];

	$str = implode( ',', $invalid_emails );

	$result = CsvHelper::with( $str )->only_emails()->get();

	expect( $result )->toBeArray()->toHaveCount( 0 );
} );


test( 'only_urls returns valid urls', function () {
	$valid_urls = [
		'https://example.com',
		'http://sub.domain.co.uk',
		'ftp://ftp.example.com',
		'https://valid-url.com/path?query=123',
		'https://valid-one.com'
	];

	$str = implode( ',', $valid_urls );

	$result = CsvHelper::with( $str )->only_urls()->get();

	expect( $result )->toBeArray()->toHaveCount( count( $valid_urls ) );

	foreach ( $valid_urls as $d )
	{
		expect( strtolower( $d ) )->toBeIn( $result );
	}
} );


test( 'only_urls rejects invalid urls', function () {
	$invalid_urls = [
		'invalid-url',
		'www.missing-http.com',
		'htp://typo.com',
		'https:// spaceinurl.com',
		'http://123.456.789.000',
	];

	$str = implode( ',', $invalid_urls );

	$result = CsvHelper::with( $str )->only_emails()->get();

	expect( $result )->toBeArray()->toHaveCount( 0 );
} );

test( 'only_domains returns valid domains', function () {
	$valid_domains = [
		'example.com',
		'get.memberowl.com',
		'zapl.io'
	];

	$str = implode( ',', $valid_domains );

	$result = CsvHelper::with( $str )->only_domains()->get();

	expect( $result )->toBeArray()->toHaveCount( count( $valid_domains ) );

	foreach ( $valid_domains as $d )
	{
		expect( strtolower( $d ) )->toBeIn( $result );
	}
} );

test( 'only_domains rejects invalid domains', function () {
	$invalid_domains = [
		'invalid_domain.com', // Invalid: contains an underscore
		'123.456.789.000', // Invalid: looks like an IP, not a domain
		'-leadinghyphen.com', // Invalid: cannot start with a hyphen
		'trailinghyphen-.net', // Invalid: cannot end with a hyphen
		'no-tld', // Invalid: missing TLD
		'super.longtldexample.toolongtld' // Invalid: unrealistic TLD
	];

	$str = implode( ',', $invalid_domains );

	$result = CsvHelper::with( $str )->only_domains()->get();

	expect( $result )->toBeArray()->toHaveCount( 0 );
} );