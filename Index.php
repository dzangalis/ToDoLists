<?php

require __DIR__ . '/vendor/autoload.php';

use DoTo\ProcessList;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

$todoList = new ProcessList();

while (true) {
    $todoList->showCommands();

    $input = readline("Enter command: ");
    $formatedInput = ucfirst(strtolower($input));

    switch ($formatedInput) {

        case "Add":
            $text = readline("Enter your task to do: \n");

            if (empty($text) === false) {
                $todoList->addTodo($text);
                echo "Task added successfully!" . PHP_EOL;
            } else {
                echo "Error: No task was set." . PHP_EOL;
            }
            break;

        case "List":
            $todoList->listTodo() . PHP_EOL;
            break;

        case "Complete":
            $todoList->listTodo() . PHP_EOL;
            $taskId = readline("Enter the task you've done: \n");

            if (empty($taskId) === false && is_numeric($taskId)) {
                $todoList->isCompleted((int)$taskId);
                echo "Task#{$taskId} is completed now!" . PHP_EOL;
            } else {
                echo "Error: No task was selected." . PHP_EOL;
            }
            break;

        case "Delete":
            $taskId = readline("Enter the task you want to delete: \n");

            if (empty($taskId) === false && is_numeric($taskId)) {
                $isDeleted = $todoList->deleteTodo((int)$taskId);
                if ($isDeleted === true) {
                    echo "Task#{$taskId} was deleted." . PHP_EOL;
                } else {
                    echo "Task#{$taskId} was not found." . PHP_EOL;
                }
            } else {
                echo "Error: No task was selected." . PHP_EOL;
            }
            break;

        case "Exit":
            echo "Goodbye!" . PHP_EOL;
            exit;

        default:
            echo "Error: Unknown command " . $formatedInput . PHP_EOL;
            break;
    }
}