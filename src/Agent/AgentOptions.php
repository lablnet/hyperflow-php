<?php

namespace HyperFlow\Agent;

use HyperFlow\Contracts\BaseChatModel;
use HyperFlow\Contracts\BaseTool;

class AgentOptions
{
    /**
     * @param BaseTool[]|null $tools
     */
    public function __construct(
        public ?string $model = null,
        public ?LlmConfig $llmConfig = null,
        public ?BaseChatModel $llm = null,
        public ?array $tools = null,
        public ?string $logFile = null,
        public ?string $promptFile = null
    ) {}
}
