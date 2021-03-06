--TEST--
Test suspend in object destructor
--SKIPIF--
<?php include __DIR__ . '/include/skip-if.php';
--FILE--
<?php

require dirname(__DIR__) . '/scripts/bootstrap.php';

$loop = new Loop;

$object = new class($loop) {
    private Loop $loop;

    public function __construct(Loop $loop)
    {
        $this->loop = $loop;
    }

    public function __destruct()
    {
        $promise = new Promise($this->loop);
        $this->loop->delay(10, fn() => $promise->resolve('destruct'));
        echo Fiber::suspend($promise, $this->loop);
    }
};

Fiber::suspend(new Success($loop), $loop);

--EXPECT--
destruct
