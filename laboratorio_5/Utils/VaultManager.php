<?php
declare(strict_types=1);

namespace Root\Program\Utils;

class VaultManager
{
    public static function getSecretKey1(): string {
        return $_ENV['SECRET_KEY_1'];

    }

    public static function getSecretKey2(): string {
        return $_ENV['SECRET_KEY_2'];
    }

    public static function getSecretKey3(): string {
        return $_ENV['SECRET_KEY_3'];
    }
}
