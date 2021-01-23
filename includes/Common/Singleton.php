<?php
declare(strict_types=1);

namespace Common;


class Singleton {
    
    protected static $instance = null;
    
    /**
     * Call this method to instance
     */
    public static function instance() {
        if (static::$instance === null){
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
      * private not to be instanced
     */
    private function __construct() {}

    /**
     * private not to clone instance
     */
    private function __clone() {}

    /**
     * private not to serialize instance
     */
    private function __sleep() {}

    /**
     * private not to unserialize instance
     */
    private function __wakeup() {}

}

