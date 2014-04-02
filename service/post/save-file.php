<?php

function save_file($_path_to_file, $_content) {
        if (null !== ($handle = fopen(ROOT . $_path_to_file, 'w')))
            return fwrite($handle, $_content);
        else
            return false;
}