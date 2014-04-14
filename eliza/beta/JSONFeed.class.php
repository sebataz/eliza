<?php

namespace eliza\feed;

class JSONFeed extends QFeed {
    public function JSONFeed() {
        header('Content-type: application/json');
        $array = (array)$this;
        return json_encode($array);
    }
}