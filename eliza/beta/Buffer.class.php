<?php 

namespace eliza\beta;

class Buffer {
    private $_buffer = null;

    public static function buffered() {
        ob_start();
        register_shutdown_function(array(get_called_class(), 'out')); 
    }
    
    public static function out() {
        ob_flush();
    }
    
    public static function clean() {
        ob_end_clean();
    }
}