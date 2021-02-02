<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Default connection group
$config['redis_default']['host'] = '139.129.93.219';   // IP address or host
$config['redis_default']['port'] = '4079';     // Default Redis port is 6379
$config['redis_default']['password'] = 'winwin';     // Can be left empty when the server does not require AUTH
$config['redis_slave']['host'] = '';
$config['redis_slave']['port'] = '6379';
$config['redis_slave']['password'] = '';
?>