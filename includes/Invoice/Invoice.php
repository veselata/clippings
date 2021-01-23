<?php
declare(strict_types=1);

namespace Invoice;

use Common\Singleton;

class Invoice extends Singleton {

    /**
     * @var array
     */
    protected $data = [];   
    
    /**
     * @var array
     */
    protected $currencies;   
    
    /**
     * @var integer
     */
    protected $type;
	
    /**
     * @var string
     */
    protected $currency = \Invoice\CurrencyList::CURRENCY_EUR;	
    
    /**
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     *
     * @param array $data
     * @return Invoice
     */
    public function setData($data) {
        $this->data = $data;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getCurrencies() {
        return $this->currencies;
    }

    /**
     *
     * @param array $currencies
     * @return Invoice
     */
    public function setCurrencies($currencies) {
        $this->currencies = $currencies;

        return $this;
    }
    
    /**
     *
     * @return integer
     */
    public function getInvoiceType() {
        return $this->type;
    }

    /**
     *
     * @param integer $type
     * @return Invoice
     */
    public function setInvoiceType($type) {
        $this->type = $type;

        return $this;
    }
	
    /**
     *
     * @param string $currency
     * @return Invoice
     */
    public function setDefaultCurrency($currency) {
        $this->currency = $currency;

        return $this;
    }
	
    /**
     *
     * @return integer
     */
    public function getDefaultCurrency() {
        return $this->currency;
    }
    
     /**
     *
     * @return numeric
     */
    public function getCurrencyRateByAbbr($abbr) {
        $rate = 0;
        foreach ($this->currencies as $currency) {
            if($currency->getCurrency() == $abbr) {
                $rate = $currency->getRateByAbbr($abbr);
                break;
            }
        }
        return $rate;
    }
    
    /**
     *
     * @return array
     */
    public function getTotals($vat = '') {
        $output = [];

        $defaulCurrency = $this->getDefaultCurrency();
        $csvData = $this->data->getData();
		$keys = ['Customer','Vat number','Document number', 'Type','Parent document','Currency', 'Total']; 

        foreach ($csvData as $key => $line) {			
			$line = array_combine($keys,$line);
			
            $vendorTotal = 0;
            if(!isset($output[$line['Customer']]['Currency'])) {
				$output[$line['Customer']]['Currency'] = $defaulCurrency;
			}
            if(!isset($output[$line['Customer']]['Total'])) {
                $output[$line['Customer']]['Total'] = $vendorTotal;
            }
           
                if(array_key_exists('Currency', $line) && 
                   array_key_exists('Total', $line) &&      
                   is_numeric($line['Total'])) {
	     	
                    if($line['Currency'] != $defaulCurrency) {
                       $csvData[$key]['Currency'] = $defaulCurrency;
                       $vendorTotal = $csvData[$key]['Total'] = $line['Total']*$this->getCurrencyRateByAbbr($defaulCurrency);
                   }else {
                       $vendorTotal = $line['Total'];
                   }
                   
                   if(array_key_exists('Parent document', $line) &&
				      isset($output[$line['Document number']]) &&
                      $csvData[$key]['Document number'] == $line['Parent document']  
                      ) {
                      $vendorTotal += $line['Total']; 
                   } 
                   
                   $output[$line['Customer']][$line['Document number']][] = $vendorTotal; 
                   $output[$line['Customer']]['Total'] += $vendorTotal;
                }
        }
        return $output;
    }
}