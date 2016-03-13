<?php

// Main settings
$cfg['Title'] = 'sebataz.ch';
$cfg['Lock']['test'] = 'test1234';

// Feeds settings
$cfg['Feed']['Location'] = 'feeds' . DS;
$cfg['Feed']['LocationArticle'] = $cfg['Feed']['Location'] . 'article' . DS;
$cfg['Feed']['LocationPage'] = $cfg['Feed']['Location'] . 'page' . DS;
$cfg['Feed']['LocationText'] = $cfg['Feed']['Location'] . 'text' . DS;
$cfg['Feed']['LocationComment'] = $cfg['Feed']['Location'] . 'comment' . DS;

// Response settings
$cfg['XMLResponse'] = false;

// Custom settings
$cfg['Navigation']['sebataz']['blog'] = 'blog.php';
$cfg['Navigation']['sebataz']['about me'] = "about.php";
$cfg['Navigation']['sebataz']['curriculum'] = "https://careers.stackoverflow.com/rigoni";

$cfg['Navigation']['projects']['eliza'] = "http://eliza.sebataz.ch/";

$cfg['Navigation']['comunities']['github.com'] = 'https://github.com/sebataz';
$cfg['Navigation']['comunities']['sourceforge.net'] = 'https://sourceforge.net/users/sebataz';
$cfg['Navigation']['comunities']['stackoverflow.com'] = 'http://stackoverflow.com/users/935075/sebataz';
