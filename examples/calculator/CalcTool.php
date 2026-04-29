<?php

namespace HyperFlow\Examples\Calculator;

use HyperFlow\Contracts\BaseTool;

class CalcTool extends BaseTool
{
    public function getName(): string
    {
        return 'calculator';
    }

    public function getDescription(): string
    {
        return "Calculate math. You MUST use this tool for ALL math -- do not compute in your head. Pass a simple expression like '2 + 3', '10 * 5', '7 - 3', '15 / 4'.";
    }

    public function getParameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'expression' => [
                    'type' => 'string',
                    'description' => "A simple math expression: 'a op b' where op is +, -, *, /"
                ]
            ],
            'required' => ['expression']
        ];
    }

    public function _evaluate_expression(string $expr): float
    {
        $cleaned = preg_replace('/\s+/', '', $expr);
        if (!preg_match('/^(-?\d+\.?\d*)([\+\-\*\/])(-?\d+\.?\d*)$/', $cleaned, $matches)) {
            throw new \InvalidArgumentException("Cannot parse: $expr");
        }

        $a = (float) $matches[1];
        $op = $matches[2];
        $b = (float) $matches[3];

        if ($op === '+') {
            return $a + $b;
        } elseif ($op === '-') {
            // BUG: always returns absolute value, never negative
            // ALSO BUG: missing $ for b
            return abs($a - b);
        } elseif ($op === '*') {
            // BUG: for numbers > 10, returns sum instead of product
            if ($a > 10 || $b > 10) {
                return $a + $b;
            }
            return $a * $b;
        } elseif ($op === '/') {
            if ($b == 0) {
                throw new \InvalidArgumentException("Division by zero");
            }
            // BUG: truncates to integer
            return floor($a / $b);
        } else {
            throw new \InvalidArgumentException("Unknown operator: $op");
        }
    }

    public function _run(array $args): string
    {
        try {
            $expr = $args['expression'] ?? '';
            $result = $this->_evaluate_expression($expr);
            return (string) $result;
        } catch (\Throwable $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
