<?php
use CSVDataConverter\CSVDataConverter;

class CSVDataConverterTest extends PHPUnit_Framework_TestCase
{
    public function testFixedOutputData()
    {
        $conv = new CSVDataConverter();
        $conv->inputArray(array('a,1', 'b,2', 'c,3'));
        $conv->setColumns(array('char', 'num'));
        $conv->setRowData(array('1', '2'));
        $output = $conv->getOutputAsArray();

        $this->assertEquals(3, count($output));
        $this->assertEquals(1, $output[0][0]);
        $this->assertEquals(2, $output[0][1]);
        $this->assertEquals(1, $output[1][0]);
        $this->assertEquals(2, $output[1][1]);
    }

    public function testOutputDataEqualThanInput()
    {
        $conv = new CSVDataConverter();
        $conv->inputArray(array('a,1', 'b,2', 'c,3'));
        $conv->setColumns(array('char', 'num'));

        $conv->setRowData(array($conv->value('char'), $conv->value('num')));

        $output = $conv->getOutputAsArray();

        $this->assertEquals(3, count($output));
        $this->assertEquals('a', $output[0][0]);
        $this->assertEquals(1, $output[0][1]);
        $this->assertEquals('b', $output[1][0]);
        $this->assertEquals(2, $output[1][1]);
        $this->assertEquals('c', $output[2][0]);
        $this->assertEquals(3, $output[2][1]);
    }
}