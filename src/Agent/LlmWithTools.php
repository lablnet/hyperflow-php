<?php

namespace HyperFlow\Agent;

use HyperFlow\Contracts\BaseChatModel;
use HyperFlow\Contracts\Message;
use HyperFlow\Contracts\AIMessage;
use HyperFlow\Contracts\ToolMessage;

class LlmWithTools
{
    public function __construct(
        private BaseChatModel $llm,
        private ToolRegistry $toolRegistry,
        private int $maxSteps = 10,
        private ?\Closure $logger = null
    ) {}

    /**
     * @param Message[] $messages
     * @return Message[] Final sequence of messages
     */
    public function run(array $messages): array
    {
        $step = 0;
        $history = $messages;

        while ($step < $this->maxSteps) {
            $this->log("Step " . ($step + 1) . " / " . $this->maxSteps);
            $response = $this->llm->invoke($history, $this->toolRegistry->getAll());
            $history[] = $response;

            if (empty($response->toolCalls)) {
                $this->log("No tool calls, finishing.");
                break;
            }

            foreach ($response->toolCalls as $call) {
                $tool = $this->toolRegistry->get($call['name']);
                if ($tool) {
                    $this->log("Executing tool: " . $call['name']);
                    try {
                        $result = $tool->_run($call['args']);
                        $history[] = new ToolMessage($result, $call['id']);
                    } catch (\Throwable $e) {
                        $this->log("Tool exception: " . $e->getMessage());
                        $history[] = new ToolMessage("Error executing tool: " . $e->getMessage(), $call['id']);
                    }
                } else {
                    $this->log("Tool not found: " . $call['name']);
                    $history[] = new ToolMessage("Error: Tool not found.", $call['id']);
                }
            }
            $step++;
        }

        return $history;
    }

    private function log(string $msg): void
    {
        if ($this->logger) {
            ($this->logger)($msg);
        }
    }
}
