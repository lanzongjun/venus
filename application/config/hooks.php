<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['repertory_daily_1'] = array(
    'class'    => 'repertoryDaily',
    'function' => 'run',
    'filename' => 'repertoryDaily.php',
    'filepath' => 'hooks',
    'params'   => array('shop_id' => 1)
);

$hook['repertory_daily_2'] = array(
    'class'    => 'repertoryDaily',
    'function' => 'run',
    'filename' => 'repertoryDaily.php',
    'filepath' => 'hooks',
    'params'   => array('shop_id' => 2)
);

$hook['repertory_daily_3'] = array(
    'class'    => 'repertoryDaily',
    'function' => 'run',
    'filename' => 'repertoryDaily.php',
    'filepath' => 'hooks',
    'params'   => array('shop_id' => 3)
);