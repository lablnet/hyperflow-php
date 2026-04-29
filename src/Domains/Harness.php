<?php

namespace HyperFlow\Domains;

use HyperFlow\Agent\AgentSystem;

class Harness
{
    /**
     * @param DomainTask[] $tasks
     */
    public static function runEvaluations(AgentSystem $agent, Domain $domain, array $tasks): array
    {
        $results = [];
        foreach ($tasks as $task) {
            try {
                $output = $agent->forward(['task' => $task->getInstruction()]);
                $result = $domain->evaluate($task, $output);
                $results[] = [
                    'task_id' => $task->getId(),
                    'output' => $output,
                    'result' => $result
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'task_id' => $task->getId(),
                    'error' => $e->getMessage(),
                    'result' => new EvalResult(false, 0.0, 'Error: ' . $e->getMessage())
                ];
            }
        }
        return $results;
    }
}
