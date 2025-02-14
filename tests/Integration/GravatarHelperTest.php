<?php

use Cravens\Php\Utilities\GravatarHelper;

test( 'returns expected result', function () {
	$url = ( new GravatarHelper() )->get_gravatar_url( 'bob.cravens@gmail.com', 128 );

	expect( $url )->toBeString()
	              ->toStartWith( 'https://www.gravatar.com/avatar/' )
	              ->toContain( 's=128' )
	              ->toContain( 'd=mp' )
	              ->toContain( 'r=g' );
} );
