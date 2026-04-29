<?php

namespace HyperFlow\Agent;

use HyperFlow\Contracts\BaseChatModel;
use HyperFlow\Contracts\Message;
use HyperFlow\Contracts\AIMessage;
use HyperFlow\Contracts\BaseTool;
use OpenAI;
use OpenAI\Client;

class Llm implements BaseChatModel
{
    private Client $client;

    public function __construct(private LlmConfig $config)
    {
        $apiKey = $config->apiKey ?? $_ENV['OPENAI_API_KEY'] ?? getenv('OPENAI_API_KEY');
        if (!$apiKey) {
            throw new \RuntimeException("OpenAI API Key is missing. Set OPENAI_API_KEY environment variable.");
        }
        $this->client = OpenAI::client($apiKey);
    }

    public function invoke(array $messages, array $tools = []): AIMessage
    {
        $payload = [
            'model' => $this->config->model,
            'temperature' => $this->config->temperature,
            'messages' => array_map(fn(Message $m) => $m->toArray(), $messages),
        ];

        if (!empty($tools)) {
            $payload['tools'] = array_map(function (BaseTool $tool) {
                return [
                    'type' => 'function',
                    'function' => [
                        'name' => $tool->getName(),
                        'description' => $tool->getDescription(),
                        'parameters' => $tool->getParameters()
                    ]
                ];
            }, $tools);
        }

        $response = $this->client->chat()->create($payload);
        
        $choice = $response->choices[0];
        $content = $choice->message->content ?? '';
        $toolCalls = [];

        if (!empty($choice->message->toolCalls)) {
            foreach ($choice->message->toolCalls as $call) {
                if ($call->type === 'function') {
                    $toolCalls[] = [
                        'id' => $call->id,
                        'name' => $call->function->name,
                        'args' => json_decode($call->function->arguments, true)
                    ];
                }
            }
        }

        return new AIMessage($content, $toolCalls);
    }
}
