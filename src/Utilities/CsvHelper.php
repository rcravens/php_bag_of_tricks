<?php

namespace Cravens\Php\Utilities;

class CsvHelper
{
	private string|null $csv;
	private string      $delimiter;

	private bool $is_to_lower = false;
	private bool $is_sorted   = false;
	private ?int $filter_var;

	public function __construct( string|null $csv, string $delimiter = ',' )
	{
		$this->csv        = $csv;
		$this->delimiter  = $delimiter;
		$this->filter_var = null;
	}

	public static function with( string|null $csv ): CsvHelper
	{
		return new self( $csv );
	}

	public function delimiter( string $delimiter ): static
	{
		$this->delimiter = $delimiter;

		return $this;
	}

	public function to_lower(): static
	{
		$this->is_to_lower = true;

		return $this;
	}

	public function only_emails(): self
	{
		$this->filter_var = FILTER_VALIDATE_EMAIL;

		return $this;
	}

	public function only_urls(): self
	{
		$this->filter_var = FILTER_VALIDATE_URL;

		return $this;
	}

	public function only_domains(): self
	{
		$this->filter_var = FILTER_VALIDATE_DOMAIN;

		return $this;
	}

	public function sort(): static
	{
		$this->is_sorted = true;

		return $this;
	}

	public function get(): array
	{
		$parts = explode( $this->delimiter, $this->csv );

		$result = [];
		foreach ( $parts as $part )
		{
			$trimmed = trim( $part );
			if ( strlen( $trimmed ) > 0 )
			{
				if ( $this->is_to_lower )
				{
					$trimmed = strtolower( $trimmed );
				}

				if ( $this->is_valid( $trimmed ) )
				{
					$result[] = $trimmed;
				}
			}
		}

		if ( $this->is_sorted )
		{
			sort( $result );
		}

		return $result;
	}

	private function filter_var( ?int $filter_var ): self
	{
		$this->filter_var = $filter_var;

		return $this;
	}

	private function is_valid( ?string $str ): bool
	{
		if ( is_null( $str ) || strlen( $str ) == 0 )
		{
			return false;
		}

		if ( is_null( $this->filter_var ) )
		{
			return true;
		}

		$local_filter_var = $this->filter_var;
		if ( $local_filter_var == FILTER_VALIDATE_DOMAIN )
		{
			// FILTER_VALIDATE_DOMAIN does not work as expected.
			//
			return checkdnsrr( $str, 'A' );
		}

		if ( $local_filter_var == FILTER_VALIDATE_URL )
		{
			// FILTER_VALIDATE_URL does not work as expected either.
			//  It allows things like 'http://xxxx' with no TLD.
			//  Enforce that there is a TLD (at least one '.' internal)
			//
			if ( ! str_contains( $str, '.' ) || $str[ strlen( $str ) - 1 ] == '.' or $str[ 0 ] == '.' )
			{
				return false;
			}
		}

		if ( filter_var( $str, $local_filter_var ) === false )
		{
			return false;
		}

		return true;
	}
}
