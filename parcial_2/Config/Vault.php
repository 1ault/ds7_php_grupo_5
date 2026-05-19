<?php
foreach (parse_ini_file(__DIR__ . '/.env.example') as $key => $value)
{
    putenv("$key=$value");
}

// https://www.php.net/manual/en/function.openssl-encrypt.php
define('ENCRYPT_METHOD', "aes-256-cbc");


define('ALGO_SHA256', 'sha256');

// https://www.php.net/manual/en/function.password-hash.php
// https://www.php.net/manual/en/password.constants.php#constant.password-argon2id
// https://www.php.net/manual/en/function.password-verify.php
// https://www.php.net/manual/en/function.password-needs-rehash.php

// PASSWORD_ARGON2ID = "argon2id"
define('ARGON2ID_ALGO', PASSWORD_ARGON2ID);
define(
"ARGON2ID_OPTIONS",
[
    // memory_cost (int) - Maximum memory (in kibibytes) that may be used to compute the Argon2 hash. Defaults to PASSWORD_ARGON2_DEFAULT_MEMORY_COST.
    // Maximum memory (in kibibytes) that may be used to compute the Argon2 hash. Defaults to
    // PASSWORD_ARGON2_DEFAULT_MEMORY_COST = 65536 KB = 64 MB;
    // 10000 KB = 1 MB;
    'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,

    // time_cost (int) - Maximum amount of time it may take to compute the Argon2 hash. Defaults to PASSWORD_ARGON2_DEFAULT_TIME_COST.
    // Maximum amount of time it may take to compute the Argon2 hash. Defaults to
    // (Number of iterations)
    // PASSWORD_ARGON2_DEFAULT_TIME_COST = 4;
    // Example:
    // stage0 = password + salt
    // memory = init_memory(stage0)
    // for (iter in time_cost) {
    //    memory = blend(memory)
    // }
    // hash = compress(memory)
    'time_cost'   => PASSWORD_ARGON2_DEFAULT_TIME_COST,

    //threads (int) - Number of threads to use for computing the Argon2 hash. Defaults to PASSWORD_ARGON2_DEFAULT_THREADS.
    // PASSWORD_ARGON2_DEFAULT_THREADS = 1
    // CPU
    'threads'     => PASSWORD_ARGON2_DEFAULT_THREADS,
]
);
