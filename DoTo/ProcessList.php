<?php

namespace DoTo;

use DoTo\Todo;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class ProcessList
{
    private $todos = [];

    public function __construct()
    {
        if (file_exists('list.json') === true) {
            $this->loadTodo();
        } else {
            $this->createTodo();
        }
    }

    private function loadTodo(): void
    {
        $file = json_decode(file_get_contents('list.json'));
        foreach ($file as $variables) {
            $this->todos[] = new Todo($variables->id, $variables->text, $variables->state);
        }
    }

    private function createTodo(): void
    {
        $makeFile = array_map(fn($todo) => $todo->startArray(), $this->todos);;
        file_put_contents('list.json', json_encode($makeFile));
    }

    public function addTodo($text): void
    {
        $id = count($this->todos) > 0 ? end($this->todos)->id + 1 : 1;
        $this->todos[] = new Todo($id, $text);
        $this->createTodo();
    }

    public function deleteTodo($id): bool
    {
        $removed = false;

        foreach ($this->todos as $index => $todo) {
            if ($todo->getId() == $id) {
                array_splice($this->todos, $index, 1);
                $removed = true;
                break;
            }
        }

        if ($removed === true) {
            for ($i = $index; $i < count($this->todos); $i++) {
                $newId = $this->todos[$i]->getId() - 1;
                $this->todos[$i]->setId($newId);
            }

            $this->createTodo();
            return true;
        }
        return false;
    }

    public function isCompleted($id): void
    {
        foreach ($this->todos as $task) {
            if ($task->id == $id) {
                $task->isCompleted();
                break;
            }
        }
        $this->createTodo();
    }

    public function showCommands()
    {
        $output = new ConsoleOutput();
        $table = new Table($output);
        $table
            ->setHeaders(['Command', 'Description'])
            ->setRows([
                ['Add', 'Add a new TODO'],
                ['List', 'List all TODOs'],
                ['Complete', 'Mark TODO as completed'],
                ['Delete', 'Delete a TODO'],
                ['Exit', 'Exit the application']
            ]);

        $table->render();
    }

    public function listTodo()
    {
        $output = new ConsoleOutput();
        $table = new Table($output);
        $table
            ->setHeaders(['ID', 'Task', 'Completed'])
            ->setRows(array_map(function ($todo) {
                return [$todo->id, $todo->text, $todo->checkCompleted()];
            }, $this->todos));

        $table->render();
    }
}