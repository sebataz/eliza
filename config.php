<?php
// Privileges settings
$cfg['Roles']['eliza'] =                  'eliza';
$cfg['Permission']['SaveFile'] =         array('eliza');
$cfg['Permission']['DeleteFile'] =         array('eliza');
$cfg['Permission']['UploadFile'] =         array('eliza');
$cfg['Permission']['SaveFeed'] =           array('eliza');
$cfg['Permission']['DeleteFeed'] =         array('eliza');

// Response settings
$cfg['XMLResponse'] =                       false;              // default is 'false': 
                                                                // output is JSON formatted

// Database Settings
$cfg['Mysql']['Hostname'] =                 'localhost';
$cfg['Mysql']['Username'] =                 'admin';
$cfg['Mysql']['Password'] =                 '';
$cfg['Mysql']['Database'] =                 'test';
