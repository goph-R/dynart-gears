<?php

//$startTime = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';
Dynart\Minicore\Framework::run('\Dynart\Gears\GearsAdminApp', ['../config.ini.dev.php', 'config.ini.dev.php']);

//$endTime = microtime(true);
//echo '<!-- Time: '.round($endTime - $startTime, 3).'s | Memory: '.round(memory_get_usage() / 1024).'kB -->';