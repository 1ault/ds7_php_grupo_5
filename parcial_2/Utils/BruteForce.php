<?php
declare(strict_types=1);

namespace Root\Program\Utils;

use Root\Program\Utils\CryptoVault;
use Root\Program\Config\Database;

use PDO;

class BruteForce
{
    private PDO $conexion;
    private int  $max_attempts  = 5;
    private int  $block_minutes = 15;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    private function getFingerprint(): string
    {

        $fingerprint_client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';  
        $fingerprint_browser_identification = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'; 
        $fingerprint_accept_language =  $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'unknown';
        $fingerprint_accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? 'unknown';

        // Combine all fingerprint
        $combined = implode('|', [
            $fingerprint_client_ip,
            $fingerprint_browser_identification,
            $fingerprint_accept_language,
            $fingerprint_accept_encoding
        ]);

        // One single hash from all data combined
        return CryptoVault::hashMessageAuthentication(data: $combined);
    }

    public function isBlocked(): bool
    {
        $consulta = $this->conexion->prepare(
            'SELECT COUNT(*) 
             FROM login_attempts
             WHERE fingerprint = :fingerprint
             AND success = 0
             AND created_at >= NOW() - INTERVAL :minutes MINUTE'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint());
        $consulta->bindValue(':minutes', $this->block_minutes, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchColumn() >= $this->max_attempts;
    }

    public function loginAttempt(bool $success): void
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO login_attempts 
             (
                fingerprint, 
                success
            )
             VALUES 
             (
                :fingerprint, 
                :success
              )'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint());
        $consulta->bindValue(':success', $success ? 1 : 0, PDO::PARAM_INT);
        $consulta->execute();
    }


    public function registerAttempt(bool $success): void
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO register_attempts
             (
                fingerprint, 
                success
            )
             VALUES 
             (
                :fingerprint, 
                :success
              )'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint());
        $consulta->bindValue(':success', $success ? 1 : 0, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function clearAttempts(): void
    {
        $consulta = $this->conexion->prepare(
            'DELETE FROM login_attempts 
             WHERE fingerprint = :fingerprint'
        );
        $consulta->bindValue(':fingerprint', $this->getFingerprint());
        $consulta->execute();
    }
}
