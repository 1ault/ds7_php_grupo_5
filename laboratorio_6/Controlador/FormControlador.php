<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;
use Root\Program\Modelo\Producto;

class FormControlador
{
    public static function vista(): void
    {
        $id = (int)($_POST["id"] ?? -1);
        $nombre = trim($_POST['nombre'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $precio = trim($_POST['precio'] ?? '');
        $stock = trim($_POST['stock'] ?? '');
        $tipo_de_producto = trim($_POST['tipo_de_producto'] ?? ''); 

        $producto = new Producto(
            id: Producto::getPathCouterFiles(),
            nombre: $nombre,
            marca: $marca,
            precio: (float)$precio,
            stock: (int)$stock,
            tipo_de_producto: $tipo_de_producto
        );

        if ($id > -1 && Producto::existsFile($id))
        {
            if (
                empty($nombre) &&
                empty($marca) &&
                empty($precio) &&
                empty($stock) &&
                empty($tipo_de_producto)
            )
            {
                $data = Producto::loadFile($id);
                
                extract($data);
                require_once __DIR__ . "/../Vista/Form.php";
                exit;
            }

            Producto::editFile($id, json_encode($producto));

            require_once __DIR__ . "/../Vista/Form.php";
            exit;
        }

        if (
            !empty($nombre) &&
            !empty($marca) &&
            !empty($precio) &&
            !empty($stock) &&
            !empty($tipo_de_producto)
        )
        { 
            $data = Producto::saveFile(json_encode($producto));
            
            extract($data);
            require_once __DIR__ . "/../Vista/Form.php";
            exit;
        }

        require_once __DIR__ . "/../Vista/Form.php";
        exit;
    }
}
