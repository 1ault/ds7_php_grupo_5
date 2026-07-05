<?php
declare(strict_types=1);

namespace Root\Program\Config;

use PDO;
use PDOException;

class Layer8
{
    private static ?PDO $layer8 = null;

    public static function get(): PDO
    {
        if (self::$layer8 === null || !self::isAlive()) {
            self::connect();
        }

        return self::$layer8;
    }

    public static function connect(): void
    {
        $dsn = null;
        $user = null;
        $password= null;

        switch (PHP_OS_FAMILY) {
            case "Windows":
                $dsn = getenv("DB_WINDOWS_DSN");
                $user = getenv("DB_WINDOWS_USERNAME");
                $password = getenv("DB_WINDOWS_PASSWORD");
                break;

            case "Linux":
                $dsn = getenv("DB_WINDOWS_DSN");
                $user = getenv("DB_LINUX_USERNAME");
                $password = getenv("DB_LINUX_PASSWORD");
                break;

            case "BSD":
                $dsn = getenv("DB_BSD_DNS");
                $user = getenv("DB_BSD_USERNAME");
                $password = getenv("DB_BSD_PASSWORD");
                break;

            default:
                ASSERT_OR_PANIC(false, "TODO: unknown host system family");
                break;
        }

        ASSERT_OR_PANIC($dsn !== false, "DB_DSN_BSD is not set");
        ASSERT_OR_PANIC($user !== false, "DB_USER is not set");
        ASSERT_OR_PANIC($password !== false, "DB_PASSWORD is not set");

        $conexion = new PDO($dsn, $user, $password);

        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::$layer8 = $conexion;
    }

    private static function isAlive(): bool
    {
        try {
            self::$layer8->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            self::$layer8 = null;
            return false;
        }
    }

    private function __construct() {}
    private function __clone() {}
}
