<?php 

namespace eliza\beta;

class Buffer {
    public static function buffered() {
        ob_start();
        register_shutdown_function(function () {
            ob_flush();
        }); 
    }
    
    public static function clear() {
        ob_clean();
    }
}