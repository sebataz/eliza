<?php
include '../eliza/beta.php';
if (!count($_GET)) oops();

$feed = key($_GET);
$args = count($_POST) 
      ? $_POST : array_slice($_GET, 1);
      
if (isset($_GET['term']))
    echo eliza\beta\Feed::Kb()->TagList($_GET['term'])->JSONFeed();
else
    echo eliza\beta\Response::Feed($feed, $args)
        ->Q(isset($_GET['q']) ? $_GET['q'] : false)
        ->sortBy(isset($_GET['sort']) ? $_GET['sort'] : null)
        ->limit(isset($_GET['limit']) ? $_GET['limit'] : null)
        ->JSONFeed();