<?php

// Main settings
$cfg['Lock']['test'] = 'test1234';

// Feeds settings
$cfg['Feed']['Location'] = 'feeds' . DS;
$cfg['Feed']['LocationArticle'] = $cfg['Feed']['Location'] . 'article' . DS;
$cfg['Feed']['LocationPage'] = $cfg['Feed']['Location'] . 'page' . DS;
$cfg['Feed']['LocationText'] = $cfg['Feed']['Location'] . 'text' . DS;
$cfg['Feed']['LocationComment'] = $cfg['Feed']['Location'] . 'comment' . DS;

// Database Settings
$cfg['Mysql']['Hostname'] = 'localhost';
$cfg['Mysql']['Username'] = 'admin';
$cfg['Mysql']['Password'] = '';
$cfg['Mysql']['Database'] = 'test';

// Response settings
$cfg['XMLResponse'] = false;