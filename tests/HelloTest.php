<?php

declare(strict_types=1);

namespace Be\App;

use Be\App\Input\HelloInput;
use Be\App\Module\AppModule;
use Be\App\Final\Hello;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class HelloTest extends TestCase
{
    public function testHello(): void
    {
        $injector = new Injector(new AppModule());
        $becoming = new Becoming($injector, 'Be\App\Semantic');
        $input = new HelloInput('World');
        $hello = $becoming($input);
        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertSame('Hello World', $hello->greeting);
    }
}
