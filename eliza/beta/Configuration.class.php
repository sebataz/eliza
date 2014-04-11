<?php

namespace eliza\beta;

class Configuration {
    private static $_Config;

    public static function load($_config_file) {
        
        // check if file exist
        if (!file_exists($_config_file))
            oops('the configuration could not be loaded');
                
        // create configuration
        include $_config_file;
        self::$_Config = new Collection($config);
    }
    
    public static function get() {
        if (!self::$_Config)
            self::load(ROOT . ELIZA . 'config.php');

        return self::$_Config;
    }
}