<?php

namespace eliza\beta;

class Query {
    public static function Feed($_feed, $_args) {
        Feed::load($_feed);
        return $_feed::__Feed($_args);
    }
}