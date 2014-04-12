<?php

namespace eliza\feed;

class JSONFeed extends QueryFeed {
    public function JSONFeed() {
        $array = (array)$this;
        return json_encode($array);
    }
}