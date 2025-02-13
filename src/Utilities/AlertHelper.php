<?php

namespace Cravens\Php\Utilities;

class AlertHelper
{
	private array $config = [
		'showConfirmButton' => false,
		'timer'             => 3000,
		'allowOutsideClick' => true
	];

	public function create( $title, $text, $type ): static
	{
		$this->config[ 'title' ] = $title;
		$this->config[ 'text' ]  = $text;
		$this->config[ 'icon' ]  = $type;

		$this->flashConfig();

		return $this;
	}

	public function info( $text, $title = 'Info' ): static
	{
		$this->create( $title, $text, 'info' );

		return $this;
	}

	public function success( $text, $title = 'Success' ): static
	{
		$this->create( $title, $text, 'success' );

		return $this;
	}

	public function warning( $text, $title = 'Warning' ): static
	{
		$this->create( $title, $text, 'warning' );

		return $this;
	}

	public function error( $text, $title = 'Error' ): static
	{
		$this->create( $title, $text, 'error' );

		return $this;
	}

	public function autoclose( $milliseconds = 1800 ): static
	{
		$this->config[ 'timer' ] = $milliseconds;

		$this->flashConfig();

		return $this;
	}

	public function confirmButton( $buttonText = 'OK' ): static
	{
		$this->config[ 'confirmButtonText' ] = $buttonText;
		$this->config[ 'showConfirmButton' ] = true;

		$this->flashConfig();

		return $this;
	}

	public function persistent( $buttonText = 'OK' ): static
	{
		$this->config[ 'confirmButtonText' ] = $buttonText;
		$this->config[ 'showConfirmButton' ] = true;
		$this->config[ 'allowOutsideClick' ] = false;

		unset( $this->config[ 'timer' ] );

		$this->flashConfig();

		return $this;
	}

	public function persistent2(): static
	{
		$this->config[ 'showConfirmButton' ] = false;
		$this->config[ 'allowOutsideClick' ] = false;
		$this->config[ 'timer' ]             = 'null';

		$this->flashConfig();

		return $this;
	}

	private function flashConfig(): void
	{
		if ( function_exists( 'session' ) )
		{
			session()->flash( "sweet_alert", $this->buildConfig() );
		}
	}

	private function buildConfig(): false|string
	{
		return json_encode( $this->config );
	}
}
