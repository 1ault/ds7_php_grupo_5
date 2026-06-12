<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Preferencia;
use Root\Program\Modelo\Genero;

class PreferenciaController
{
    private static function requireLogin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: /login'); exit;
        }
    }

    private static function validarCsrf(array $data): bool
    {
        $token = $data['csrf_token'] ?? '';
        return !empty($_SESSION['csrf_token'])
            && !empty($token)
            && hash_equals($_SESSION['csrf_token'], $token)
            && (!isset($_SESSION['csrf_token_expiry']) || $_SESSION['csrf_token_expiry'] >= time());
    }

    public static function vistaPerfil(): void
    {
        self::requireLogin();

        $uid           = (int) $_SESSION['usuario_id'];
        $generos       = (new Genero())->obtenerTodos();
        $preferencias  = (new Preferencia())->obtenerPorUsuario($uid);

        require_once __DIR__ . '/../Vista/Perfil/index.php';
    }

    public static function postGuardar(): void
    {
        self::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ['Solicitud inválida.'];
            header('Location: /perfil'); exit;
        }

        $uid        = (int) $_SESSION['usuario_id'];
        $genero_ids = array_filter(
            array_map('intval', (array)($_POST['generos'] ?? [])),
            fn($v) => $v > 0
        );

        (new Preferencia())->guardar($uid, array_values($genero_ids));

        setcookie(
            'cm_usuario',
            htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
            time() + 30 * 86400, '/', '', isset($_SERVER['HTTPS']), false
        );

        $_SESSION['user_logs'] = ['¡Preferencias guardadas correctamente!'];
        header('Location: /perfil');
    }
}
