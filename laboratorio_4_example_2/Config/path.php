<?php
define('PATH_ROOT', dirname(__DIR__, 1));
define('PATH_ROOT_VISTA', PATH_ROOT . '/Vista');
define('PATH_ROOT_VISTA_LAYOUT', PATH_ROOT . '/Vista/Layout');


$env = parse_ini_file(PATH_ROOT . '/Config/.env');
define('DB_USUARIO',   $env['DB_USUARIO']);
define('DB_CONTRASENA',   $env['DB_CONTRASENA']);
define('DB_DSN',   $env['DB_DSN']);
