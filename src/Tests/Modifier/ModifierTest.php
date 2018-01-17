<?php

namespace Dpiquet\Hook\Tests\Modifier;

use Dpiquet\Hook\Modifier\Modifier;

class ModifierTest extends \PHPUnit_Framework_TestCase
{

    public function test__construct()
    {
        $modifier = new Modifier('testEvent', function() {return 'ok';}, 10);
        $this->assertInstanceOf(Modifier::class, $modifier);
        $this->assertEquals('testEvent', $modifier->getEvent());
        $this->assertEquals(10, $modifier->getPriority());
        $this->assertTrue(is_callable($modifier->getCallback()), 'callback is callable');
    }

    public function callMeMaybe($a)
    {
        return $a;
    }

    public function testcall()
    {
        $modifier = new Modifier('test', function() { return 'ok'; }, 10);
        $this->assertEquals('ok', $modifier->call('smth'));

        $modifier = new Modifier('test', function($a) { return $a; }, 10);
        $this->assertEquals('test', $modifier->call('test'));

        $modifier = new Modifier('test', [$this, 'callMeMaybe'], 10);
        $this->assertEquals('called', $modifier->call('called'));
    }
}
