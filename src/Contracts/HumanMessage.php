<?php

namespace HyperFlow\Contracts;

class HumanMessage extends Message
{
    public function getType(): string { return 'user'; }
}
