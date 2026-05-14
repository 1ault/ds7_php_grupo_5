<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Layer8;
use Root\Program\Modelo\UsuarioInfo;
use Root\Program\Utils\Encrypted;

use PDO;

class Usuario
{
    private readonly int  $id;

    public function __construct
    (        
        private readonly string $name,
        private readonly string $password,
        private readonly string $email,
        private readonly string $indexing_email
    )
    {
    }

    public function setId(int $id): void
    {
        assert(!isset($this->id), "[setId()]: calle twice => code error");
        $this->id = $id;
    }

    public function login(): array|false
{

    $layer8 = Layer8::Init();

    $consulta = $layer8->prepare(
        'SELECT id, name, email, password 
         FROM usuario
         WHERE indexing_email = :indexing_email
         LIMIT 1;'
    );

    $consulta->bindValue(':indexing_email', $this->indexing_email);

    $consulta->execute();

    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        return false;
    }

    $verify_password =
        Encrypted::verifyPassword
        (
            password: $this->password,
            hash: $usuario['password']
        );

    if (!$verify_password) {
        return false;
    }

    $data_name = Encrypted::securedDecrypt(
        data: $usuario['name']
    );

    $data_email = Encrypted::securedDecrypt(
        data: $usuario['email']
    );

    session_unset();
    session_destroy();

    session_start();
    session_regenerate_id(true);

    $_SESSION['user_email'] = $data_email;
    $_SESSION['user_name'] = $data_name;
    $_SESSION['user_auth'] = true;

    // DATOS DEL USUARIO
    return [
        'id' => $usuario['id'],
        'name' => $data_name,
        'email' => $data_email
    ];
}

    public function insert(): int
    {
        $layer8 = Layer8::Init();

        // Prepare la operacion INSERT
        $consulta = $layer8->prepare(
            'INSERT INTO usuario
            (
                name, 
                email,
                password,
                indexing_email
            )
                VALUES 
            (
                :name, 
                :email,
                :password,
                :indexing_email
            );
            '
        );

        // Vincular las parametros
        $consulta->bindValue(':name', $this->name);
        $consulta->bindValue(':email', $this->email);
        $consulta->bindValue(':password', $this->password);
        $consulta->bindValue(':indexing_email', $this->indexing_email);
        
        // Ejecutar
        $consulta->execute();

        // Obtener resultados
        return $layer8->lastInsertId();
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }

}
