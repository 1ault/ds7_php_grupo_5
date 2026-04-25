<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Libro;

class LibroController
{

    public static function home()
    {
        echo "En contruccion";
        header('Location: /listar');
        exit;
    }

    public static function crear()
    {     
        $logs = $_GET['logs'] ?? [];

        ob_start();
        require_once PATH_ROOT_VISTA . '/Crear.php';
        $main = ob_get_clean();
        
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

    public static function editar()
    { 

        $logs = $_GET['logs'] ?? [];

        ob_start();
        require_once PATH_ROOT_VISTA . '/Editar.php';
        $main = ob_get_clean();
        
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }


    public static function libro_crear()
    {    

        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $autor = trim(filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $fecha = trim(filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $categoria = trim(filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $img = trim(filter_input(INPUT_POST, 'img', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

        $logs = [];

        if (empty($nombre)) {
            $logs['nombre'] = 'empty nombre';
        }

        if (empty($autor)) {
            $logs['autor'] = 'empty autor';
        }

        if (empty($fecha)) {
            $logs['fecha'] = 'empty fecha';
        }

        if (empty($categoria)) {
            $logs['categoria'] = 'empty categoria';
        }

        if (empty($img)) {
            $logs['img'] = 'empty img';
        }

        if (!empty($logs)) {
            $query = http_build_query([
                'logs' => $logs,
            ]);

            header('Location: /crear?' . $query);
            exit;
        }

        $libro = new Libro
        (
            -1,
            $nombre,
            $autor,
            $fecha,
            $categoria,
            $img
        );


        $libro->insert();

        header('Location: /crear');
        exit;
    }

    public static function libro_editar()
    {    

        //$id = $_POST['id'] ?? '';
        //$nombre = $_POST['nombre'] ?? '';
        //$autor = $_POST['autor'] ?? '';
        //$fecha = $_POST['fecha'] ?? '';
        //$categoria = $_POST['categoria'] ?? '';
        //$img = $_POST['img'] ?? '';

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $autor = trim(filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $fecha = trim(filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $categoria = trim(filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $img = trim(filter_input(INPUT_POST, 'img', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

        $logs = [];

        if ($id === false || $id === null) {
            $logs['id'] = 'id no es valido';
        }

        if (empty($nombre)) {
            $logs['nombre'] = 'empty nombre';
        }

        if (empty($autor)) {
            $logs['autor'] = 'empty autor';
        }

        if (empty($fecha)) {
            $logs['fecha'] = 'empty fecha';
        }

        if (empty($categoria)) {
            $logs['categoria'] = 'empty categoria';
        }

        if (empty($img)) {
            $logs['img'] = 'empty img';
        }

        if (!empty($logs)) {
            $query = http_build_query([
                'logs' => $logs,
            ]);

            header('Location: /editar?' . $query);
            exit;
        }


        $libro = new Libro
        (
            $id,
            $nombre,
            $autor,
            $fecha,
            $categoria,
            $img
        );

        $libro->edit();

        header('Location: /editar');
        exit;
    }

    public static function listar()
    {
        $libros_modelo = new Libro();

        $libros = $libros_modelo->listar();
        
        ob_start();
        require_once PATH_ROOT_VISTA . '/Listar.php';
        $main = ob_get_clean();
    
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }
}
