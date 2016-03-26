<?php

namespace DefuncTest;

class DemoTest extends \PHPUnit_Framework_TestCase
{
    protected $defunc;

    public function setUp()
    {
        $this->defunc = new \Defunc\Builder();
    }

    public function tearDown()
    {
        $this->defunc->clear();
    }

    public function test()
    {
        $space_tst = $this->defunc->in(__NAMESPACE__);

        // set return value
        $space_tst->time()->willReturn(42);

        $this->assertEquals(42, time());

        // change return value
        $space_tst->time()->willReturn(57);

        $this->assertEquals(57, time());

        // with arguments
        $space_tst->date('Y-m-d')->willReturn('1900-01-02');
        $space_tst->date('H:i:s')->willReturn('12:34:56');

        $this->assertEquals('1900-01-02', date('Y-m-d'));
        $this->assertEquals('12:34:56', date('H:i:s'));

        // call with unknown arguments will return null
        $this->assertNull(date('r'));
        $this->assertNull(date('s'));

        // clear mocks, real functions will be called
        $space_tst->clear();

        $this->assertNotEquals(42, time());
        $this->assertNotEquals('1900-01-02', date('Y-m-d'));
    }
}
