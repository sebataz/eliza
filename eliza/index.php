<?php 
include '../eliza/beta.php';

if (!count($_GET)) 
    oops('there is nothing to see here');

$feed = key($_GET);

echo eliza\beta\Response::invoke($feed . 'JSON', count($_POST) ? $_POST : array_slice($_GET, 1));

// var_dump($_POST); die();

// Get::proxy($_GET);
// Post::proxy($_POST);


    
