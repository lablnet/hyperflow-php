<?php

namespace HyperFlow\Contracts;

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
            $data['tool_calls'] = array_map(function ($call) {
                return [
                    'id' => $call['id'],
                    'type' => 'function',
                    'function' => [
                        'name' => $call['name'],
                        'arguments' => json_encode($call['args'])
                    ]
                ];
            }, $this->toolCalls);
        }
        return $data;
    }
}
