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

class SystemMessage extends Message
{
    public function getType(): string { return 'system'; }
}

class HumanMessage extends Message
{
    public function getType(): string { return 'user'; }
}

class AIMessage extends Message
{
    public function __construct(
        string $content,
        public readonly array $toolCalls = []
    ) {
        parent::__construct($content);
    }
    
    public function getType(): string { return 'assistant'; }
    
    public function toArray(): array
    {
        $data = parent::toArray();
        if (!empty($this->toolCalls)) {
            $data['tool_calls'] = $this->toolCalls;
        }
        return $data;
    }
}

class ToolMessage extends Message
{
    public function __construct(
        string $content,
        public readonly string $toolCallId
    ) {
        parent::__construct($content);
    }

    public function getType(): string { return 'tool'; }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['tool_call_id'] = $this->toolCallId;
        return $data;
    }
}
