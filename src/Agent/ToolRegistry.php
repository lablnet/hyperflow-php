<?php

namespace HyperFlow\Agent;

use HyperFlow\Contracts\BaseTool;

class ToolRegistry
{
    /** @var BaseTool[] */
    private array $tools = [];

    public function register(BaseTool $tool): void
    {
        $this->tools[$tool->getName()] = $tool;
    }

    /**
     * @param BaseTool[] $tools
     */
    public function registerMany(array $tools): void
    {
        foreach ($tools as $tool) {
            $this->register($tool);
        }
    }

    public function get(string $name): ?BaseTool
    {
        return $this->tools[$name] ?? null;
    }

    /**
     * @return BaseTool[]
     */
    public function getAll(): array
    {
        return array_values($this->tools);
    }
}
