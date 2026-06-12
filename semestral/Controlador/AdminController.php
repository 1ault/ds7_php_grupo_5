<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Pelicula;
use Root\Program\Modelo\Genero;

class AdminController
{
    private static function validarCsrf(array $data): bool
    {
        $token = $data['csrf_token'] ?? '';
        return !empty($_SESSION['csrf_token'])
            && !empty($token)
            && hash_equals($_SESSION['csrf_token'], $token)
            && (!isset($_SESSION['csrf_token_expiry']) || $_SESSION['csrf_token_expiry'] >= time());
    }

    private static function requireAdmin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: /login'); exit;
        }
        if (($_SESSION['usuario_rol'] ?? '') !== 'administrador') {
            header('Location: /home'); exit;
        }
    }

    // ── Vista Admin ───────────────────────────────────────────────────────────

    public static function vistaAdmin(): void
    {
        self::requireAdmin();

        $peliculas    = (new Pelicula())->obtenerTodas();
        $generos      = (new Genero())->obtenerTodos();
        $estadisticas = (new Pelicula())->estadisticasGeneros();

        require_once __DIR__ . '/../Vista/Admin/index.php';
    }

    // ── POST: Crear ───────────────────────────────────────────────────────────

    public static function postCrearPelicula(): void
    {
        self::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ['Solicitud inválida.']; header('Location: /admin'); exit;
        }
        $result = self::logicGuardarPelicula($_POST);
        $_SESSION['user_logs'] = $result['user_logs'];
        header('Location: /admin');
    }

    // ── POST: Actualizar ──────────────────────────────────────────────────────

    public static function postActualizarPelicula(): void
    {
        self::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ['Solicitud inválida.']; header('Location: /admin'); exit;
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['user_logs'] = ['ID inválido.']; header('Location: /admin'); exit;
        }
        $result = self::logicGuardarPelicula($_POST, $id);
        $_SESSION['user_logs'] = $result['user_logs'];
        header('Location: /admin');
    }

    // ── POST: Eliminar ────────────────────────────────────────────────────────

    public static function postEliminarPelicula(): void
    {
        self::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ['Solicitud inválida.']; header('Location: /admin'); exit;
        }
        $id = (int) ($_POST['id'] ?? 0);
        $ok = $id > 0 && (new Pelicula())->eliminar($id);
        $_SESSION['user_logs'] = $ok ? ['Película eliminada.'] : ['Error al eliminar.'];
        header('Location: /admin');
    }

    // ── API: Importar XML ─────────────────────────────────────────────────────

    public static function apiImportarXml(): void
    {
        self::requireAdmin();
        header('Content-Type: application/json; charset=utf-8');

        $path = __DIR__ . '/../Data/peliculas.xml';
        if (!file_exists($path)) { echo json_encode(['err' => 'Archivo XML no encontrado.']); exit; }

        $xml    = simplexml_load_file($path);
        $modelo = new Pelicula();
        $mapa   = array_column((new Genero())->obtenerTodos(), 'id', 'nombre');

        $n = 0;
        foreach ($xml->pelicula as $p) {
            $ids = [];
            foreach ($p->generos->genero as $g) {
                $nombre = (string)$g;
                if (isset($mapa[$nombre])) $ids[] = $mapa[$nombre];
            }
            $modelo->insertar([
                'titulo'      => (string)$p->titulo,
                'descripcion' => (string)$p->descripcion,
                'tipo'        => (string)$p->tipo,
                'anio'        => (int)$p->anio,
                'poster_url'  => (string)$p->poster_url,
            ], $ids);
            $n++;
        }
        echo json_encode(['ok' => "Se importaron $n película(s) desde XML."]);
        exit;
    }

    // ── API: Importar JSON ────────────────────────────────────────────────────

    public static function apiImportarJson(): void
    {
        self::requireAdmin();
        header('Content-Type: application/json; charset=utf-8');

        $path = __DIR__ . '/../Data/peliculas.json';
        if (!file_exists($path)) { echo json_encode(['err' => 'Archivo JSON no encontrado.']); exit; }

        $data   = json_decode(file_get_contents($path), true);
        $modelo = new Pelicula();
        $mapa   = array_column((new Genero())->obtenerTodos(), 'id', 'nombre');

        $n = 0;
        foreach ($data as $p) {
            $ids = [];
            foreach ((array)($p['generos'] ?? []) as $nombre) {
                if (isset($mapa[$nombre])) $ids[] = $mapa[$nombre];
            }
            $modelo->insertar([
                'titulo'      => $p['titulo']      ?? '',
                'descripcion' => $p['descripcion'] ?? '',
                'tipo'        => $p['tipo']        ?? 'pelicula',
                'anio'        => (int)($p['anio']  ?? 0),
                'poster_url'  => $p['poster_url']  ?? '',
            ], $ids);
            $n++;
        }
        echo json_encode(['ok' => "Se importaron $n película(s) desde JSON."]);
        exit;
    }

    // ── Lógica compartida crear/actualizar ────────────────────────────────────

    private static function logicGuardarPelicula(array $data, int $id = 0): array
    {
        $titulo      = trim($data['titulo']      ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $tipo        = trim($data['tipo']        ?? '');
        $anio        = (int)($data['anio']       ?? 0);
        $poster_url  = trim($data['poster_url']  ?? '');
        $genero_ids  = array_filter(
            array_map('intval', (array)($data['generos'] ?? [])),
            fn($v) => $v > 0
        );

        if ($titulo === '')                               return ['success' => false, 'user_logs' => ['El título es obligatorio.']];
        if (strlen($titulo) > 255)                       return ['success' => false, 'user_logs' => ['Título demasiado largo (máx 255).']];
        if (!in_array($tipo, ['pelicula', 'serie'], true)) return ['success' => false, 'user_logs' => ['Tipo inválido.']];
        if ($anio < 1888 || $anio > (int)date('Y') + 2) return ['success' => false, 'user_logs' => ['Año inválido.']];

        $datos  = compact('titulo', 'descripcion', 'tipo', 'anio', 'poster_url');
        $modelo = new Pelicula();

        if ($id > 0) {
            $modelo->actualizar($id, $datos, array_values($genero_ids));
            return ['success' => true, 'user_logs' => ['Película actualizada correctamente.']];
        }
        $modelo->insertar($datos, array_values($genero_ids));
        return ['success' => true, 'user_logs' => ['Película creada correctamente.']];
    }
}
