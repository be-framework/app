<?php

declare(strict_types=1);

namespace Be\Skeleton;

use Be\Skeleton\Input\HelloInput;
use Be\Skeleton\Module\AppModule;
use Be\Skeleton\Final\Hello;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class HelloTest extends TestCase
{
    public function testHello(): void
    {
        $injector = new Injector(new AppModule());
        $becoming = new Becoming($injector, 'Be\Skeleton\Semantic');
        $input = new HelloInput('World');
        $hello = $becoming($input);
        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertSame('Hello World', $hello->greeting);
    }
}
