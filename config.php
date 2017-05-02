<?php

/**
 * config.php: this file holds the global configuration for eliza and your 
 * website/application/service.
 *
 * You can if you wish edit the below file, however I recommend making a copy
 * of this file and save it in your document root, this way eliza will overwrite
 * the below configuration with your own config.php. If a setting is not defined 
 * in your configuration the default below is used.
 **/

// Privileges settings
$cfg['Roles']['eliza'] =                    'eliza';

$cfg['Permission']['SaveFile'] =            array('eliza');
$cfg['Permission']['DeleteFile'] =          array('eliza');
$cfg['Permission']['UploadFile'] =          array('eliza');
$cfg['Permission']['SaveFeed'] =            array('eliza');
$cfg['Permission']['DeleteFeed'] =          array('eliza');

// Autoload path for PHP classes
$cfg['PathToClass'] =                       ''; /* you can set this variable if you wish to have a your own defined classes autoloaded */

// Response settings
$cfg['XMLResponse'] =                       false; /* default is 'false': output is JSON formatted */

// Database Settings
$cfg['Mysql']['Hostname'] =                 'localhost';
$cfg['Mysql']['Username'] =                 'admin';
$cfg['Mysql']['Password'] =                 '';
$cfg['Mysql']['Database'] =                 'test';
