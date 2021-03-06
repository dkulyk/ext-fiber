--TEST--
FiberScheduler returns before resuming the fiber
--SKIPIF--
<?php include __DIR__ . '/include/skip-if.php';
--FILE--
<?php

require dirname(__DIR__) . '/scripts/bootstrap.php';

echo Fiber::suspend(fn(Fiber $fiber) => null, new Loop);

--EXPECTF--
Fatal error: Uncaught FiberError: Loop::run() returned before resuming the fiber in %s:%d
Stack trace:
#0 %s(%d): Fiber::suspend(Object(Closure), Object(Loop))
#1 {main}
  thrown in %s on line %d
