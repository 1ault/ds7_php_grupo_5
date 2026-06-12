<?php
foreach (parse_ini_file(__DIR__ . '/.env') as $key => $value)
{
    putenv("$key=$value");
}

define('ENCRYPT_METHOD', "aes-256-cbc");
define('ALGO_SHA256', 'sha256');

define('ARGON2ID_ALGO', PASSWORD_ARGON2ID);
define('ARGON2ID_OPTIONS', [
    'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
    'time_cost'   => PASSWORD_ARGON2_DEFAULT_TIME_COST,
    'threads'     => PASSWORD_ARGON2_DEFAULT_THREADS,
]);
