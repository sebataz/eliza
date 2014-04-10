<?php 
include '../eliza/beta.php';

if (!count($_GET)) 
    oops('there is nothing to see here');

$feed = key($_GET);

// echo eliza\beta\Response::ls('.');
// echo eliza\beta\Response::Feed($feed, count($_POST) ? $_POST : array_slice($_GET, 1))->sortBy('Timestamp', SORT_ASC);
echo eliza\beta\Response::JSONFeed($feed, count($_POST) ? $_POST : array_slice($_GET, 1));
// echo eliza\beta\Response::XMLFeed($feed, count($_POST) ? $_POST : array_slice($_GET, 1))->sortBy('Timestamp', SORT_ASC);
// echo eliza\beta\Response::SimpleHTMLFeed($feed, count($_POST) ? $_POST : array_slice($_GET, 1))->sortBy('Timestamp', SORT_ASC);


// var_dump($_POST); die();

// Get::proxy($_GET);
// Post::proxy($_POST);


    
