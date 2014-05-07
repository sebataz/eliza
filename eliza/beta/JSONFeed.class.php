<?php

namespace eliza\feed;

interface JSONFeedI {
    public function toJSON();
}

class JSONFeed extends QFeed {
    public function JSONFeed() {
        @header('Content-type: application/json');
        $array = (array)$this;
        return json_encode($array);
    }
}