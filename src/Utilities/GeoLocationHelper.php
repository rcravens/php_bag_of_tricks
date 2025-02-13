<?php

namespace Cravens\Php\Utilities;

use Carbon\Carbon;
use Cravens\Php\Traits\CachableTrait;

class GeoLocationHelper
{
	use CachableTrait;

	private const GOOGLE_API_BASE_URL = 'https://maps.googleapis.com/maps/api';
	private string $api_key;

	public function __construct( $google_maps_api_key )
	{
		$this->api_key = $google_maps_api_key;
	}

	public function get_address( $lat, $lng ): ?\stdClass
	{
		return $this->get_address_google_api( $lat, $lng );
	}

	public function get_lat_lng( $street1, $city, $state, $zip, $country ): ?\stdClass
	{
		return $this->get_lat_lng_google_api( $street1, $city, $state, $zip, $country );
	}

	public function get_directions( $from_address, $to_address ): ?\stdClass
	{
		return $this->get_directions_google_api( $from_address, $to_address );
	}

	private function get_lat_lng_google_api( $street1, $city, $state, $zip, $country ): ?\stdClass
	{
		$address = $street1 . ', ' . $city . ' ' . $state . ' ' . $country;

		$key   = 'get_lat_lng_google_api|' . $address;
		$value = self::cache_get_value( $key );
		if ( is_null( $value ) )
		{
			try
			{
				$url      = self::GOOGLE_API_BASE_URL . '/geocode/json?address=' . urlencode( $address ) . '&key=' . $this->api_key;
				$response = file_get_contents( $url );
				$data     = json_decode( $response, true );

				if ( ! is_null( $data ) && $data[ 'status' ] == 'OK' && count( $data[ 'results' ] ) > 0 )
				{
					$value            = new \stdClass();
					$value->latitude  = $data[ 'results' ][ 0 ][ 'geometry' ][ 'location' ][ 'lat' ];
					$value->longitude = $data[ 'results' ][ 0 ][ 'geometry' ][ 'location' ][ 'lng' ];

					self::cache_set_value( $key, $value, Carbon::now()->addMinutes( 60 ) );
				}
			}
			catch( \Exception $e )
			{
//                dd( $e );
			}
		}

		return $value;
	}

	private function get_address_google_api( $lat, $lng ): ?\stdClass
	{
		$key   = 'address_from_lat_lng|' . $lat . '|' . $lng;
		$value = self::cache_get_value( $key );
		if ( is_null( $value ) )
		{
			try
			{
				$url      = self::GOOGLE_API_BASE_URL . '/geocode/json?latlng=' . $lat . ',' . $lng . '&key=' . $this->api_key;
				$response = file_get_contents( $url );
				$data     = json_decode( $response, false );

				if ( ! is_null( $data ) && $data->status == 'OK' && count( $data->results ) > 0 )
				{
					$address_components = $data->results[ 0 ]->address_components;

					$value                = new \stdClass();
					$value->street_number = '';
					$value->street        = '';
					$value->city          = '';
					$value->state         = '';
					$value->state_code    = '';
					$value->postal_code   = '';
					$value->country       = '';
					$value->county        = '';
					$value->country_code  = '';

					foreach ( $address_components as $address_component )
					{
						$types = $address_component->types;
						if ( in_array( 'street_number', $types ) )
						{
							$value->street_number = $address_component->long_name;
						}
						if ( in_array( 'route', $types ) )
						{
							$value->street = $address_component->long_name;
						}
						if ( in_array( 'locality', $types ) )
						{
							$value->city = $address_component->long_name;
						}
						if ( in_array( 'administrative_area_level_1', $types ) )
						{
							$value->state      = $address_component->long_name;
							$value->state_code = $address_component->short_name;
						}
						if ( in_array( 'postal_code', $types ) )
						{
							$value->postal_code = $address_component->long_name;
						}
						if ( in_array( 'country', $types ) )
						{
							$value->country      = $address_component->long_name;
							$value->country_code = $address_component->short_name;
						}
						if ( in_array( 'administrative_area_level_2', $types ) )
						{
							$value->county = $address_component->long_name;
						}
					}

					self::cache_set_value( $key, $value, Carbon::now()->addMinutes( 60 ) );
				}
			}
			catch( \Exception $e )
			{
				//dd( $e );
			}
		}

		return $value;
	}

	private function get_directions_google_api( $from_address, $to_address ): ?\stdClass
	{
		$value = null;

		$key   = 'get_directions_google_api|' . $from_address . '|' . $to_address;
		$value = self::cache_get_value( $key );

		if ( is_null( $value ) )
		{
			try
			{
				$url      = self::GOOGLE_API_BASE_URL . '/directions/json?destination=' . urlencode( $to_address ) . '&origin=' . urlencode( $from_address ) . '&key=' . $this->api_key;
				$response = file_get_contents( $url );
				$data     = json_decode( $response, true );

				if ( ! is_null( $data ) && $data[ 'status' ] = 'OK' && count( $data[ 'routes' ] ) > 0 && count( $data[ 'routes' ][ 0 ][ 'legs' ] ) > 0 )
				{
					$value                         = new \stdClass();
					$value->summary                = new \stdClass();
					$value->summary->distance      = $data[ 'routes' ][ 0 ][ 'legs' ][ 0 ][ 'distance' ][ 'text' ];
					$value->summary->duration      = $data[ 'routes' ][ 0 ][ 'legs' ][ 0 ][ 'duration' ][ 'text' ];
					$value->summary->from_location = $data[ 'routes' ][ 0 ][ 'legs' ][ 0 ][ 'start_address' ];
					$value->summary->to_location   = $data[ 'routes' ][ 0 ][ 'legs' ][ 0 ][ 'end_address' ];

					$value->steps = [];
					foreach ( $data[ 'routes' ][ 0 ][ 'legs' ][ 0 ][ 'steps' ] as $step )
					{
						$obj            = new \stdClass();
						$obj->html      = $step[ 'html_instructions' ];
						$obj->distance  = $step[ 'distance' ][ 'text' ];
						$obj->duration  = $step[ 'duration' ][ 'text' ];
						$obj->start_lat = $step[ 'start_location' ][ 'lat' ];
						$obj->start_lng = $step[ 'start_location' ][ 'lng' ];
						$obj->end_lat   = $step[ 'end_location' ][ 'lat' ];
						$obj->end_lng   = $step[ 'end_location' ][ 'lng' ];

						$value->steps[] = $obj;
					}

					self::cache_set_value( $key, $value, Carbon::now()->addMinutes( 60 ) );
				}
			}
			catch( \Exception $e )
			{
				//dd( $e );
			}
		}

		return $value;
	}
}
