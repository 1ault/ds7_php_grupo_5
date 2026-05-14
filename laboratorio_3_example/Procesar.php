<?php
class Procesar {
    public function procesarPost($nombre, $correo, $cedula, $edad) {
        return[
            "nombre" => $nombre,
            "correo" => $correo,
            "cedula" => $cedula,
            "edad" => $edad
        ];
    }

    public function procesarGet($nombre, $peso, $altura) {
        $imc = $peso / ($altura * $altura);
        return[
            "nombre" => $nombre,
            "peso" => $peso,
            "altura" => $altura,
            "imc" => $imc
        ];
    }
}
?>