<?php

namespace HyperFlow\Contracts;

class SystemMessage extends Message
{
    public function getType(): string { return 'system'; }
}
