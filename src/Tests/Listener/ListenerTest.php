<?php

namespace Dpiquet\Hook\Tests\Listener;

use Dpiquet\Hook\Listener\Listener;
use PHPUnit_Framework_TestCase;
use StdClass;

class ListenerTest extends PHPUnit_Framework_TestCase
{

    public function test__construct()
    {
        $listener = new Listener('testEvent', function() {return 'ok';}, 10);
        $this->assertInstanceOf(Listener::class, $listener);
        $this->assertEquals('testEvent', $listener->getEvent());
        $this->assertEquals(10, $listener->getPriority());
    }


    /**
     * Method used in testCall
     * @param StdClass
     */
    public function callMeMaybe(StdClass $obj)
    {
        $obj->proof = 'callMeMaybe';
    }

    public function testcall()
    {
        $listener = new Listener('test', function(&$a) { $a = 'proof'; }, 20);
        $testVar = '';
        $listener->call($testVar);
        $this->assertEquals('proof', $testVar);

        $complex = new Listener('test', function(&$a, &$b = []) { $a = 'ok'; $b = ['ok']; }, 15);
        $arg1 = 'xxx';
        $arg2 = ['not empty'];
        $complex->call($arg1);
        $this->assertEquals('ok', $arg1);
        $complex->call($arg1, $arg2);
        $this->assertContains('ok', $arg2);

        $obj = new Listener('test', function ($a) {$a->test = 'modified !';}, 10);
        $ball = new StdClass();
        $obj->call($ball);
        $this->assertEquals('modified !', $ball->test);

        $proof = new StdClass();
        $class_call = new Listener('test', [$this, 'callMeMaybe'], 15);
        $class_call->call($proof);
        $this->assertEquals('callMeMaybe', $proof->proof);
    }

}
