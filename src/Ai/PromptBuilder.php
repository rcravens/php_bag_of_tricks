<?php

namespace Cravens\Php\Ai;

class PromptBuilder
{
	private array $messages;

	public function add_system_message( string $message ): void
	{
		$this->add_message( 'system', $message );
	}

	public function add_user_message( string $message ): void
	{
		$this->add_message( 'user', $message );
	}

	public function add_message( string $role, string $message ): void
	{
		$message          = [ 'role' => $role, 'content' => $message ];
		$this->messages[] = $message;
	}

	public function messages(): array
	{
		return $this->messages;
	}
}
