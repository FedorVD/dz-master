<?php

function main(): string
{
    $command = parseCommand();

    if (function_exists($command)) {
        $result = $command();
    } else {
        $result = handleError("Нет такой функции");
    }

    return $result;
}

function parseCommand(): string
{
    //TODO улучшите код, избавтесь от дублирования строки handleHelp
    $command = $_SERVER['argv'][1] ?? 'help';

        return match ($command) {
            'add-post' => 'addPost',
            'read-all' => 'readAllPosts',
            'clear-all' => 'clearPosts',
            'read-post' => 'readPost',
            'find-post' => 'searchPost',
            'delete-post'=> 'deletePost',
            'quiz' => 'quiz',
            default => 'handleHelp'
        };

}