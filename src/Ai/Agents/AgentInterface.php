<?php

namespace Cravens\Php\Ai\Agents;

use Cravens\Php\Ai\LlmInterface;

interface AgentInterface
{
	public function work( LlmInterface $llm ): string;
}
