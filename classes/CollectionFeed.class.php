<?php

namespace eliza;

class CollectionFeed extends CollectionHTML {
    private $__Feed = null;
    
    public function append($_Feed) {
        $this->__Feed = get_class($_Feed);
        return parent::append($_Feed);
    }
    
    public function getById($_id) {
        return $this->getBy('Id', $_id)->first();;
    }
    
    public function __call($_method, $_args) {
        if (!$this->__Feed) return new parent();
        return call_user_func_array(array($this->__Feed, $_method),
                                    array_merge(array($this), $_args));
    }
}