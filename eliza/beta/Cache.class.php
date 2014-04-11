<?php

namespace eliza\beta

class Cache extends Buffer {
    private $_cache = null;

    public static function out() {
        $cached = ob_get_flush();
        file_put_contents(self::$_cache, $cached);
    }

    public static function cached($_timeout = 3600) {
        self::$_cache = '/tmp/cache/' . md5($_SERVER['REQUEST_URI']);
        
        if ((file_exists(self::$_cache)) && (filemtime(self::$_cache) + $_timeout) > time()) {
            readfile(self::$_cache);
            exit();
        } else {
            self::buffered();
        }
    }
}