<?php

define('PUBLIC_PATH', __DIR__);
define('ROOT', dirname(PUBLIC_PATH));
define('DATA_PATH', ROOT . '/data/app.db');

require ROOT . '/app/autoload.php';

use App\Kernel;

$kernel = new Kernel(DATA_PATH);
$kernel->handleRequest();
