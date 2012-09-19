<?php

use CSVDataConverter\CSVDataConverter;

class CSVDataConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testDummy()
    {
        new CSVDataConverter();
        $this->assertTrue(true);
    }
}