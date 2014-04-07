<?php

function remove_file($_path_to_file) {
        if (file_exists($_path_to_file))
            return unlink($handle, $_content);
        else
            return false;
}