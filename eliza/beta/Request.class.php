<?php

namespace eliza\beta;

class Request {
    public $url;

    private $_option = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => null,
            'content' => null,
        )
    );

    public function __construct($_url, $_request, $_method = 'GET') {
        $this->url = $_url;
        $this->_option['http']['content'] = http_build_query($_request);
        $this->_option['http']['method'] = $_method;
    }    
    
    public function send($_request = null) {
        file_get_contents($this->url, false, $this->_context());
    }
    
    private function _context() {
        return stream_context_create($this->_option);
    }
    
    public static function querystring($_include = array()) {
        if (!is_array($_include)) $_include = array($_include);
        $querystring = array();
        
        foreach ($_GET as $key => $value) {
            if (in_array($key, $_include) || empty($_include))
                $querystring[$key] = $value;
        }
        
        // the preg_ will prevent the overwriting of array get variables, by removing the explicit index
        return empty($querystring) 
             ? '' : ('&' . preg_replace('/%5B[0-9]*%5D/', '[]', http_build_query($querystring)));
    }
}