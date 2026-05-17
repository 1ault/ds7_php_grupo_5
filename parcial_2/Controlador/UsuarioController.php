<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

require_once __DIR__ . "/../Modelo/Usuario.php";

class UsuarioController
{
    public $mensaje = "";

    public function registrar()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $usuario = trim($_POST["usuario"]);
            $password = trim($_POST["password"]);

            // Validar campos vacíos
            if(empty($usuario) || empty($password))
            {
                $this->mensaje =
                    "Todos los campos son obligatorios.";

                return;
            }

            // Validar usuario
            if(
                !preg_match(
                    "/^[a-zA-Z0-9_]{5,15}$/",
                    $usuario
                )
            )
            {
                $this->mensaje =
                    "El usuario debe tener entre 5 y 15 caracteres y solo puede contener letras, números y guion bajo.";

                return;
            }

            // Validar contraseña segura
            if(
                !preg_match(
                    "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{15,20}$/",
                    $password
                )
            )
            {
                $this->mensaje =
                    "La contraseña debe tener entre 15 y 20 caracteres, mayúsculas, minúsculas, números y caracteres especiales.";

                return;
            }

            $modelo = new Usuario();

            // Validar usuario repetido
            if($modelo->existeUsuario($usuario))
            {
                $this->mensaje =
                    "El usuario ya existe.";

                return;
            }

            // Cifrar contraseña
            $passwordHash = password_hash(
                $password,
                PASSWORD_BCRYPT
            );

            // Registrar usuario
            $resultado = $modelo->registrar(
                htmlspecialchars($usuario),
                $passwordHash
            );

            if($resultado)
            {
                $this->mensaje =
                    "Usuario registrado correctamente.";
            }
            else
            {
                $this->mensaje =
                    "Error al registrar.";
            }
        }
    }
}
