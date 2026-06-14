<?php

if (!function_exists('safe_query')) {
    die('Access denied');
}

global $str, $modulname, $version, $plugin;

$modulname = 'lastlogin';
$version = isset($plugin['version']) ? (string)$plugin['version'] : ($version ?? '0.0.0');
$str = 'LastLogin';

require __DIR__ . '/install.php';
