<?php

namespace HyperFlow\Agent;

use HyperFlow\Contracts\SystemMessage;
use HyperFlow\Contracts\HumanMessage;

class TaskAgent extends AgentSystem
{
    public function forward(array $args = []): string
    {
        $task = $args['task'] ?? '';
        $systemPrompt = $args['system_prompt'] ?? 'You are a helpful AI assistant.';

        $messages = [
            new SystemMessage($systemPrompt),
            new HumanMessage($task)
        ];

        $executor = new LlmWithTools(
            $this->llm,
            $this->toolRegistry,
            $args['max_steps'] ?? 10,
            $this->logger
        );

        $history = $executor->run($messages);

        $lastMessage = end($history);
        return $lastMessage->content;
    }
}
