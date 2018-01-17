<?php

namespace Dpiquet\Hook\Tests;

use Dpiquet\Hook\Hook;
use PHPUnit_Framework_TestCase;
use StdClass;

class HookTest extends PHPUnit_Framework_TestCase
{
    public function testgetInstance()
    {
        $instance = Hook::getInstance();
        $this->assertInstanceOf(Hook::class, $instance);
    }

    public function testcallListeners()
    {
        $arg = new StdClass();
        $arg->proof = 'bad';
        Hook::registerListener('test', function($obj) { $obj->proof = 'modified'; }, 10);
        Hook::callListeners('test', $arg);
        $this->assertEquals('modified', $arg->proof);

        Hook::callListeners('undef', $arg);
    }

    public function testcallModifiers()
    {
        Hook::registerModifier('test', function() { return 'bad'; }, 10);
        Hook::registerModifier('test', function() { return 'modified'; }, 12);
        Hook::registerModifier('ko', function() { return 'bad'; }, 120);
        $ret = Hook::callModifiers('test', 'ok');
        $this->assertEquals('modified', $ret);

        Hook::callModifiers('undef', 'test');
    }
}