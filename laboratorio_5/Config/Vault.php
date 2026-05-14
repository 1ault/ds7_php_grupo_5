<?php
foreach (parse_ini_file(__DIR__ . '/.env') as $key => $value)
{
    putenv("$key=$value");
}
