<?php

namespace Cravens\Php\Ai\Agents;


use Cravens\Php\Ai\LlmInterface;
use Cravens\Php\Ai\PromptBuilder;

abstract class AgentBase implements AgentInterface
{
	private string  $role;
	private string  $background;
	private string  $goal;
	private array   $context;
	private ?string $instructions;
	private ?string $output_format;
	private bool    $is_json;

	public function __construct( string $role, string $background, string $goal, array $context, string $instructions = null, string $output_format = null, bool $is_json = false )
	{
		$this->role          = $role;
		$this->background    = $background;
		$this->goal          = $goal;
		$this->context       = $context;
		$this->instructions  = $instructions;
		$this->output_format = $output_format;
		$this->is_json       = $is_json;
	}

	public function update_context( array $context )
	{
		$this->context = $context;
	}

	public function work( LlmInterface $llm ): string
	{
		$prompt = $this->get_prompt();

		return $llm->get_response( $prompt );
	}

	private function get_prompt(): PromptBuilder
	{
		$prompt = new PromptBuilder();

		$prompt->add_system_message( 'You have the following role: ' . $this->role );
		$prompt->add_system_message( 'You have the following background: ' . $this->background );

		if ( $this->is_json )
		{
			$prompt->add_system_message( 'Your results should be in valid JSON format.' );
		}

		foreach ( $this->context as $key => $value )
		{
			if ( ! is_null( $value ) )
			{
				$prompt->add_user_message( 'Here is the information about the ' . $key . ':' );
				$prompt->add_user_message( $value );
			}
		}

		$prompt->add_user_message( 'You have the following goal: ' . $this->goal );

		if ( ! is_null( $this->instructions ) )
		{
			$prompt->add_user_message( $this->instructions );
		}

		if ( ! is_null( $this->output_format ) )
		{
			$prompt->add_user_message( 'Provide your answer using the following format.' );
			$prompt->add_user_message( 'Do not provide any additional explanations before or after the desired format.' );
			if ( $this->is_json )
			{
				$prompt->add_user_message( 'Only provide a RFC8259 compliant JSON response using the following this format without deviation.' );
			}
			else
			{
				$prompt->add_user_message( 'Here is information about the desired format:' );
			}
			$prompt->add_user_message( $this->output_format );
		}

		return $prompt;
	}
}
