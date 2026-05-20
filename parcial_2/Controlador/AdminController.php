<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Admin as ModeloAdmin;
use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;

class AdminController
{
    // ── Helpers de autorización ──────────────────────────────────────────────

    private static function requireAdmin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            $_SESSION['user_logs'] = ["Usuario no logueado."];
            header("Location: /login");
            exit;
        }

        if (($_SESSION['usuario_rol'] ?? '') !== 'rh') {
            $_SESSION['user_logs'] = ["Acceso denegado."];
            header("Location: /aspirante");
            exit;
        }
    }

    // ── Vistas ───────────────────────────────────────────────────────────────

    public static function vistaAdmin(): void
    {
        self::requireAdmin();

        $modelo     = new ModeloAdmin();
        $aspirantes = $modelo->obtenerTodos();

        require_once __DIR__ . "/../Vista/Admin/index.php";
    }

    // ── POST: actualizar estado de una solicitud ─────────────────────────────

    public static function postAdminUpdateAspirante(): void
    {
        self::requireAdmin();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $_SESSION['user_logs'] = ["No método Post."];
            header("Location: /admin");
            exit;
        }

        $result = self::logicAdminUpdateAspirante($_POST);

        $_SESSION['user_logs'] = $result['user_logs'];
        header("Location: /admin");
    }

    // ── API JSON: actualizar estado ──────────────────────────────────────────

    public static function apiAdminUpdateAspirante(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            Http::response(['err' => 'only post'], HttpStatus::NOT_FOUND);
        }

        if (empty($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'rh') {
            Http::response(['err' => 'no autorizado'], HttpStatus::NOT_FOUND);
        }

        $result = self::logicAdminUpdateAspirante($_POST);

        if (!$result['success']) {
            Http::response(['err' => $result['user_logs']], HttpStatus::BAD_REQUEST);
        }

        Http::response(['ok' => 'estado actualizado'], HttpStatus::OK);
    }

    // ── Lógica compartida ────────────────────────────────────────────────────

    public static function logicAdminUpdateAspirante(array $data): array
    {
        $usuario_id      = (int) ($data['usuario_id']      ?? 0);
        $estado_solicitud = trim($data['estado_solicitud'] ?? '');

        $estadosValidos = ['no revisado', 'considerado', 'no considerado'];

        if ($usuario_id <= 0) {
            return ['success' => false, 'user_logs' => ["ID de usuario inválido."]];
        }

        if (!in_array($estado_solicitud, $estadosValidos, true)) {
            return ['success' => false, 'user_logs' => ["Estado de solicitud inválido."]];
        }

        $modelo    = new ModeloAdmin();
        $resultado = $modelo->actualizarEstado($usuario_id, $estado_solicitud);

        if (!$resultado) {
            return ['success' => false, 'user_logs' => ["Error al actualizar el estado."]];
        }

        return ['success' => true, 'user_logs' => ["Estado actualizado correctamente."]];
    }
}
