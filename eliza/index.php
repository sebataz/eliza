<?php 
include '../eliza/beta.php';

if (!count($_GET)) oops();

$feed = key($_GET);
$args = count($_POST) ? $_POST : array_slice($_GET, 1);

echo eliza\beta\Query::Feed($feed, $args)
    ->sortBy(isset($_GET['sort']) ? $_GET['sort'] : 'Title')
    ->limit(isset($_GET['limit']) ? $_GET['limit'] : null)
    ->JSONFeed();