<?php

namespace Cravens\Php\Utilities;

class GravatarHelper
{
	private string $default_image_type;
	private string $rating;

	public function __construct( string $default_image_type = 'mp', string $rating = 'g' )
	{
		$this->default_image_type = $default_image_type;
		$this->rating             = $rating;
	}

	public function get_gravatar_url( $email, $size = 64 ): string
	{
		$params = [
			's' => htmlentities( $size ),
			'd' => htmlentities( $this->default_image_type ),
			'r' => htmlentities( $this->rating ),
		];

		// Generate url.
		$base_url = 'https://www.gravatar.com/avatar';
		$hash     = hash( 'sha256', strtolower( trim( $email ) ) );
		$query    = http_build_query( $params );
		$url      = sprintf( '%s/%s?%s', $base_url, $hash, $query );

		return $url;
	}
}
