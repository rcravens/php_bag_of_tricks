<?php

namespace Cravens\Php\Utilities;

use Carbon\Carbon;
use Exception;

class WeatherHelper
{
	private const BASE_URL = 'https://api.openweathermap.org/data/2.5/';
	private string $api_key;
	private string $language;
	private string $unit;

	public function __construct( string $open_weather_map_api_key, string $language = 'en', bool $is_metric = false )
	{
		$this->api_key  = $open_weather_map_api_key;
		$this->language = $language;
		$this->unit     = $is_metric ? 'metric' : 'imperial';
	}

	public function daily_forecast_by_lat_lng( $lat, $lng )
	{
		$key   = 'weather_forecast|' . $lat . '|' . $lng;
		$value = null;
		if ( function_exists( 'cache' ) )
		{
			$value = cache()->has( $key ) ? cache()->get( $key ) : null;
		}
		if ( is_null( $value ) )
		{
			try
			{
				$url   = self::BASE_URL . 'forecast?lat=' . $lat . '&lon=' . $lng . '&appid=' . $this->api_key . '&units=' . $this->unit . '&lang=' . $this->language;
				$value = file_get_contents( $url );

				if ( function_exists( 'cache' ) )
				{
					cache()->put( $key, $value, Carbon::now()->addMinutes( 60 ) );
				}
			}
			catch( Exception $e )
			{
				// Do nothing
			}
		}

		if ( is_null( $value ) )
		{
			return null;
		}

		$data = json_decode( $value );

		$forecast           = new \stdClass();
		$forecast->location = $data->city->name;
		$forecast->data     = [];
		foreach ( $data->list as $item )
		{
			$date = Carbon::createFromTimestamp( $item->dt );
			$key  = $date->format( 'Y-m-d' );
			if ( ! array_key_exists( $key, $forecast->data ) )
			{
				$obj                      = new \stdClass();
				$obj->date                = $date;
				$obj->temp_min            = PHP_FLOAT_MAX;
				$obj->temp_max            = PHP_FLOAT_MIN;
				$obj->temp_feels_like_min = PHP_FLOAT_MAX;
				$obj->temp_feels_like_max = PHP_FLOAT_MIN;
				$obj->humidity_min        = PHP_FLOAT_MAX;
				$obj->humidity_max        = PHP_FLOAT_MIN;

				$forecast->data[ $key ] = $obj;
			}
			$obj                      = $forecast->data[ $key ];
			$obj->temp_min            = min( $obj->temp_min, $item->main->temp_min );
			$obj->temp_max            = max( $obj->temp_max, $item->main->temp_max );
			$obj->temp_feels_like_min = min( $obj->temp_feels_like_min, $item->main->feels_like );
			$obj->temp_feels_like_max = max( $obj->temp_feels_like_max, $item->main->feels_like );
			$obj->humidity_min        = min( $obj->humidity_min, $item->main->humidity );
			$obj->humidity_max        = max( $obj->humidity_max, $item->main->humidity );
			if ( ! property_exists( $obj, 'weather' ) || $date->hour <= 12 )
			{
				$obj->weather      = $item->weather[ 0 ]->main;
				$obj->weather_icon = 'https://openweathermap.org/img/wn/' . $item->weather[ 0 ]->icon . '@2x.png';
				$obj->wind_speed   = $item->wind->speed;
				$obj->wind_deg     = $item->wind->deg;
				$obj->wind_gust    = $item->wind->gust;
			}
		}

		return $forecast;
	}
}
