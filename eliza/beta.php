<?php
//----------------------------------------------------------------------------//
//                                 eliza\beta                                 //
//----------------------------------------------------------------------------//
//                                  settings                                  //
//----------------------------------------------------------------------------//
    error_reporting(E_ALL);
    define ('DS', DIRECTORY_SEPARATOR); 
    define ('ROOT', dirname(__DIR__) . DS);
    define ('ELIZA', basename(__DIR__) . DS);
    define ('BASE_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/');
    
    define ('CACHE', ROOT . 'tmp' . DS . 'cache' . DS);



//----------------------------------------------------------------------------//
//                                  autoload                                  //
//----------------------------------------------------------------------------//
    spl_autoload_register(null, false);
    spl_autoload_extensions('.class.php');
    spl_autoload_register(function ($_class) {
        $path_to_class = ROOT . ELIZA . 'beta' . DS 
                       . basename($_class) . '.class.php';
                       
        if (file_exists($path_to_class))
            require_once $path_to_class;
    }, false);



//----------------------------------------------------------------------------//
//                               error handling                               //
//----------------------------------------------------------------------------//
    set_exception_handler(function ($e) {
        include 'oops.php'; die();
    });
    
    function oops($_excuse = null) { 
        if (is_array($_excuse)) oops(); 
        throw new eliza\beta\OopsException($_excuse);
    }
    
    set_error_handler(function () { 
        oops(func_get_arg(1).' in '.func_get_arg(2).' at '.func_get_arg(3)); 
    }, E_ALL & ~E_STRICT);
    
    // error codes
    define('OOPS', 'there is something fishy going on');