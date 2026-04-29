<?php

namespace HyperFlow\Tools;

use HyperFlow\Contracts\BaseTool;

class BashTool extends BaseTool
{
    public function getName(): string
    {
        return 'bash';
    }

    public function getDescription(): string
    {
        return 'Run bash commands in the workspace. Use this to execute code, run tests, or manage files. Returns stdout and stderr.';
    }

    public function getParameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'command' => [
                    'type' => 'string',
                    'description' => 'The bash command to run.'
                ]
            ],
            'required' => ['command']
        ];
    }

    public function _run(array $args): string
    {
        $command = $args['command'] ?? '';
        if (empty($command)) {
            return "Error: Command cannot be empty.";
        }

        $process = proc_open($command, [
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ], $pipes);

        if (is_resource($process)) {
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $return_value = proc_close($process);

            $output = "Exit Code: $return_value\n";
            if (!empty($stdout)) $output .= "STDOUT:\n$stdout\n";
            if (!empty($stderr)) $output .= "STDERR:\n$stderr\n";

            return $output;
        }

        return "Error: Failed to execute command.";
    }
}
