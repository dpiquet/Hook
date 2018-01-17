<?php

namespace Dpiquet\Hook\Tests\Modifier;

use Dpiquet\Hook\Modifier\ModifierCollection;
use Dpiquet\Hook\Modifier\Modifier;
use PHPUnit_Framework_TestCase;

class ModifierCollectionTest extends PHPUnit_Framework_TestCase
{

    public function test__construct()
    {
        $collection = new ModifierCollection();
        $this->assertInstanceOf(ModifierCollection::class, $collection);
    }

    /**
     * @dataProvider collectionGenerator
     */
    public function testGetListeners(ModifierCollection $collection, $modifierCount)
    {
        $modifiers = $collection->getModifiers();
        $this->assertTrue(is_array($modifiers));
        $this->assertEquals($modifierCount, count($modifiers));
    }


    public function testAddListener()
    {
        $collection = new ModifierCollection();

        $modifier = $this->createMock(Modifier::class);
        $modifier->method('getPriority')->willReturn(10);
        $collection->addModifier($modifier);
        $this->assertEquals(1, count($collection));

        $listener2 = $this->createMock(Modifier::class);
        $listener2->method('getPriority')->willReturn(15);
        $collection->addModifier($listener2);
        $this->assertEquals(2, count($collection));

        $listener3 = $this->createMock(Modifier::class);
        $listener3->method('getPriority')->willReturn(3);
        $collection->addModifier($listener3);
        $this->assertEquals(3, count($collection));

        $listener4 = $this->createMock(Modifier::class);
        $listener4->method('getPriority')->willReturn(15);
        $collection->addModifier($listener4);

        $listener5 = $this->createMock(Modifier::class);
        $listener5->method('getPriority')->willReturn(-15);
        $collection->addModifier($listener5);

        $this->assertEquals(5, count($collection));

        // Check listeners are ordered by priority
        $prios = [-15, 3, 10, 15, 15];
        foreach($collection as $index => $modifier) {
            $this->assertEquals($prios[$index], $modifier->getPriority());
        }
    }

    /**
     * Generator of ListenerCollection
     */
    public function collectionGenerator()
    {
        $emptyCollection = new ModifierCollection();

        yield [$emptyCollection, 0];

        $collection3 = new ModifierCollection();

        $stubs = [
            $this->createMock(Modifier::class),
            $this->createMock(Modifier::class),
            $this->createMock(Modifier::class),
        ];

        foreach($stubs as $stub) {
            $collection3->addModifier($stub);
        }

        yield [$collection3, 3];
    }

    /**
     * @param ModifierCollection $collection
     * @param int $modifierCount
     * @dataProvider collectionGenerator
     */
    public function testcountable(ModifierCollection $collection, $modifierCount)
    {
        $this->assertEquals($modifierCount, count($collection));
    }

    /**
     * @param ModifierCollection $collection
     * @param int $listenerCount
     * @dataProvider collectionGenerator
     */
    public function testforeach(ModifierCollection $collection, $listenerCount)
    {
        $index = 0;

        foreach($collection as $listener) {
            $index++;
            $this->assertInstanceOf(Modifier::class, $listener);
        }

        $this->assertEquals($listenerCount, $index);
    }


    /**
     * @param ModifierCollection $collection
     * @param int $listenerCount
     * @dataProvider collectionGenerator
     */
    public function testreset(ModifierCollection $collection, $listenerCount)
    {
        reset($collection);

        $this->assertEquals(0, $collection->key());
    }
}