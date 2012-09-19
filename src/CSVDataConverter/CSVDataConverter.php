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
    protected $delimiter = ',';

    public function inputArray($data)
    {
        $this->input = $data;
    }

    protected function bindFila($row)
    {
        $datos = str_getcsv($row, $this->delimiter);
        if (count($datos) != count($this->columns)) {
            throw new Exception("");
        }
        $this->fila = array_combine($this->columns, $datos);
    }

    public function load()
    {
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
}