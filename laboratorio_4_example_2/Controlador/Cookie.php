<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

class Cookie
{
    public static function set()
    {
        setCookie("login", $_POST['nombre'], time() + 300, "/");
        header('Location: /home');
        exit;
    }

    public static function free()
    {
        setCookie("login", "", time() - 3600, "/");
        header('Location: /');
        exit;
    }

}
