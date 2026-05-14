<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Servicio as ServicioModelo;
use Root\Program\Utils\CrossSiteRequestForgery;

class Servicio
{
    public static function viewHome()
    {
        session_write_close();

        if (!isset($_SESSION['user_auth']) || $_SESSION['user_auth'] !== true) {
            header('Location: login');
            exit;
        }

        $logs = $_GET['logs'] ?? [];

        ob_start();
        require_once PATH_ROOT_VISTA_LAYOUT . '/Header.php';
        $header = ob_get_clean();

        $servicios = ServicioModelo::listar();

        ob_start();
        require_once PATH_ROOT_VISTA . '/Servicio.php';
        $main = ob_get_clean();


        // var_dump($_SESSION);
        $title = 'servicio';
    
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
        exit;
    }

    public static function apiBuy()
    {
        session_write_close();

        if (!isset($_SESSION['user_auth']) || $_SESSION['user_auth'] !== true) {
            header('Location: login');
            exit;
        }

        if (!CrossSiteRequestForgery::tokenValidate($_POST['csrf_token']))
        {
            session_unset();
            session_destroy();
            header('Location: login');
            exit;
        }

        $selected = $_POST['services'] ?? [];
        $servicios = ServicioModelo::pedir($selected);

$total = 0;

foreach ($servicios as $servicio) {
    $total += (int)$servicio['precio'];
}

// CONEXION
$conexion = \Root\Program\Config\Layer8::init();

// INSERTAR FORMULARIO DE COMPRA
$sqlCompra = "
    INSERT INTO formulario_de_compra
    (
        fecha_de_compra,
        total_a_pagar
    )
    VALUES
    (
        :fecha_de_compra,
        :total_a_pagar
    )
";

$stmtCompra = $conexion->prepare($sqlCompra);

$stmtCompra->execute([
    ':fecha_de_compra' => date('Y-m-d H:i:s'),
    ':total_a_pagar' => $total
]);

// ID FORMULARIO
$idFormulario = (int)$conexion->lastInsertId();

// RELACIONAR USUARIO CON COMPRA
$sqlRelacionUsuario = "
    INSERT INTO relacion_usuario_formulario_de_compra
    (
        id_usuario,
        id_formulario_de_compra
    )
    VALUES
    (
        :id_usuario,
        :id_formulario
    )
";

$stmtRelacionUsuario = $conexion->prepare($sqlRelacionUsuario);

$stmtRelacionUsuario->execute([
    ':id_usuario' => $_SESSION['user_id'],
    ':id_formulario' => $idFormulario
]);

// RELACIONAR SERVICIOS CON COMPRA
$sqlRelacionServicio = "
    INSERT INTO relacion_formulario_servicio
    (
        id_formulario,
        id_servicio
    )
    VALUES
    (
        :id_formulario,
        :id_servicio
    )
";

$stmtRelacionServicio = $conexion->prepare($sqlRelacionServicio);

foreach ($selected as $idServicio)
{
    $stmtRelacionServicio->execute([
        ':id_formulario' => $idFormulario,
        ':id_servicio' => $idServicio
    ]);
}

header('Location: /factura?id=' . $idFormulario);
exit;

        //header('Location: /home');
        //exit;
    }


public static function viewFactura(): void
{

    if (!isset($_SESSION['user_auth']) || $_SESSION['user_auth'] !== true)
    {
        header('Location: /login');
        exit;
    }

    $idFactura = $_GET['id'] ?? null;

    if (!$idFactura)
    {
        header('Location: /home');
        exit;
    }

    $conexion = \Root\Program\Config\Layer8::init();

    // FACTURA
    $sqlFactura = "
        SELECT *
        FROM formulario_de_compra
        WHERE id = :id
        LIMIT 1
    ";

    $stmtFactura = $conexion->prepare($sqlFactura);

    $stmtFactura->execute([
        ':id' => $idFactura
    ]);

    $factura = $stmtFactura->fetch(\PDO::FETCH_ASSOC);

    // SERVICIOS
    $sqlServicios = "
        SELECT servicio.name, servicio.precio
        FROM relacion_formulario_servicio

        INNER JOIN servicio
        ON servicio.id = relacion_formulario_servicio.id_servicio

        WHERE relacion_formulario_servicio.id_formulario = :id_formulario
    ";

    $stmtServicios = $conexion->prepare($sqlServicios);

    $stmtServicios->execute([
        ':id_formulario' => $idFactura
    ]);

    $servicios = $stmtServicios->fetchAll(\PDO::FETCH_ASSOC);

    ob_start();
    require_once PATH_ROOT_VISTA . '/Factura.php';
    $main = ob_get_clean();

    $title = 'Factura';

    require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';}
}
