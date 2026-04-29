<?php

namespace HyperFlow\Domains;

interface DomainTask
{
    public function getId(): string;
    public function getInstruction(): string;
}

class EvalResult
{
    public function __construct(
        public readonly bool $success,
        public readonly float $score,
        public readonly string $feedback,
        public readonly array $metrics = []
    ) {}
}

interface Domain
{
    public function evaluate(DomainTask $task, string $agentOutput): EvalResult;
}
