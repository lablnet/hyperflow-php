<?php

namespace HyperFlow\Contracts;

abstract class Message
{
    public function __construct(public readonly string $content) {}

    abstract public function getType(): string;
    
    public function toArray(): array
    {
        return [
            'role' => $this->getType(),
            'content' => $this->content,
        ];
    }
}
