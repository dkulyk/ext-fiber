<?php

final class Success implements Future
{
    private Loop $loop;

    private mixed $value;

    public function __construct(Loop $loop, mixed $value = null)
    {
        if ($value instanceof Future) {
            throw new \Error("Cannot use a Future as success value");
        }

        $this->loop = $loop;
        $this->value = $value;
    }

    public function __invoke(Continuation $continuation): void
    {
        $this->loop->defer(fn() => $continuation->resume($this->value));
    }
}
