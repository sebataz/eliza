<?php 
include '../eliza/beta.php';

if (!count($_GET)) 
    oops('there is nothing to see here');

$feed = key($_GET);
echo eliza\beta\Response::JSONFeed($feed, count($_POST) ? $_POST : array_slice($_GET, 1));


// brava la mia ciccia!!!