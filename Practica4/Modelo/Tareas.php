<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

class Tareas
{
    
   public static function GuardarTarea($tarea, $password)
    {

    $ruta = __DIR__ . "/../Datos/" . $password . ".json";
    $datos = json_decode(file_get_contents($ruta), true);

    if (!isset($datos['tareas'])) {
    $datos['tareas'] = [];
    }

    $datos['tareas'][] = [
    "tarea" => $tarea,
    "estado" => "Pendiente"
     ];
    file_put_contents($ruta,json_encode($datos, JSON_PRETTY_PRINT));
    }

    public static function MostrarTareas($password): array
    {
        $ruta = __DIR__ . "/../Datos/" . $password . ".json";

        if (!file_exists($ruta)) {
            return [];
        }

        $datos = json_decode(file_get_contents($ruta), true);
        return $datos['tareas'] ?? [];
    }

    public static function EliminarTarea($password, $index)
    {
      
$ruta = __DIR__ . "/../Datos/" . $password . ".json";
 if (!file_exists($ruta)) {
    return;
}

    $datos = json_decode(file_get_contents($ruta), true);

    unset($datos['tareas'][$index]);

    // Reacomoda los índices
    $datos['tareas'] = array_values($datos['tareas']);

    file_put_contents($ruta,json_encode($datos, JSON_PRETTY_PRINT));
    }

    public static function EditarTarea($password, $index, $tarea, $estado)
    {
        $ruta = __DIR__ . "/../Datos/" . $password . ".json";

        if (!file_exists($ruta)) {
            return;
        }

        $datos = json_decode(file_get_contents($ruta), true);
        $datos["tareas"][$index]["tarea"] = $tarea;
        $datos["tareas"][$index]["estado"] = $estado;

            file_put_contents($ruta,json_encode($datos, JSON_PRETTY_PRINT));

    }

}