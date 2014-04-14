<?php 
include '../eliza/beta.php';

if (!count($_GET)) oops();

$feed = key($_GET);
$args = count($_POST) ? $_POST : array_slice($_GET, 1);

// echo eliza\beta\Response::Feed($feed, $args)
    // // ->Q(isset($_GET['q']) ? $_GET['q'] : null)
    // // ->sortBy(isset($_GET['sort']) ? $_GET['sort'] : 'Title')
    // ->limit(isset($_GET['limit']) ? $_GET['limit'] : null)
    // ->JSONFeed();
    
echo eliza\beta\Response::Feed($feed, $args)->JSONFeed();
echo eliza\beta\Response::JSONFeed($feed, $args);