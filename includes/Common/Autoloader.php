<?php
declare(strict_types=1);

namespace Common;

class Autoloader {

    /**
     *
     * @param string $className
     */
    public static function initAutoload($className) {
        $matches = array();
        preg_match('/(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $className, $matches);

        $class = (isset($matches['class'])) ? $matches['class'] : '';
        $namespace = (isset($matches['namespace'])) ? $matches['namespace'] : '';

        $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . $class . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }

    /**
     * @return void
     */
    public static function registerAutoload(): void {
        spl_autoload_register(__NAMESPACE__ . "\\Autoloader::initAutoload");
    }

}
