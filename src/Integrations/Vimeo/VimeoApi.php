<?php

namespace Cravens\Php\Integrations\Vimeo;

use Cravens\Php\Utilities\GenericResult;
use stdClass;
use Vimeo\Vimeo;

class VimeoApi
{
	private string $client_id;
	private string $client_secret;
	private string $access_token;
	private Vimeo  $client;

	public function __construct( string $client_id, string $client_secret, string $access_token )
	{
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->access_token  = $access_token;

		$this->client = new Vimeo( $this->client_id, $this->client_secret, $this->access_token );
	}

	public function request( $url, $params = array(), $method = 'GET', $json_body = true, array $headers = array() ): GenericResult
	{
		try
		{
			$response = $this->client->request( $url, $params, $method, $json_body, $headers );

			return GenericResult::data( (object) $response );
		}
		catch( \Exception $e )
		{
			return GenericResult::error( $e->getMessage() );
		}
	}

	public function start_tus_upload( string $title, string $description, int $file_size ): GenericResult
	{
		try
		{
			$response = $this->client->request( '/me/videos', [
				'name'        => $title,
				'description' => $description,
				'privacy'     => [
					'view'  => 'nobody', // Make the video private
					'embed' => 'whitelist', // Restrict embedding to specific domains
				],
				'upload'      => [
					'approach' => 'tus',
					'size'     => $file_size,
				]
			],                                  'POST' );

			if ( ! isset( $response[ 'body' ][ 'uri' ] ) || ! isset( $response[ 'body' ][ 'upload' ][ 'upload_link' ] ) )
			{
				GenericResult::error( 'Unexpected response (no upload or upload_link).' );
			}

			$obj              = new stdClass();
			$obj->uri         = $response[ 'body' ][ 'uri' ];
			$obj->upload_link = $response[ 'body' ][ 'upload' ][ 'upload_link' ];

			return GenericResult::data( $obj );
		}
		catch( \Exception $e )
		{
			return GenericResult::error( $e->getMessage() );
		}
	}

	public function upload_video( string $file_path, string $title, string $description = null, array $allowed_domains = [] ): GenericResult
	{
		$result = new GenericResult();
		try
		{
			$response = $this->client->upload( $file_path, [
				'name'        => $title,
				'description' => $description,
				'privacy'     => [
					'view'  => 'nobody', // Make the video private
					'embed' => 'whitelist', // Restrict embedding to specific domains
				],
				//                'embed'       => [
				//                    'whitelist' => $allowed_domains, // Add allowed domains
				//                ],
			] );

			$result->data = $response;
		}
		catch( \Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function get_video_details( string $video_uri ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			if ( ! str_starts_with( $video_uri, '/videos/' ) )
			{
				$result->is_error = true;
				$result->error    = "Invalid video URI. It must start with '/videos/'.";

				return $result;
			}

			$response = $this->client->request( $video_uri );

			$result->data = (object) $response[ 'body' ];
		}
		catch( \Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function get_picture( string $video_uri ): GenericResult
	{
		$details_result = $this->get_video_details( $video_uri );
		if ( $details_result->is_error )
		{
			return $details_result;
		}

		$details = $details_result->data;

		if ( empty( $details->pictures->sizes ) )
		{
			return GenericResult::error( 'No images available for this video.' );
		}

		$num_pictures              = count( $details->pictures->sizes );
		$obj                       = new stdClass();
		$obj->url                  = $details->pictures->sizes[ $num_pictures - 1 ]->link;
		$obj->url_with_play_button = $details->pictures->sizes[ $num_pictures - 1 ]->link_with_play_button;

		return GenericResult::data( $obj );
	}

	public function update_video( string $video_uri, string $title, string $description ): GenericResult
	{
		$result = new GenericResult();

		try
		{
			if ( ! str_starts_with( $video_uri, '/videos/' ) )
			{
				$result->is_error = true;
				$result->error    = "Invalid video URI. It must start with '/videos/'.";

				return $result;
			}

			$data = [
				'name'        => $title,
				'description' => $description,
			];

			$response = $this->client->request( $video_uri, $data, 'PATCH' );

			$result->data = (object) $response[ 'body' ]; // Return updated video details
		}
		catch( \Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function replace_video( string $video_uri, string $file_path ): GenericResult
	{
		$result = new GenericResult();
		try
		{
			if ( ! str_starts_with( $video_uri, '/videos/' ) )
			{
				$result->is_error = true;
				$result->error    = "Invalid video URI. Expected format: /videos/{video_id}";

				return $result;
			}

			// Replace the video file
			$response = $this->client->replace( $video_uri, $file_path );

			$result->data = $response;
		}
		catch( \Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function delete_video( string $video_uri ): GenericResult
	{
		$result = new GenericResult();
		try
		{
			if ( ! str_starts_with( $video_uri, '/videos/' ) )
			{
				$result->is_error = true;
				$result->error    = "Invalid video URI. It must start with '/videos/'.";

				return $result;
			}

			$response = $this->client->request( $video_uri, [], 'DELETE' );

			$result->data = (object) $response;
		}
		catch( \Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}

	public function list_videos( int $page_size = 10, int $page = 1 ): GenericResult
	{
		$result = new GenericResult();
		try
		{
			$response     = $this->client->request( '/me/videos', [
				'per_page' => $page_size,
				'page'     => $page,
			],                                      'GET' );
			$result->data = (object) $response[ 'body' ] ?? [];
		}
		catch( \Exception $e )
		{
			$result->is_error = true;
			$result->error    = $e->getMessage();
		}

		return $result;
	}
}
