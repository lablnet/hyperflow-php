<?php

namespace HyperFlow\Agent;

abstract class AgentSystem
{
    protected string $model;
    protected \HyperFlow\Contracts\BaseChatModel $llm;
    protected ToolRegistry $toolRegistry;
    protected \Closure $logger;

    public function __construct(?AgentOptions $options = null)
    {
        $opts = $options ?? new AgentOptions();
        $this->model = $opts->model ?? 'gpt-4o';
        $this->llm = $opts->llm ?? new Llm($opts->llmConfig ?? new LlmConfig($this->model));
        $this->toolRegistry = new ToolRegistry();
        
        if ($opts->tools) {
            $this->toolRegistry->registerMany($opts->tools);
        }

        $this->logger = $this->createLogger($opts->logFile);
    }

    private function createLogger(?string $logFile): \Closure
    {
        if (!$logFile) {
            return function (string $msg) {
                echo "[HyperFlow] " . $msg . PHP_EOL;
            };
        }

        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return function (string $msg) use ($logFile) {
            $line = date('Y-m-d\TH:i:sP') . " " . $msg . PHP_EOL;
            file_put_contents($logFile, $line, FILE_APPEND);
            echo "[HyperFlow] " . $msg . PHP_EOL;
        };
    }

    abstract public function forward(array $args = []): mixed;
}
