<?php

namespace HyperFlow\Examples\Factcheck;

use HyperFlow\Domains\Domain;
use HyperFlow\Domains\DomainTask;
use HyperFlow\Domains\EvalResult;

class FactcheckTask implements DomainTask
{
    public function __construct(private string $id, private string $claim, private bool $expected) {}
    
    public function getId(): string { return $this->id; }
    public function getInstruction(): string 
    {
        return "Is the following claim true or false?\nClaim: " . $this->claim . "\n\n" .
               'Respond with ONLY JSON: { "is_true": <true or false> }';
    }
    
    public function getExpected(): bool { return $this->expected; }
}

class FactcheckDomain implements Domain
{
    public function evaluate(DomainTask $task, string $agentOutput): EvalResult
    {
        if (!$task instanceof FactcheckTask) {
            throw new \InvalidArgumentException("Task must be a FactcheckTask");
        }
        
        $expected = $task->getExpected();
        $prediction = null;
        
        if (preg_match('/"is_true"\s*:\s*(true|false)/i', $agentOutput, $matches)) {
            $prediction = strtolower($matches[1]) === 'true';
        }
        
        if ($prediction === null) {
            return new EvalResult(false, 0.0, "Failed to parse prediction from output.");
        }
        
        $success = ($prediction === $expected);
        return new EvalResult($success, $success ? 1.0 : 0.0, "Evaluated factcheck claim.");
    }
}
