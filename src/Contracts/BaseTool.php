<?php

namespace HyperFlow\Contracts;

abstract class BaseTool
{
    /**
     * @return string The name of the tool.
     */
    abstract public function getName(): string;

    /**
     * @return string A description of what the tool does.
     */
    abstract public function getDescription(): string;
    
    /**
     * Define the parameters schema for the tool.
     * Return a valid JSON Schema array structure.
     * 
     * @return array
     */
    abstract public function getParameters(): array;
    
    /**
     * Execute the tool with the given arguments.
     * 
     * @param array $args
     * @return string The tool output.
     */
    abstract public function _run(array $args): string;
}
