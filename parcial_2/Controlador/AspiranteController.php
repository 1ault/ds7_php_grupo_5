<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Aspirante;

class AspiranteController
{
    public string $mensaje = "";

    public function guardar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return;
        }

        $cedula = trim($_POST["cedula"] ?? "");
        $nombre = trim($_POST["nombre"] ?? "");
        $apellido = trim($_POST["apellido"] ?? "");
        $estado_civil = trim($_POST["estado_civil"] ?? "");
        $genero = trim($_POST["genero"] ?? "");
        $tipo_sangre = trim($_POST["tipo_sangre"] ?? "");
        $fecha_nacimiento = trim($_POST["fecha_nacimiento"] ?? "");
        $nacionalidad = trim($_POST["nacionalidad"] ?? "");
        $telefono = trim($_POST["telefono"] ?? "");
        $residencia = trim($_POST["residencia"] ?? "");
        $correo = trim($_POST["correo"] ?? "");

        if (
            empty($cedula) || empty($nombre) || empty($apellido) ||
            empty($genero) || empty($fecha_nacimiento) ||
            empty($nacionalidad) || empty($telefono) ||
            empty($residencia) || empty($correo)
        ) {
            $this->mensaje = "Complete todos los campos obligatorios.";
            return;
        }

        if (!preg_match("/^([0-9]{1,2}-[0-9]{1,4}-[0-9]{1,6}|[PEEN]-[0-9]{1,4}-[0-9]{1,6}|[A-Z0-9]{6,15})$/", $cedula)) {
            $this->mensaje = "Cédula o pasaporte inválido.";
            return;
        }

        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$/", $nombre)) {
            $this->mensaje = "Nombre inválido.";
            return;
        }

        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$/", $apellido)) {
            $this->mensaje = "Apellido inválido.";
            return;
        }

        $estadosValidos = ["", "Soltero", "Casado"];

        if (!in_array($estado_civil, $estadosValidos, true)) {
            $this->mensaje = "Estado civil inválido.";
            return;
        }

        $generosValidos = ["Masculino", "Femenino"];

        if (!in_array($genero, $generosValidos, true)) {
            $this->mensaje = "Género inválido.";
            return;
        }

        $tiposSangreValidos = [
            "",
            "A+",
            "A-",
            "B+",
            "B-",
            "AB+",
            "AB-",
            "O+",
            "O-"
        ];

        if (!in_array($tipo_sangre, $tiposSangreValidos, true)) {
            $this->mensaje = "Tipo de sangre inválido.";
            return;
        }

        if (!strtotime($fecha_nacimiento)) {
            $this->mensaje = "Fecha de nacimiento inválida.";
            return;
        }

        $edad = date_diff(
            date_create($fecha_nacimiento),
            date_create("today")
        )->y;

        if ($fecha_nacimiento > date("Y-m-d")) {
            $this->mensaje = "La fecha no puede ser futura.";
            return;
        }

        if ($edad < 18) {
            $this->mensaje = "El aspirante debe ser mayor de edad.";
            return;
        }

        $nacionalidadesValidas = [
          "Afgana",
          "Albanesa",
          "Alemana",
          "Andorrana",
          "Angoleña",
          "Antiguana",
          "Argentina",
          "Armenia",
          "Australiana",
          "Austriaca",
          "Azerbaiyana",
          "Bahameña",
          "Bareiní",
          "Bangladesí",
          "Barbadense",
          "Belga",
          "Beliceña",
          "Beninesa",
          "Bielorrusa",
          "Boliviana",
          "Bosnia", 
          "Botsuana",
          "Brasileña",
          "Británica",
          "Bruneana",
          "Búlgara",
          "Burkinesa",
          "Burundesa",
          "Camboyana",
          "Camerunesa",
          "Canadiense",
          "Chadiana",
          "Chilena",
          "China",
          "Chipriota",
          "Colombiana",
          "Congoleña",
          "Costarricense",
          "Croata",
          "Cubana",
          "Danesa",
          "Dominicana",
          "Ecuatoriana",
          "Egipcia",
          "Salvadoreña",
          "Emiratí",
          "Eritrea",
          "Eslovaca",
          "Eslovena",
          "Española",
          "Estadounidense",
          "Estonia",
          "Etíope",
          "Filipina",
          "Finlandesa",
          "Francesa",
          "Gabonesa",
          "Gambiana",
          "Georgiana",
          "Ghanesa",
          "Granadina",
          "Griega",
          "Guatemalteca",
          "Guineana",
          "Guyonesa",
          "Haitiana",
          "Hondureña",
          "Húngara",
          "India",
          "Indonesia",
          "Iraní",
          "Iraquí",
          "Irlandesa",
          "Islandesa",
          "Israelí",
          "Italiana",
          "Jamaiquina",
          "Japonesa",
          "Jordana",
          "Kazaja",
          "Keniana",
          "Kirguisa",
          "Kiribatiana",
          "Kuwaití",
          "Laosiana",
          "Lesotense",
          "Letona",
          "Libanesa",
          "Liberiana",
          "Libia",
          "Liechtensteiniana",
          "Lituana",
          "Luxemburguesa",
          "Macedonia",
          "Malasia",
          "Malauí",
          "Maldiva",
          "Maliense",
          "Maltesa",
          "Marroquí",
          "Mauriciana",
          "Mexicana",
          "Moldava",
          "Monegasca",
          "Mongola",
          "Namibia",
          "Nepalesa",
          "Nicaragüense",
          "Nigeriana",
          "Noruega",
          "Neozelandesa",
          "Panameña",
          "Paraguaya",
          "Peruana",
          "Polaca",
         "Portuguesa",
         "Qatarí",
         "Rumana",
         "Rusa",
         "Senegalesa",
         "Serbia",
         "Singapurense",
         "Siria",
         "Somalí",
         "Sudafricana",
         "Sueca",
         "Suiza",
         "Tailandesa",
         "Tanzana",
         "Tunecina",
         "Turca",
         "Ucraniana",
         "Ugandesa",
         "Uruguaya",
         "Venezolana",
         "Vietnamita",
         "Yemení",
        "Zambiana",
        "Zimbabuense"
    ];

if (
    !in_array(
        $nacionalidad,
        $nacionalidadesValidas,
        true
    )
) {
    $this->mensaje =
        "Nacionalidad inválida.";

    return;
}


if (
    !in_array(
        $nacionalidad,
        $nacionalidadesValidas,
        true
    )
) {
    $this->mensaje =
        "Nacionalidad inválida.";

    return;
}

        if (!preg_match("/^6[0-9]{3}-[0-9]{4}$/", $telefono)) {
            $this->mensaje = "Teléfono inválido. Use el formato 6123-4567.";
            return;
        }

        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s#.,-]{5,100}$/", $residencia)) {
            $this->mensaje = "Residencia inválida.";
            return;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->mensaje = "Correo inválido.";
            return;
        }

        $modelo = new Aspirante();

        $resultado = $modelo->guardar([
            "usuario_id" => $_SESSION["usuario_id"],
            "cedula" => $cedula,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "estado_civil" => $estado_civil,
            "genero" => $genero,
            "tipo_sangre" => $tipo_sangre,
            "fecha_nacimiento" => $fecha_nacimiento,
            "nacionalidad" => $nacionalidad,
            "telefono" => $telefono,
            "residencia" => $residencia,
            "correo" => $correo
        ]);

        if ($resultado) {
            $this->mensaje = "Solicitud guardada correctamente.";
        } else {
            $this->mensaje = "Error al guardar la solicitud.";
        }
    }
}
