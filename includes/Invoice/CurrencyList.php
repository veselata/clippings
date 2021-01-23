<?php
declare(strict_types=1);

namespace Invoice;


final class CurrencyList { 
    
    //const CURRENCY_NOP = 0;
    const CURRENCY_USD = 1;
    // default currency 
    const CURRENCY_EUR = 2;
    const CURRENCY_GBP = 3;
    
    /**
     *
     * @return array
     */
    public static function getCurrencyList() {
        return [
            //self::CURRENCY_NOP => 'N\A',
            self::CURRENCY_USD => 'USD',
            self::CURRENCY_EUR => 'EUR',
            self::CURRENCY_GBP => 'GBP',
        ];
    }

    /**
     * @return string
     */
    public static function getCurrencyByKey($key) {
        $list = self::getCurrencyList();
        return isset($list[$key]) ? $list[$key] : self::CURRENCY_EUR;
    }
}