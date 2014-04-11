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
}