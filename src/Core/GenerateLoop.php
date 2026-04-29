<?php

namespace HyperFlow\Core;

use HyperFlow\Agent\MetaAgent;
use HyperFlow\Agent\TaskAgent;
use HyperFlow\Agent\AgentOptions;
use HyperFlow\Domains\Domain;
use HyperFlow\Domains\Harness;
use HyperFlow\Utils\Archive;

class GenerateLoop
{
    public function __construct(
        private MetaAgent $metaAgent,
        private string $archivePath,
        private int $generations = 5
    ) {}

    public function run(Domain $domain, array $tasks): void
    {
        for ($i = 0; $i < $this->generations; $i++) {
            echo "Generation " . ($i + 1) . PHP_EOL;
            
            $history = Archive::readAll($this->archivePath);
            $context = "History of evaluations:\n" . json_encode(array_slice($history, -5)); // limit history

            // Meta agent modifies the workspace
            $this->metaAgent->forward([
                'evaluation_context' => "Please improve the TaskAgent. Here is the evaluation history:\n" . $context
            ]);

            // Test the new TaskAgent
            $taskAgent = new TaskAgent(new AgentOptions());
            $results = Harness::runEvaluations($taskAgent, $domain, $tasks);

            $avgScore = 0;
            foreach ($results as $res) {
                $avgScore += $res['result']->score;
            }
            if (count($results) > 0) {
                $avgScore /= count($results);
            }

            echo "Generation " . ($i + 1) . " Score: $avgScore\n";

            Archive::append($this->archivePath, [
                'generation' => $i + 1,
                'score' => $avgScore,
                'results' => array_map(function($r) {
                    if (isset($r['result'])) {
                        return [
                            'success' => $r['result']->success,
                            'score' => $r['result']->score,
                            'feedback' => $r['result']->feedback
                        ];
                    }
                    return $r;
                }, $results)
            ]);
        }
    }
}
