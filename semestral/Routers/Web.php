<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\UsuarioController;
use Root\Program\Controlador\PeliculaController;
use Root\Program\Controlador\PreferenciaController;
use Root\Program\Controlador\AdminController;

class Web
{
    public static function page(string $uri): void
    {
        // ── Normalizar la ruta ────────────────────────────────────────────────
        // Quita el prefijo del subdirectorio XAMPP si existe.
        // En producción (dominio propio) REQUEST_URI ya es /ruta; en XAMPP es
        // /semestral/Public/ruta — eliminamos ese prefijo para que las rutas
        // siempre comiencen con /algo.

        // Prefijos posibles según cómo XAMPP resuelva la URL
        /*
        $prefijos = [
            '/semestral/Public',
            '/semestral/public',
            '/semestral',
        ];

        foreach ($prefijos as $prefijo) {
            if (str_starts_with($uri, $prefijo)) {
                $uri = substr($uri, strlen($prefijo));
                break;
            }
        }
         */

        // Normalizar: vacío o solo barras → "/"
        //$uri = rtrim($uri, '/');
        //if ($uri === '') $uri = '/';

        switch ($uri) {
            // ── Raíz ──────────────────────────────────────────────────────
            case "":
            case "/":
                if (!empty($_SESSION["usuario_id"])) {
                    header("Location: /home");
                    exit();
                }
                UsuarioController::vistaLogin();
                exit();

            // ── Registro ──────────────────────────────────────────────────
            case "/registro":
                UsuarioController::vistaRegistro();
                exit();

            case "/post/usuario/registro":
                UsuarioController::postRegistro();
                exit();

            // ── Login / Logout ─────────────────────────────────────────────
            case "/login":
                UsuarioController::vistaLogin();
                exit();

            case "/post/usuario/login":
                UsuarioController::postLogin();
                exit();

            case "/post/usuario/logout":
                UsuarioController::postLogout();
                exit();

            // ── Home / Catálogo ────────────────────────────────────────────
            case "/home":
                PeliculaController::vistaHome();
                exit();

            // ── Detalle de película ────────────────────────────────────────
            case "/pelicula/detalle":
                PeliculaController::vistaDetalle();
                exit();

            // ── Calificar ──────────────────────────────────────────────────
            case "/post/pelicula/calificar":
                PeliculaController::postCalificar();
                exit();

            // ── Perfil / Preferencias ──────────────────────────────────────
            case "/perfil":
                PreferenciaController::vistaPerfil();
                exit();

            case "/post/preferencias/guardar":
                PreferenciaController::postGuardar();
                exit();

            // ── Admin ──────────────────────────────────────────────────────
            case "/admin":
                AdminController::vistaAdmin();
                exit();

            case "/post/admin/pelicula/crear":
                AdminController::postCrearPelicula();
                exit();

            case "/post/admin/pelicula/actualizar":
                AdminController::postActualizarPelicula();
                exit();

            case "/post/admin/pelicula/eliminar":
                AdminController::postEliminarPelicula();
                exit();

            // ── 404 ────────────────────────────────────────────────────────
            default:
                break;
        }
    }

    public static function post(string $uri): void
    {
        switch ($uri) {
            // ── Registro ──────────────────────────────────────────────────

            case "/post/usuario/registro":
                UsuarioController::postRegistro();
                exit();

            // ── Login / Logout ─────────────────────────────────────────────
            case "/post/usuario/login":
                UsuarioController::postLogin();
                exit();

            case "/post/usuario/logout":
                UsuarioController::postLogout();
                exit();

            // ── Calificar ──────────────────────────────────────────────────
            case "/post/pelicula/calificar":
                PeliculaController::postCalificar();
                exit();

            // ── Perfil / Preferencias ──────────────────────────────────────

            case "/post/preferencias/guardar":
                PreferenciaController::postGuardar();
                exit();

            // ── Admin ──────────────────────────────────────────────────────
            case "/admin":
                AdminController::vistaAdmin();
                exit();

            case "/post/admin/pelicula/crear":
                AdminController::postCrearPelicula();
                exit();

            case "/post/admin/pelicula/actualizar":
                AdminController::postActualizarPelicula();
                exit();

            case "/post/admin/pelicula/eliminar":
                AdminController::postEliminarPelicula();
                exit();

            // ── 404 ────────────────────────────────────────────────────────
            default:
                break;
        }
    }
}
