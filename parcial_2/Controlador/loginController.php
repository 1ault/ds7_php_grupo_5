<?php

require_once __DIR__ . "/../Modelo/Usuario.php";

class LoginController
{
    public $mensaje = "";

    public function login()
    {
        session_start();

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $usuario = trim($_POST["usuario"]);
            $password = trim($_POST["password"]);

            if(empty($usuario) || empty($password))
            {
                $this->mensaje =
                    "Todos los campos son obligatorios.";

                return;
            }

            $modelo = new Usuario();

            $usuarioDB =
                $modelo->obtenerUsuario($usuario);

            if(!$usuarioDB)
            {
                $this->mensaje =
                    "Usuario o contraseña incorrectos.";

                return;
            }

            if(
                password_verify(
                    $password,
                    $usuarioDB["password"]
                )
            )
            {
                $_SESSION["usuario"] = $usuarioDB["usuario"];
                $_SESSION["usuario_id"] = $usuarioDB["id"];

                header("Location: formularioP.php");
                exit;
            }
            else
            {
                $this->mensaje =
                    "Usuario o contraseña incorrectos.";
            }
        }
    }
}