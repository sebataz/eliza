<?php
//----------------------------------------------------------------------------//
//                          main runtime settings                             //
//----------------------------------------------------------------------------//
    error_reporting(E_ALL);
    define ('DS', DIRECTORY_SEPARATOR); 
    define ('ROOT', dirname(__DIR__) . DS);
    define ('ELIZA', basename(__DIR__) . DS);
    define ('BASE_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/');



//----------------------------------------------------------------------------//
//                                awake eliza                                 //
//----------------------------------------------------------------------------//
    spl_autoload_register(null, false);
    spl_autoload_extensions('.class.php');
    spl_autoload_register(function ($_class) {
        $path_to_class = ROOT . $_class . '.class.php';
        
        if (file_exists($path_to_class))
            require_once $path_to_class;
    }, false);

    set_exception_handler(function ($e) {
        include 'oops.php';
        die();
    });
    
    function oops($_excuse = '') { if (is_array($_excuse)) oops('an array was given as an excuse'); throw new eliza\beta\OopsException($_excuse); }
    set_error_handler(function () { 
        oops(func_get_arg(1) . ' in ' . func_get_arg(2) . ' at ' . func_get_arg(3)); 
    }, E_ALL & ~E_STRICT);