<?php
declare(strict_types=1);

namespace Common;


class CsvToArray {
    
    /**
     * Delimiter character
     *
     * @var string
     */
    public $delimiter = ',';

    /**
     * Enclosure character
     *
     * @var string
     */
    public $enclosure = '"';
    
    /**
     * File name
     *
     * @var string
     */
    public $file;

    /**
     * Array of CSV data
     *
     * @var array
     */
    public $csvData = [];
    
    /**
     * @param string|null $file CSV file path
     */
    public function __construct($file = null, $excludeHeading = true) {
        if (!empty($file)) {
            $this->parse($file, $excludeHeading);
        }
    }


    /**
     * Parse a CSV file
     *
     * @return array
     */
    public function parse($file = null, $excludeHeading = true ) {
        if (empty($file)) {
            return [];
        }

        if (strlen($file) <= PHP_MAXPATHLEN && is_readable($file)) {
            $this->file = $file;
            $this->csvData = $this->_parse_file();
            if($excludeHeading) {
                array_shift($this->csvData);
            }
        }

        return $this->csvData;
    }
    
    
    /**
     * Parse File
     *
     * @param string|null $file CSV file
     *
     * @return array
     */
    protected function _parse_file() {
       // Open the file for reading
        if (($handle = fopen($this->file, "r")) !== false) {
            while (($data = fgetcsv($handle, 0, $this->delimiter, $this->enclosure)) !== false) {
                $this->csvData[] = $data;		
            }

        fclose($handle);
    }

        return $this->csvData;
    }

    /**
     * Set delimiter character
     */
    public function setDelimiter($delimiter){
        $this->delimiter = $delimiter;
    }
    
    /**
     * Set enclosure character
     */
    public function seteEnclosure($enclosure){
        $this->enclosure = $enclosure;
    }
	
	/**
     *
     * @return array
     */
    public function getData() {
        return $this->csvData;
    }
}