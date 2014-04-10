<?php

namespace eliza\beta;

class JSONFeed extends Feed {
    public static function feedJSON($_args) {
        return json_encode(self::feed($_args))
    }
}