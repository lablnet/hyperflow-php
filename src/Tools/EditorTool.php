<?php

namespace HyperFlow\Tools;

use HyperFlow\Contracts\BaseTool;

class EditorTool extends BaseTool
{
    public function getName(): string
    {
        return 'editor';
    }

    public function getDescription(): string
    {
        return 'A file editor tool. You can read, write, or append to files.';
    }

    public function getParameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => [
                    'type' => 'string',
                    'enum' => ['read', 'write', 'append'],
                    'description' => 'The action to perform.'
                ],
                'path' => [
                    'type' => 'string',
                    'description' => 'The path to the file.'
                ],
                'content' => [
                    'type' => 'string',
                    'description' => 'The content to write or append (ignored for read).'
                ]
            ],
            'required' => ['action', 'path']
        ];
    }

    public function _run(array $args): string
    {
        $action = $args['action'] ?? '';
        $path = $args['path'] ?? '';
        $content = $args['content'] ?? '';

        if (empty($path)) {
            return "Error: Path cannot be empty.";
        }

        switch ($action) {
            case 'read':
                if (!file_exists($path)) {
                    return "Error: File does not exist.";
                }
                return file_get_contents($path);
            case 'write':
                $dir = dirname($path);
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                file_put_contents($path, $content);
                return "Successfully written to $path.";
            case 'append':
                $dir = dirname($path);
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                file_put_contents($path, $content, FILE_APPEND);
                return "Successfully appended to $path.";
            default:
                return "Error: Unknown action $action.";
        }
    }
}
