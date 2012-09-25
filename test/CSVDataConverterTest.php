<?php
use CSVDataConverter\CSVDataConverter;

class CSVDataConverterTest extends PHPUnit_Framework_TestCase
{
    public $csvconverter;

    public function setUp()
    {
        $this->csvconverter = new CSVDataConverter();
        $this->csvconverter->inputArray(array('a,1', 'b,2', 'c,3'));
        $this->csvconverter->setColumns(array('char', 'num'));
    }

    public function testFixedOutputData()
    {
        $this->csvconverter->setRowData(array('1', '2'));
        $output = $this->csvconverter->getOutputAsArray();

        $this->assertEquals(3, count($output));
        $this->assertEquals(1, $output[0][0]);
        $this->assertEquals(2, $output[0][1]);
        $this->assertEquals(1, $output[1][0]);
        $this->assertEquals(2, $output[1][1]);
    }

    public function testOutputDataEqualThanInput()
    {
        $this->csvconverter->setRowData(array(
            $this->csvconverter->value('char'),
            $this->csvconverter->value('num')
        ));

        $output = $this->csvconverter->getOutputAsArray();

        $this->assertEquals(3, count($output));
        $this->assertEquals('a', $output[0][0]);
        $this->assertEquals(1, $output[0][1]);
        $this->assertEquals('b', $output[1][0]);
        $this->assertEquals(2, $output[1][1]);
        $this->assertEquals('c', $output[2][0]);
        $this->assertEquals(3, $output[2][1]);
    }

    public function testOutputDataWithClosures()
    {
        $this->csvconverter->setRowData(array(
            function($csv) {
                return $csv->getValue('char') . 'sufix';
            },
            function($csv) {
                return $csv->getValue('num') + 10;
            }
        ));

        $output = $this->csvconverter->getOutputAsArray();

        $this->assertEquals(3, count($output));
        $this->assertEquals('asufix', $output[0][0]);
        $this->assertEquals('bsufix', $output[1][0]);
        $this->assertEquals('csufix', $output[2][0]);
        $this->assertEquals(11, $output[0][1]);
        $this->assertEquals(12, $output[1][1]);
        $this->assertEquals(13, $output[2][1]);
    }
}