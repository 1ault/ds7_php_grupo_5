<?php
foreach (parse_ini_file(__DIR__ . '/.env.example') as $key => $value)
{
    putenv("$key=$value");
}
