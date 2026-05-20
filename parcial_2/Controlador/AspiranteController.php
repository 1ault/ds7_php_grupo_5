<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Aspirante;

use DateTime;

class AspiranteController
{

    public static function vistaAspirante(): void
    {
        if (empty($_SESSION['usuario_id'])) 
        {
            $_SESSION['user_logs'] = ["Usuario no logueado."];
            header("Location: /login");
            exit;
        }

        // Cargar solicitud existente (si la tiene) para mostrarla en la vista
        $modelo = new Aspirante();
        $solicitud = $modelo->obtenerPorUsuario((int) $_SESSION['usuario_id']);

        require_once __DIR__ . "/../Vista/Aspirante/index.php";
    }


    public static function postGuardarAspirante(): void
    {
        if (empty($_SESSION['usuario_id'])) 
        {
            $_SESSION['user_logs'] = ["Usuario no logueado."];
            header("Location: /login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") 
        {
            $_SESSION['user_logs'] = ['success' => false, 'user_logs' => ["No metodo Post."]];
            header('Location: /aspirante');
            exit;
        }

        $result = self::logicGuardarAspirante($_POST);

        if (!$result['success']) 
        {
            $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /aspirante');
            exit;
        }

        $_SESSION['user_logs'] = $result['user_logs'];
        header('Location: /aspirante');
    }


    public static function postUpdateAspirante(): void
    {
        if (empty($_SESSION['usuario_id'])) 
        {
            $_SESSION['user_logs'] = ["Usuario no logueado."];
            header("Location: /login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") 
        {
            $_SESSION['user_logs'] = ["No método Post."];
            header('Location: /aspirante');
            exit;
        }

        $result = self::logicUpdateAspirante($_POST);

        $_SESSION['user_logs'] = $result['user_logs'];
        header('Location: /aspirante');
    }


    public static function logicUpdateAspirante(array $data): array
    {
        // Reutiliza la misma validación de guardar pero hace UPDATE
        $validated = self::validarCampos($data);

        if (!$validated['success']) {
            return $validated;
        }

        $campos = $validated['campos'];

        $modelo = new Aspirante();
        $resultado = $modelo->actualizar(
            (int) $_SESSION['usuario_id'],
            $campos
        );

        if (!$resultado) {
            return ['success' => false, 'user_logs' => ["Error al actualizar la solicitud."]];
        }

        return ['success' => true, 'user_logs' => ["Solicitud actualizada correctamente."]];
    }


    public static function logicGuardarAspirante(array $data): array
    {
        if (empty($_SESSION['usuario_id'])) 
        {
            $_SESSION['user_logs'] = ["Usuario no logueado."];
            header("Location: /login");
            exit;
        }

        $validated = self::validarCampos($data);

        if (!$validated['success']) {
            return $validated;
        }

        $campos = $validated['campos'];

        $modelo_aspirante = new Aspirante();

        $resultado = $modelo_aspirante->guardar([
            "usuario_id"      => $_SESSION["usuario_id"],
            "cedula"          => $campos['cedula'],
            "nombre"          => $campos['nombre'],
            "apellido"        => $campos['apellido'],
            "estado_civil"    => $campos['estado_civil'],
            "genero"          => $campos['genero'],
            "tipo_sangre"     => $campos['tipo_sangre'],
            "fecha_nacimiento"=> $campos['fecha_nacimiento'],
            "nacionalidad"    => $campos['nacionalidad'],
            "telefono"        => $campos['telefono'],
            "residencia"      => $campos['residencia'],
            "correo"          => $campos['correo'],
        ]);
        
        if (!$resultado)
        {
            return ['success' => false, 'user_logs' => ["Error al guardar la solicitud."]];
        }

        return ['success' => true, 'user_logs' => ["Solicitud guardada correctamente."]];
    }


    // ─── Validación compartida ───────────────────────────────────────────────
    private static function validarCampos(array $data): array
    {
        $cedula          = trim($data["cedula"]          ?? "");
        $nombre          = trim($data["nombre"]          ?? "");
        $apellido        = trim($data["apellido"]        ?? "");
        $estado_civil    = trim($data["estado_civil"]    ?? "");
        $genero          = trim($data["genero"]          ?? "");
        $tipo_sangre     = trim($data["tipo_sangre"]     ?? "");
        $fecha_nacimiento= trim($data["fecha_nacimiento"]?? "");
        $nacionalidad    = trim($data["nacionalidad"]    ?? "");
        $telefono        = trim($data["telefono"]        ?? "");
        $residencia      = trim($data["residencia"]      ?? "");
        $correo          = trim($data["correo"]          ?? "");

        $logs = [];

        if (empty($cedula) || empty($nombre) || empty($apellido) || empty($genero) ||
            empty($fecha_nacimiento) || empty($nacionalidad) || empty($telefono) ||
            empty($residencia) || empty($correo)) {
            $logs[] = "Complete todos los campos obligatorios.";
        }

        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $national = '[0-9]{1,2}-[0-9]{1,4}-[0-9]{1,6}';
        $special  = '[PEN]-[0-9]{1,4}-[0-9]{1,6}';
        $passport = '[A-Z0-9]{6,15}';
        $pattern  = "/^($national|$special|$passport)$/";
        if (!preg_match($pattern, $cedula)) $logs[] = "Cédula o pasaporte inválido.";

        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$/", $nombre))   $logs[] = "Nombre inválido.";
        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$/", $apellido)) $logs[] = "Apellido inválido.";

        if (!in_array($estado_civil, ["", "Soltero", "Casado"], true))  $logs[] = "Estado civil inválido.";
        if (!in_array($genero, ["Masculino", "Femenino"], true))        $logs[] = "Género inválido.";
        if (!in_array($tipo_sangre, ["","A+","A-","B+","B-","AB+","AB-","O+","O-"], true)) $logs[] = "Tipo de sangre inválido.";

        $date_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
        if (!$date_obj || $date_obj->format('Y-m-d') !== $fecha_nacimiento) {
            $logs[] = "Fecha de nacimiento inválida.";
        }

        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $today = new DateTime('today');
        if ($date_obj >= $today) $logs[] = "La fecha no puede ser futura.";
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $edad = $date_obj->diff($today)->y;
        if ($edad < 18)  $logs[] = "El aspirante debe ser mayor de edad.";
        if ($edad > 120) $logs[] = "Fecha de nacimiento inválida.";

        $nacs = ["Afgana","Albanesa","Alemana","Andorrana","Angoleña","Antiguana","Argentina","Armenia","Australiana","Austriaca","Azerbaiyana","Bahameña","Bareiní","Bangladesí","Barbadense","Belga","Beliceña","Beninesa","Bielorrusa","Boliviana","Bosnia","Botsuana","Brasileña","Británica","Bruneana","Búlgara","Burkinesa","Burundesa","Camboyana","Camerunesa","Canadiense","Chadiana","Chilena","China","Chipriota","Colombiana","Congoleña","Costarricense","Croata","Cubana","Danesa","Dominicana","Ecuatoriana","Egipcia","Salvadoreña","Emiratí","Eritrea","Eslovaca","Eslovena","Española","Estadounidense","Estonia","Etíope","Filipina","Finlandesa","Francesa","Gabonesa","Gambiana","Georgiana","Ghanesa","Granadina","Griega","Guatemalteca","Guineana","Guyonesa","Haitiana","Hondureña","Húngara","India","Indonesia","Iraní","Iraquí","Irlandesa","Islandesa","Israelí","Italiana","Jamaiquina","Japonesa","Jordana","Kazaja","Keniana","Kirguisa","Kiribatiana","Kuwaití","Laosiana","Lesotense","Letona","Libanesa","Liberiana","Libia","Liechtensteiniana","Lituana","Luxemburguesa","Macedonia","Malasia","Malauí","Maldiva","Maliense","Maltesa","Marroquí","Mauriciana","Mexicana","Moldava","Monegasca","Mongola","Namibia","Nepalesa","Nicaragüense","Nigeriana","Noruega","Neozelandesa","Panameña","Paraguaya","Peruana","Polaca","Portuguesa","Qatarí","Rumana","Rusa","Senegalesa","Serbia","Singapurense","Siria","Somalí","Sudafricana","Sueca","Suiza","Tailandesa","Tanzana","Tunecina","Turca","Ucraniana","Ugandesa","Uruguaya","Venezolana","Vietnamita","Yemení","Zambiana","Zimbabuense"];
        if (!in_array($nacionalidad, $nacs, true)) $logs[] = "Nacionalidad inválida.";

        if (!preg_match("/^6[0-9]{3}-[0-9]{4}$/", $telefono)) $logs[] = "Teléfono inválido. Use el formato 6123-4567.";
        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s#.,-]{5,100}$/", $residencia)) $logs[] = "Residencia inválida.";
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $logs[] = "Correo inválido.";
        if (strlen($correo) > 254) $logs[] = "Correo máximo 254 caracteres.";

        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        return [
            'success' => true,
            'user_logs' => [],
            'campos' => compact(
                'cedula','nombre','apellido','estado_civil','genero',
                'tipo_sangre','fecha_nacimiento','nacionalidad',
                'telefono','residencia','correo'
            ),
        ];
    }
}
