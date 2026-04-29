<?php

namespace HyperFlow\Agent;

class LlmConfig
{
    public function __construct(
        public readonly string $model = 'gpt-4o',
        public readonly float $temperature = 0.7,
        public readonly ?string $apiKey = null
    ) {}
}
