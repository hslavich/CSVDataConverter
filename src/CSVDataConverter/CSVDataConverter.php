<?php
namespace CSVDataConverter;

class CSVDataConverter
{
    protected $input = array();
    protected $row = array();
    protected $columns = array();
    protected $rowData = array();
    protected $output = array();
    protected $fila = array();
    protected $inputDelimiter = ',';
    protected $outputDelimiter = ',';

    public function setInputDelimiter($str)
    {
        $this->inputDelimiter = $str;
    }

    public function setOutputDelimiter($str)
    {
        $this->outputDelimiter = $str;
    }

    public function inputArray($data)
    {
        $this->input = $data;
    }

    public function inputFilename($filename)
    {
        if (file_exists($filename)) {
            $this->input = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        } else {
            throw new \Exception("Invalid file: $filename");
        }
    }

    protected function bindFila($row)
    {
        $datos = str_getcsv($row, $this->inputDelimiter);
        if (count($datos) != count($this->columns)) {
            throw new \Exception("Columns definition does not match with input data");
        }
        $this->fila = array_combine($this->columns, $datos);
    }

    public function load()
    {
        $this->output = array();

        foreach ($this->input as $dato) {
            $this->bindFila($dato);
            $row = array();
            foreach ($this->rowData as $data) {
                if ($data instanceof \Closure) {
                    $row[] = $data($this);
                } else {
                    $row[] = $data;
                }
            }
            $this->output[] = $row;
        }
    }

    public function value($column)
    {
        return function($csv) use ($column) {
            return $csv->getValue($column);
        };
    }

    public function getValue($clave)
    {
        return trim($this->fila[$clave]);
    }

    public function setRowData($rowData)
    {
        $this->rowData = $rowData;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getOutputAsArray()
    {
        $this->load();

        return $this->output;
    }

    public function outputToCSVFile($filename)
    {
        $data = $this->getOutputAsArray();
        $file = fopen($filename, 'w');
        foreach ($data as $fields) {
            fputcsv($file, $fields, $this->outputDelimiter);
        }
        fclose($file);
    }

    public function outputToPlainTextFile($filename)
    {
        $data = $this->getOutputAsArray();
        $delim = $this->outputDelimiter;
        $output = array_map(function($elem) use ($delim) {
            return implode($delim, $elem) . PHP_EOL;
        }, $data);

        file_put_contents($filename, $output);
    }
}