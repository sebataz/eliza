<?php

namespace eliza\beta;

class JSONFeed extends Feed {
    public static function feedJSON() {
        return json_encode(call_user_func_array(array(get_called_class(), 'feed'), func_get_args()));
    }
}