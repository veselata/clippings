<?php
declare(strict_types=1);

namespace Invoice;


class Currency {
    
    /**
     * @var string
     */
    private $currency;

    /**
     * @var int|float
     */
    private $rate;

    /**
     * @param string $currencyAbbr
     * @param float $currencyRate
     * @throws \Exception
     */
    public function __construct($currencyAbbr, $currencyRate) {
    //public function __construct(string $currencyAbbr, float $currencyRate) {
        if (!preg_match('/^[A-Z]{3}$/', $currencyAbbr )) {
            throw new \Exception("All currencies abbr accept three uppercase letter acronym");
        }
        
        if(!is_numeric($currencyRate)) {
            throw new Exception("Input value is not numeric");
        }

        $this->currency = strtoupper($currencyAbbr);
        $this->rate = sprintf('%0.3f', round($currencyRate, 3)); 
    }

    /**
     * @return float|int
     */
    public function getRate() {
        return $this->rate;
    }
    
     /**
     * @return float|int
     */
    public function getCurrency() {
        return $this->currency;
    }
    
    /**
     * @return float|int
     */
    public function getRateByAbbr($abbr) {
        if($abbr == $this->currency){
            return $this->rate;
        }
        return 0;    
    }
}