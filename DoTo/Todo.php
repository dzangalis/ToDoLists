<?php

namespace DoTo;

class Todo
{
    public int $id;
    public string $text;
    public string $state;

    public function __construct(int $id, string $text, string $state = 'pending')
    {
        $this->id = $id;
        $this->text = $text;
        $this->state = $state;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function isCompleted(): void
    {
        $this->state = 'completed';
    }

    public function checkCompleted(): string
    {
        return $this->state === 'completed' ? 'yes' : 'no';
    }

    public function startArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'state' => $this->state
        ];
    }
}