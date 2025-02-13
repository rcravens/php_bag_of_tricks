<?php

namespace Cravens\Php\Utilities;

class GenericResult
{
	public bool               $is_error;
	public ?string            $error;
	public object|string|null $data;

	public function __construct()
	{
		$this->is_error = false;
		$this->error    = null;
		$this->data     = new \stdClass();
	}

	public static function error( string $error ): GenericResult
	{
		$result           = new GenericResult();
		$result->is_error = true;
		$result->error    = $error;

		return $result;
	}

	public static function no_error(): GenericResult
	{
		$result           = new GenericResult();
		$result->is_error = false;
		$result->error    = null;

		return $result;
	}

	public static function data( object|string|null $data ): GenericResult
	{
		$result       = new GenericResult();
		$result->data = $data;

		return $result;
	}
}
