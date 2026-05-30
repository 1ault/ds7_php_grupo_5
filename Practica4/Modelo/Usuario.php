<?php
namespace Root\Program\Modelo;

class Usuario{

 public static function RegistrarUsuario($usuario, $password){

  $user=["usuario" => $usuario, 
                   "password" => $password];
            $jsonString = json_encode($user);
            file_put_contents(__DIR__ . "/../Datos/" . $password . ".json", $jsonString);
 }

 public static function IniciarSesion($usuario, $password){
     $archivos= glob(__DIR__ . "/../Datos/*.json");
        foreach ($archivos as $archivo) {
            $contenido = file_get_contents($archivo);
            $usuarioRegistrado = json_decode($contenido, true);

            if ($usuarioRegistrado['usuario'] === $usuario && $usuarioRegistrado['password'] === $password) {
                return true;
                }
        }

        return false;
}
}

