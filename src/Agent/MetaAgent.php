<?php

namespace HyperFlow\Agent;

use HyperFlow\Contracts\SystemMessage;
use HyperFlow\Contracts\HumanMessage;

class MetaAgent extends AgentSystem
{
    public function forward(array $args = []): string
    {
        $evaluationContext = $args['evaluation_context'] ?? '';
        $systemPrompt = $args['system_prompt'] ?? 'You are a MetaAgent that improves the TaskAgent by modifying its code or prompts based on past evaluations.';

        $messages = [
            new SystemMessage($systemPrompt),
            new HumanMessage($evaluationContext)
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
