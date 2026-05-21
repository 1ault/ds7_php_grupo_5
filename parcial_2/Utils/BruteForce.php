<?php
declare(strict_types=1);

namespace Root\Program\Utils;

use Root\Program\Utils\CryptoVault;
use Root\Program\Config\Database;

use PDO;

class BruteForce
{
    private PDO $conexion;
    private int $max_attempts  = 5;
    private int $block_minutes = 2;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    /**
     * Huella basada en el nombre de usuario que intentó hacer login.
     * Así el bloqueo es POR CUENTA, no por IP.
     */
    private function getFingerprint(string $usuario = ''): string
    {
        $data = $usuario !== '' ? $usuario : ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        return CryptoVault::hashMessageAuthentication(data: $data);
    }

    public function isBlocked(string $usuario = ''): bool
    {
        $consulta = $this->conexion->prepare(
            'SELECT COUNT(*) 
             FROM login_attempts
             WHERE fingerprint = :fingerprint
             AND success = 0
             AND created_at >= NOW() - INTERVAL :minutes MINUTE'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint($usuario));
        $consulta->bindValue(':minutes', $this->block_minutes, PDO::PARAM_INT);
        $consulta->execute();
        return (int) $consulta->fetchColumn() >= $this->max_attempts;
    }

    public function loginAttempt(bool $success, string $usuario = ''): void
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO login_attempts 
             (fingerprint, success)
             VALUES (:fingerprint, :success)'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint($usuario));
        $consulta->bindValue(':success', $success ? 1 : 0, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function clearAttempts(string $usuario = ''): void
    {
        $consulta = $this->conexion->prepare(
            'DELETE FROM login_attempts 
             WHERE fingerprint = :fingerprint'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint($usuario));
        $consulta->execute();
    }

    public function registerAttempt(bool $success): void
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO register_attempts
             (fingerprint, success)
             VALUES (:fingerprint, :success)'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint());
        $consulta->bindValue(':success', $success ? 1 : 0, PDO::PARAM_INT);
        $consulta->execute();
    }
}
