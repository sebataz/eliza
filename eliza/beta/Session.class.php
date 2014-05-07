<?php

namespace eliza\beta;

class Session {
    public static function open() {
        if (session_id() == '')
            session_start();
    }

    public static function locked() {
        self::open();
        
        if (isset($_SESSION['lock']))
            foreach (Configuration::get()->Lock as $user => $lock)
                if ($_SESSION['lock'] == $lock) return;
        
        oops('this page is locked');
    }
    
    public static function unlock($_lock) {
        self::open();
        $_SESSION['lock'] = $_lock;
    }
}