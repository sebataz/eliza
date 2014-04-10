<?php

namespace eliza\beta

class Cache {
    public static function cached($_timeout = 3600) {
        $file = '/tmp/cache/' . md5($_SERVER['REQUEST_URI']);

        if ((file_exists($file)) && (filemtime($file) + $_timeout) > time()) {
            // Output the existing file to the user
            readfile($file);
            exit();
        } else {
            // Ssetup saving and let the page execute:
            ob_start();
            register_shutdown_function(function () use ($file) {
                $content = ob_get_flush();
                file_put_contents($file, $content);
            });
        }
    }
}