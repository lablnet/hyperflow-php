<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/CalcTool.php';

use HyperFlow\Agent\AgentOptions;
use HyperFlow\Agent\TaskAgent;
use HyperFlow\Agent\MetaAgent;
use HyperFlow\Tools\EditorTool;
use HyperFlow\Examples\Calculator\CalcTool;

$tasks = json_decode(file_get_contents(__DIR__ . '/tasks.json'), true);

$options = new AgentOptions(
    tools: [new CalcTool()],
    model: 'gpt-4o'
);

$agent = new TaskAgent($options);

echo "=== Generation: Evaluating current CalcTool ===\n";
$correct = 0;
$details = [];

foreach ($tasks as $task) {
    $prompt = "You MUST use the calculator tool for this math problem. Do NOT compute the answer yourself.\n" .
              "Call the calculator tool, then return exactly what it gives you.\n\n" .
              "Problem: " . $task['description'] . "\n\n" .
              'Respond with ONLY JSON: { "response": "<the number the calculator returned>" }';

    $output = $agent->forward(['task' => $prompt]);
    
    // Extact response
    $prediction = "error";
    if (preg_match('/"response"\s*:\s*"?([^"}]+)"?/', $output, $matches)) {
        $prediction = trim($matches[1]);
    } else if (preg_match('/-?\d+\.?\d*/', $output, $matches)) {
        $prediction = $matches[0];
    }

    $is_correct = ($prediction == $task['expected']) || (floatval($prediction) == floatval($task['expected']));
    if ($is_correct) $correct++;
    
    $icon = $is_correct ? "PASS" : "FAIL";
    $line = "  [$icon] {$task['id']}: '{$task['description']}' -> $prediction (expected: {$task['expected']})";
    echo $line . "\n";
    $details[] = $line;
}

$score = count($tasks) > 0 ? $correct / count($tasks) : 0;
echo "\nScore: " . number_format($score, 2) . " ($correct/" . count($tasks) . ")\n\n";

if ($score < 1.0) {
    echo "=== MetaAgent fixing CalcTool ===\n";
    $metaOptions = new AgentOptions(
        tools: [new EditorTool()],
        model: 'gpt-4o'
    );
    $metaAgent = new MetaAgent($metaOptions);

    $evalContext = "The calculator tool has bugs. Here are the results:\n" . implode("\n", $details) . "\n\n" .
                   "Please use the EditorTool to fix the bugs in " . __DIR__ . "/CalcTool.php.\n" .
                   "Known bugs:\n" .
                   "- Subtraction returns abs() instead of allowing negatives\n" .
                   "- Multiplication returns a+b instead of a*b when numbers > 10\n" .
                   "- Division uses floor() which truncates decimals\n" .
                   "Overwrite the file with the corrected code.";
    
    $metaAgent->forward(['evaluation_context' => $evalContext]);

    echo "MetaAgent has finished. Please run this script again to see the improved score!\n";
} else {
    echo "All tasks passed! No improvement needed.\n";
}
