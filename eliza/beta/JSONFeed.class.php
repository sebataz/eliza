<?php

namespace eliza\beta;

class JSONFeed extends Feed {
    public static function JSONFeed() {
        return json_encode(array_values((array)call_user_func_array(array(get_called_class(), 'Feed'), func_get_args())));
    }
}