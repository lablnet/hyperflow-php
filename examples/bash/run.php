<?php

require __DIR__ . '/../../vendor/autoload.php';

use HyperFlow\Agent\AgentOptions;
use HyperFlow\Agent\TaskAgent;
use HyperFlow\Tools\BashTool;
use HyperFlow\Domains\Domain;
use HyperFlow\Domains\DomainTask;
use HyperFlow\Domains\EvalResult;
use HyperFlow\Domains\Harness;

class BashTask implements DomainTask
{
    public function __construct(private string $id, private string $instruction) {}
    
    public function getId(): string { return $this->id; }
    public function getInstruction(): string { return $this->instruction; }
}

class BashDomain implements Domain
{
    public function evaluate(DomainTask $task, string $agentOutput): EvalResult
    {
        // Simple dummy evaluator for the example
        $success = strlen($agentOutput) > 0;
        return new EvalResult($success, $success ? 1.0 : 0.0, "Evaluated output.");
    }
}

// 1. Setup the TaskAgent with Bash tool
$options = new AgentOptions(
    tools: [new BashTool()],
    model: 'gpt-4o' // Using default model
);

$agent = new TaskAgent($options);

// 2. Define tasks
$tasks = [
    new BashTask('1', 'List the files in the current directory.'),
    new BashTask('2', 'Create a new file named test.txt with the content "Hello, HyperFlow PHP!"')
];

$domain = new BashDomain();

echo "Running evaluations...\n";
$results = Harness::runEvaluations($agent, $domain, $tasks);

foreach ($results as $res) {
    echo "Task ID: " . $res['task_id'] . "\n";
    echo "Output: " . ($res['output'] ?? $res['error']) . "\n";
    echo "Score: " . $res['result']->score . "\n";
    echo "------------------------\n";
}
