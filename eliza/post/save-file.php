<?php

class SaveNode extends Node {
    public static function Feed($_path, $_content) {
        if (null !== ($handle = fopen(ROOT . $_path, 'w')))
            return fwrite($handle, $_content);
        else
            return false;
    }
}