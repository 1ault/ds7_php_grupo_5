<?php
declare(strict_types=1);

namespace Root\Program\Controlador;
use Root\Program\Modelo\Tareas;

class TareasController
{
    
   public static function vistaTareas(): void
    {
            $tareas = self::MostrarTareas();
        require_once __DIR__ . "/../Vista/tareas.php";
    }

    public static function postGuardarTarea(): void
    {

        if ($_SERVER["REQUEST_METHOD"] !== "POST")
        {
            $_SESSION['tarea_logs'] = ["No metodo Post."];
            header('Location: /tareas');
            exit;
        }

    $password = $_SESSION['password'] ?? '';
    $tarea = trim($_POST['tarea'] ?? '');


    if ($tarea === '') {
        $_SESSION['tarea_logs'] = ["La tarea no puede estar vacía."];
        header('Location: /tareas');
        exit;
    }  
    
    Tareas::GuardarTarea($tarea, $password);

    $_SESSION['tarea_logs'] = ["Tarea guardada correctamente."];
    header('Location: /tareas');
    exit;

    }

    public static function MostrarTareas(): array
    {
        $password = $_SESSION['password'] ?? '';

        return Tareas::MostrarTareas($password);   
    }

    public static function EliminarTarea(): void
    {
      if ($_SERVER["REQUEST_METHOD"] !== "POST")
        {
            $_SESSION['tarea_logs'] = ["No metodo Post."];
            header('Location: /tareas');
            exit;
        }

      $index = (int)($_POST['index'] ?? -1);
       $password = $_SESSION['password'] ?? '';
       
       Tareas::EliminarTarea($password, $index);

    $_SESSION['tarea_logs'] = ["Tarea eliminada correctamente."];
    header('Location: /tareas');
    exit;
        
    }

    public static function EditarTarea(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST")
        {
            $_SESSION['tarea_logs'] = ["No metodo Post."];
            header('Location: /tareas');
            exit;
        }

            $index = (int)($_POST['index'] ?? -1);
            $tarea = trim($_POST['tarea'] ?? '');
            $estado = $_POST['estado'] ?? '';
           $password = $_SESSION['password'] ?? '';

           Tareas::EditarTarea($password, $index, $tarea, $estado);

            $_SESSION['tarea_logs'] = ["Tarea editada correctamente."];
            header('Location: /tareas');
            exit;
    }

}

