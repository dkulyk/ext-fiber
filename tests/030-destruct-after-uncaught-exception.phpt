--TEST--
Test suspend in object destructor after an uncaught exception
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
        $this->loop->delay(10, fn() => $promise->resolve(1));
        Fiber::suspend($promise, $this->loop);
        echo "unreacahble";
    }
};

Fiber::suspend(new Success($loop), $loop);

throw new Exception('test');

--EXPECTF--
Fatal error: Uncaught Exception: test in %s:%d
Stack trace:
#0 {main}
  thrown in %s on line %d

Fatal error: Uncaught FiberError: Cannot suspend during shutdown in %s:%d
Stack trace:
#0 %s(%d): Fiber::suspend(Object(Promise), Object(Loop))
#1 [internal function]: class@anonymous->__destruct()
#2 {main}
  thrown in %s on line %d

