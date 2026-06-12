<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Pelicula;
use Root\Program\Modelo\Genero;
use Root\Program\Modelo\Preferencia;

class PeliculaController
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

    // ── Vista: Home ───────────────────────────────────────────────────────────

    public static function vistaHome(): void
    {
        self::requireLogin();

        $uid = (int) $_SESSION['usuario_id'];

        $modelo_pelicula   = new Pelicula();
        $modelo_genero     = new Genero();
        $modelo_pref       = new Preferencia();

        $generos           = $modelo_genero->obtenerTodos();
        $preferencias      = $modelo_pref->obtenerPorUsuario($uid);
        $recomendaciones   = $modelo_pelicula->obtenerRecomendaciones($uid);
        $historial         = $modelo_pelicula->obtenerHistorial($uid);
        $todas             = $modelo_pelicula->obtenerTodas();

        $ultimasVistas = [];
        if (!empty($_COOKIE['ultimas_vistas'])) {
            $decoded = json_decode(base64_decode($_COOKIE['ultimas_vistas']), true);
            if (is_array($decoded)) $ultimasVistas = $decoded;
        }

        require_once __DIR__ . '/../Vista/Home/index.php';
    }

    // ── Vista: Detalle ────────────────────────────────────────────────────────

    public static function vistaDetalle(): void
    {
        self::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) { header('Location: /home'); exit; }

        $modelo   = new Pelicula();
        $pelicula = $modelo->obtenerPorId($id);
        if (!$pelicula) { header('Location: /home'); exit; }

        $uid = (int) $_SESSION['usuario_id'];
        $modelo->registrarVista($uid, $id);

        // Cookie últimas vistas
        $ultimasVistas = [];
        if (!empty($_COOKIE['ultimas_vistas'])) {
            $decoded = json_decode(base64_decode($_COOKIE['ultimas_vistas']), true);
            if (is_array($decoded)) $ultimasVistas = $decoded;
        }
        $ultimasVistas = array_filter($ultimasVistas, fn($uv) => $uv['id'] !== $id);
        array_unshift($ultimasVistas, ['id' => $id, 'titulo' => $pelicula['titulo']]);
        $ultimasVistas = array_slice(array_values($ultimasVistas), 0, 5);
        setcookie(
            'ultimas_vistas',
            base64_encode(json_encode($ultimasVistas)),
            time() + 7 * 86400, '/', '', isset($_SERVER['HTTPS']), true
        );

        $calificacion = $modelo->obtenerCalificacion($uid, $id);

        require_once __DIR__ . '/../Vista/Pelicula/detalle.php';
    }

    // ── POST: Calificar ───────────────────────────────────────────────────────

    public static function postCalificar(): void
    {
        self::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !self::validarCsrf($_POST)) {
            header('Location: /home'); exit;
        }

        $pelicula_id = (int) ($_POST['pelicula_id'] ?? 0);
        $puntuacion  = (int) ($_POST['puntuacion']  ?? 0);

        if ($pelicula_id > 0 && $puntuacion >= 1 && $puntuacion <= 5) {
            (new Pelicula())->calificar((int)$_SESSION['usuario_id'], $pelicula_id, $puntuacion);
        }

        header('Location: /pelicula/detalle?id=' . $pelicula_id);
    }

    // ── API: Listar (JSON) ────────────────────────────────────────────────────

    public static function apiListar(): void
    {
        self::requireLogin();
        header('Content-Type: application/json; charset=utf-8');

        $modelo   = new Pelicula();
        $todas    = $modelo->obtenerTodas();
        $genero   = trim($_GET['genero']   ?? '');
        $tipo     = trim($_GET['tipo']     ?? '');
        $busqueda = trim($_GET['busqueda'] ?? '');

        if ($genero   !== '') $todas = array_filter($todas, fn($p) => stripos($p['generos'] ?? '', $genero)   !== false);
        if ($tipo     !== '') $todas = array_filter($todas, fn($p) => $p['tipo'] === $tipo);
        if ($busqueda !== '') $todas = array_filter($todas, fn($p) => stripos($p['titulo'],    $busqueda) !== false);

        echo json_encode(array_values($todas), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── API: Exportar XML ─────────────────────────────────────────────────────

    public static function apiExportarXml(): void
    {
        self::requireLogin();
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="catalogo.xml"');

        $modelo = new Pelicula();
        $todas  = $modelo->obtenerTodas();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><catalogo/>');
        foreach ($todas as $p) {
            $item = $xml->addChild('pelicula');
            $item->addChild('id',          (string)$p['id']);
            $item->addChild('titulo',      htmlspecialchars($p['titulo']));
            $item->addChild('tipo',        $p['tipo']);
            $item->addChild('anio',        (string)$p['anio']);
            $item->addChild('descripcion', htmlspecialchars($p['descripcion'] ?? ''));
            $gen = $item->addChild('generos');
            foreach (explode(', ', $p['generos'] ?? '') as $g) {
                if (trim($g) !== '') $gen->addChild('genero', htmlspecialchars(trim($g)));
            }
        }
        echo $xml->asXML();
        exit;
    }
}
