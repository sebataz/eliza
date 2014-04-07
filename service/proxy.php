<?php 

include '../lib/service.php';

// var_dump($_POST); die();

Get::proxy($_GET);
Post::proxy($_POST);