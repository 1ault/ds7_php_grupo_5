<?php
declare(strict_types=1);
echo getenv('DB_HOST');

require_once __DIR__ . '/vendor/autoload.php';

use Root\Program\Routers\Web;
use Root\Program\Routers\Api;

$web = new Web();
$web->run();

$api = new Api();
$api->run();
