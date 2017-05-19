<?php

namespace eliza;

class CollectionFeed extends CollectionHTML {
    public function getById($_id) {
        return $this->getBy('Id', $_id);
    }
}