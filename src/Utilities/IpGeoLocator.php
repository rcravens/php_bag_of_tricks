<?php

namespace Cravens\Php\Utilities;

class IpGeoLocator
{
	public ?string $ip_address                = null;
	public ?string $continent_name            = null;
	public ?string $country_code2             = null;
	public ?string $country_code3             = null;
	public ?string $country_name              = null;
	public ?string $country_capital           = null;
	public ?string $state_prov                = null;
	public ?string $city                      = null;
	public ?string $postal_code               = null;
	public ?float  $latitude                  = null;
	public ?float  $longitude                 = null;
	public bool    $is_eu                     = false;
	public ?string $calling_code              = null;
	public ?string $country_tld               = null;
	public ?string $languages                 = null;
	public ?string $country_flag_url          = null;
	public ?string $country_emoji             = null;
	public ?string $currency_code             = null;
	public ?string $currency_name             = null;
	public ?string $currency_symbol           = null;
	public ?string $time_zone_name            = null;
	public ?float  $time_zone_offset          = null;
	public ?float  $time_zone_offset_with_dst = null;
	public ?string $current_time              = null;
	public ?float  $current_timestamp         = null;
	public ?bool   $is_dst                    = false;

	private string $api_key;

	public function __construct( string $ip_geolocation_api_key, $ip_address = null )
	{
		$this->api_key = $ip_geolocation_api_key;

		$client_ip_address = ! is_null( $ip_address ) ? $ip_address : ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ? $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] : $_SERVER[ 'REMOTE_ADDR' ] );

		$this->ip_address = $this->is_public_ip_address_range( $client_ip_address ) ? $client_ip_address : '75.100.3.111';

		$this->geolocate_ip_address();
	}


	public function address(): string
	{
		return $this->city . ', ' . $this->state_prov . ',' . $this->country_name;
	}

	private function geolocate_ip_address(): void
	{
		// Cache geo-location data to reduce API calls
		//
		$key   = 'ip_geo_data_' . $this->ip_address;
		$value = null;
		if ( function_exists( 'session' ) )
		{
			$value = session()->has( $key ) ? session()->get( $key ) : null;
		}

		if ( is_null( $value ) )
		{
			if ( ! is_null( $this->api_key ) )
			{
				$url = 'https://api.ipgeolocation.io/ipgeo?apiKey=' . $this->api_key . '&ip=' . $this->ip_address;

				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
				$value = curl_exec( $ch );
				curl_close( $ch );

				if ( function_exists( 'session' ) )
				{
					session()->put( $key, $value );
				}
			}
		}

		if ( $value )
		{
			$data = json_decode( $value );
			if ( ! isset( $data->continent_name ) )
			{
				$this->fake_data();

				return;
			}

			$this->continent_name            = $data->continent_name;
			$this->country_code2             = $data->country_code2;
			$this->country_code3             = $data->country_code3;
			$this->country_name              = $data->country_name;
			$this->country_capital           = $data->country_capital;
			$this->state_prov                = $data->state_prov;
			$this->city                      = $data->city;
			$this->postal_code               = $data->zipcode;
			$this->latitude                  = $data->latitude;
			$this->longitude                 = $data->longitude;
			$this->is_eu                     = $data->is_eu;
			$this->calling_code              = $data->calling_code;
			$this->country_tld               = $data->country_tld;
			$this->languages                 = $data->languages;
			$this->country_flag_url          = $data->country_flag;
			$this->country_emoji             = $data->country_emoji;
			$this->currency_code             = $data->currency->code;
			$this->currency_name             = $data->currency->name;
			$this->currency_symbol           = $data->currency->symbol;
			$this->time_zone_name            = $data->time_zone->name;
			$this->time_zone_offset          = $data->time_zone->offset;
			$this->time_zone_offset_with_dst = $data->time_zone->offset_with_dst;
			$this->current_time              = $data->time_zone->current_time;
			$this->current_timestamp         = $data->time_zone->current_time_unix;
			$this->is_dst                    = $data->time_zone->is_dst;
		}
	}

	private function fake_data(): void
	{
		$this->continent_name            = 'North America';
		$this->country_code2             = 'US';
		$this->country_code3             = 'USA';
		$this->country_name              = 'United States';
		$this->country_capital           = 'Washington, D.C.';
		$this->state_prov                = 'Wisconsin';
		$this->city                      = 'Madison';
		$this->postal_code               = '53715';
		$this->latitude                  = '43.07217';
		$this->longitude                 = '-89.40075';
		$this->is_eu                     = 'No';
		$this->calling_code              = '+1';
		$this->country_tld               = '.us';
		$this->languages                 = 'en-US,es-US,haw,fr';
		$this->country_flag_url          = 'https://ipgeolocation.io/static/flags/us_64.png';
		$this->country_emoji             = '';
		$this->currency_code             = 'USD';
		$this->currency_name             = 'US Dollar';
		$this->currency_symbol           = '$';
		$this->time_zone_name            = 'America/Chicago';
		$this->time_zone_offset          = '-6';
		$this->time_zone_offset_with_dst = '-5';
		$this->current_time              = '2024-10-06 17:08:51.480-0500';
		$this->current_timestamp         = '1728252531.48';
		$this->is_dst                    = 'Yes';
	}

	private function is_public_ip_address_range( $ip_address ): bool
	{
		if ( ! filter_var( $ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) )
		{
			// IP address is private
			return false;
		}

		return true;
	}
}
