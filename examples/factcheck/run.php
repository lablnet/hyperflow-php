<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/Domain.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->safeLoad();

use HyperFlow\Agent\AgentOptions;
use HyperFlow\Agent\TaskAgent;
use HyperFlow\Agent\MetaAgent;
use HyperFlow\Tools\EditorTool;
use HyperFlow\Examples\Factcheck\FactcheckTask;
use HyperFlow\Examples\Factcheck\FactcheckDomain;
use HyperFlow\Core\GenerateLoop;

$tasksData = json_decode(file_get_contents(__DIR__ . '/tasks.json'), true);
$tasks = [];
foreach ($tasksData as $i => $task) {
    $tasks[] = new FactcheckTask((string)$i, $task['claim'], $task['label'] === 'True');
}

$taskOptions = new AgentOptions(model: 'gpt-4o-mini');
$metaOptions = new AgentOptions(tools: [new EditorTool()], model: 'gpt-4o');

$domain = new FactcheckDomain();
$metaAgent = new MetaAgent($metaOptions);
$archivePath = __DIR__ . '/../../outputs/factcheck_eval/archive.jsonl';

echo "=== HyperFlow: Factcheck Evolution Demo ===\n";
echo "The TaskAgent is evaluating claims. The MetaAgent will modify the system prompts to improve accuracy.\n";

$loop = new GenerateLoop($metaAgent, $archivePath, 2); // Run 2 generations
$loop->run($domain, $tasks);

echo "Evolution complete. Check $archivePath for results.\n";
