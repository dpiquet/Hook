<?php

namespace Dpiquet\Hook\Tests\Listener;

use Dpiquet\Hook\Listener\ListenerCollection;
use Dpiquet\Hook\Listener\Listener;
use PHPUnit_Framework_TestCase;

class ListenerCollectionTest extends PHPUnit_Framework_TestCase
{

    public function test__construct()
    {
        $collection = new ListenerCollection();
        $this->assertInstanceOf(ListenerCollection::class, $collection);
    }

    /**
     * @dataProvider collectionGenerator
     */
    public function testGetListeners(ListenerCollection $collection, $listenerCount)
    {
        $listeners = $collection->getListeners();
        $this->assertTrue(is_array($listeners));
        $this->assertEquals($listenerCount, count($listeners));
    }


    public function testAddListener()
    {
        $collection = new ListenerCollection();

        $listener = $this->createMock(Listener::class);
        $listener->method('getPriority')->willReturn(10);
        $collection->addListener($listener);
        $this->assertEquals(1, count($collection));

        $listener2 = $this->createMock(Listener::class);
        $listener2->method('getPriority')->willReturn(15);
        $collection->addListener($listener2);
        $this->assertEquals(2, count($collection));

        $listener3 = $this->createMock(Listener::class);
        $listener3->method('getPriority')->willReturn(3);
        $collection->addListener($listener3);
        $this->assertEquals(3, count($collection));

        $listener4 = $this->createMock(Listener::class);
        $listener4->method('getPriority')->willReturn(15);
        $collection->addListener($listener4);

        $listener5 = $this->createMock(Listener::class);
        $listener5->method('getPriority')->willReturn(-15);
        $collection->addListener($listener5);

        $this->assertEquals(5, count($collection));

        // Check listeners are ordered by priority
        $prios = [-15, 3, 10, 15, 15];
        foreach($collection as $index => $listener) {
            $this->assertEquals($prios[$index], $listener->getPriority());
        }
    }

    /**
     * @return Generator of ListenerCollection
     */
    public function collectionGenerator()
    {
        $emptyCollection = new ListenerCollection();

        yield [$emptyCollection, 0];

        $collection3 = new ListenerCollection();

        $stubs = [
            $this->createMock(Listener::class),
            $this->createMock(Listener::class),
            $this->createMock(Listener::class),
        ];

        foreach($stubs as $stub) {
            $collection3->addListener($stub);
        }

        yield [$collection3, 3];
    }

    /**
     * @param ListenerCollection $collection
     * @param int $listenerCount
     * @dataProvider collectionGenerator
     */
    public function testcountable(ListenerCollection $collection, $listenerCount)
    {
        $this->assertEquals($listenerCount, count($collection));
    }

    /**
     * @param ListenerCollection $collection
     * @param int $listenerCount
     * @dataProvider collectionGenerator
     */
    public function testforeach(ListenerCollection $collection, $listenerCount)
    {
        $index = 0;

        foreach($collection as $listener) {
            $index++;
            $this->assertInstanceOf(Listener::class, $listener);
        }

        $this->assertEquals($listenerCount, $index);
    }


    /**
     * @param ListenerCollection $collection
     * @param int $listenerCount
     * @dataProvider collectionGenerator
     */
    public function testreset(ListenerCollection $collection, $listenerCount)
    {
        reset($collection);

        $this->assertEquals(0, $collection->key());
    }

}