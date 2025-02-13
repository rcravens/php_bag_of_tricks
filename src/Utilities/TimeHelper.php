<?php

namespace Cravens\Php\Utilities;

class TimeHelper
{
	public int $days;
	public int $hours;
	public int $minutes;
	public int $seconds;

	public int $total_seconds;

	public function __construct( int $total_seconds )
	{
		$this->total_seconds = $total_seconds;

		$seconds_in_day    = 24 * 60 * 60;
		$this->days        = intval( $this->total_seconds / $seconds_in_day );
		$remaining_seconds = $this->total_seconds - $this->days * $seconds_in_day;

		$seconds_in_hour   = 60 * 60;
		$this->hours       = intval( $remaining_seconds / $seconds_in_hour );
		$remaining_seconds = $remaining_seconds - $this->hours * $seconds_in_hour;

		$seconds_in_minute = 60;
		$this->minutes     = intval( $remaining_seconds / $seconds_in_minute );
		$this->seconds     = $remaining_seconds - $this->minutes * $seconds_in_minute;
	}

	public function to_str(): string
	{
		$parts = [];
		if ( $this->days > 0 )
		{
			$parts[] = $this->days . 'd';
		}
		if ( $this->hours > 0 )
		{
			$parts[] = $this->hours . 'h';
		}
		if ( $this->minutes > 0 )
		{
			$parts[] = $this->minutes . 'm';
		}
		if ( $this->seconds > 0 )
		{
			$parts[] = $this->seconds . 's';
		}

		return count( $parts ) > 0 ? implode( ':', $parts ) : 'none';
	}
}
