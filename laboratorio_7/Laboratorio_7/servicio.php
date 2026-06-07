<?php

ini_set("soap.wsdl_cache_enabled", "0");

// Configurar el servidor usando el archivo WSDL
$wsdl = "http://localhost/Laboratorio_7/servicioOperations.wsdl";

class MathOperations {
    public function sumar($a, $b) {
        return (float)$a + (float)$b;
    }

    public function restar($a, $b) {
        return (float)$a - (float)$b;
    }

    public function multiplicar($a, $b) {
        return (float)$a * (float)$b;
    }

    public function dividir($a, $b) {
        $a = (float)$a;
        $b = (float)$b;
        if ($b == 0.0) {
            throw new SoapFault("Client", "Error matemático: No se permite la división entre cero.");
        }
        return $a / $b;
    }
}

try {
    $server = new SoapServer($wsdl);
    $server->setClass('MathOperations');
    $server->handle();
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Error en el servidor SOAP: " . $e->getMessage();
}
?>
