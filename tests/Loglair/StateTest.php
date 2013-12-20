<?php

namespace Loglair;

class StateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidCursorLocationProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowAnExceptionWhenCursorLocationIsNotValid($invalidCursorLocation)
    {
        $state = new State($invalidCursorLocation, 123, '/tmp/file');
    }

    public function invalidCursorLocationProvider()
    {
        return array(
            array(-1),
            array('string'),
            array(new \ArrayObject),
        );
    }

    /**
     * @dataProvider invalidFileSizeProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowAnExceptionWhenFileSizeIsNotValid($invalidFileSizeProvider)
    {
        $state = new State(123, $invalidFileSizeProvider, '/tmp/file');
    }

    public function invalidFileSizeProvider()
    {
        return array(
            array(-1),
            array('string'),
            array(new \ArrayObject),
        );
    }

    public function testShouldGetFileSize()
    {
        $state = new State(15, 10, '/tmp/file');

        $this->assertEquals(10, $state->getFileSize());
    }

    public function testShouldGetCursorLocation()
    {
        $state = new State(10, 15, '/tmp/file');

        $this->assertEquals(10, $state->getCursorLocation());
    }
}
