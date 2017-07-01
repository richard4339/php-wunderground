<?php

/**
 * Class Test
 */
class Test extends PHPUnit_Framework_TestCase
{

    /**
     * Tests for passing a valid API key
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAPIKey()
    {
        new \Wunderground\Objects\Wunderground('');
    }

}
