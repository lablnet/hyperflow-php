<?php

namespace HyperFlow\Contracts;

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
