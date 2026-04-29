<?php

namespace HyperFlow\Contracts;

interface BaseChatModel
{
    /**
     * Invoke the chat model with messages and optional tools.
     * 
     * @param Message[] $messages
     * @param BaseTool[] $tools
     * @return AIMessage
     */
    public function invoke(array $messages, array $tools = []): AIMessage;
}
