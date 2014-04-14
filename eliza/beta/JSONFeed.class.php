<?php

namespace eliza\feed;

class JSONFeed extends QFeed {
    public function JSONFeed() {
        $array = (array)$this;
        return json_encode($array);
    }
}