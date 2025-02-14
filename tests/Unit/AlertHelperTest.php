<?php

use Cravens\Php\Utilities\AlertHelper;

test( 'create returns expected config', function () {
	$title = 'This is a title';
	$text  = 'This is some text';
	$type  = 'success';

	$alert_helper = new AlertHelper();
	$alert_helper->create( $title, $text, $type );
	$config = $alert_helper->config();
	var_dump( $config );
	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'timer', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( $title )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( $type )
	                 ->and( $config[ 'showConfirmButton' ] )->toBeFalse()
	                 ->and( $config[ 'timer' ] )->toBe( 3000 )
	                 ->and( $config[ 'allowOutsideClick' ] )->toBeTrue();
} );

test( 'info returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->info( $text );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'timer', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Info' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'info' );
} );

test( 'success returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->success( $text );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'timer', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Success' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'success' );
} );

test( 'warning returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->warning( $text );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'timer', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Warning' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'warning' );
} );

test( 'error returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->error( $text );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'timer', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Error' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'error' );
} );

test( 'auto_close returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->error( $text )->autoClose( 1234 );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'timer', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Error' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'error' )
	                 ->and( $config[ 'timer' ] )->toBe( 1234 );
} );

test( 'confirmButton returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->error( $text )->confirmButton( 'Close Me Now' );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'timer', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Error' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'error' )
	                 ->and( $config[ 'confirmButtonText' ] )->toBe( 'Close Me Now' )
	                 ->and( $config[ 'showConfirmButton' ] )->toBeTrue();
} );

test( 'persistent returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->error( $text )->persistent( 'Click Me' );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'allowOutsideClick', 'title', 'text', 'icon' ] )
	                 ->not()->toHaveKeys( [ 'timer' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Error' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'error' )
	                 ->and( $config[ 'confirmButtonText' ] )->toBe( 'Click Me' )
	                 ->and( $config[ 'showConfirmButton' ] )->toBeTrue()
	                 ->and( $config[ 'allowOutsideClick' ] )->toBeFalse();
} );

test( 'persistent2 returns expected config', function () {
	$text = 'This is some text';

	$alert_helper = new AlertHelper();
	$alert_helper->error( $text )->persistent2( 'Click Me' );
	$config = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'allowOutsideClick', 'timer', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Error' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'error' )
	                 ->and( $config[ 'showConfirmButton' ] )->toBeFalse()
	                 ->and( $config[ 'allowOutsideClick' ] )->toBeFalse()
	                 ->and( $config[ 'timer' ] )->toBe( 'null' );
} );

test( 'helper returns expected config', function () {
	$text = 'This is some text';


	$alert_helper = alert()->error( $text )->persistent2( 'Click Me' );
	$config       = $alert_helper->config();

	expect( $config )->toBeArray()
	                 ->toHaveKeys( [ 'showConfirmButton', 'allowOutsideClick', 'timer', 'title', 'text', 'icon' ] )
	                 ->and( $config[ 'title' ] )->toBe( 'Error' )
	                 ->and( $config[ 'text' ] )->toBe( $text )
	                 ->and( $config[ 'icon' ] )->toBe( 'error' )
	                 ->and( $config[ 'showConfirmButton' ] )->toBeFalse()
	                 ->and( $config[ 'allowOutsideClick' ] )->toBeFalse()
	                 ->and( $config[ 'timer' ] )->toBe( 'null' );
} );