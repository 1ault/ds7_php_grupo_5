<?php

ini_set("soap.wsdl_cache_enabled", "0");


$wsdl = "http://localhost/Laboratorios/Laboratorio9/veterinaria_soap/inventario.wsdl";


class inventario {

    private $archivo = __DIR__ . "/productos.php";

    private function cargarProductos() {
        return include $this->archivo;
    }

    private function guardarProductos($productos) {
        $contenido = "<?php\nreturn " . var_export($productos, true) . ";\n";
        file_put_contents($this->archivo, $contenido);
    }
    
    public function obtenerProducto($id) {
        $productos = $this->cargarProductos();

        if (isset($productos[$id])) {
            return $productos[$id];
        } else {
            throw new SoapFault("Client", "Producto no encontrado con ID: $id");
        }
    }

    public function actualizarStock($id, $cantidad) {
        $productos = $this->cargarProductos();

        if (isset($productos[$id])) {
            $productos[$id]['stock'] += $cantidad;
            $this->guardarProductos($productos);
            return $productos[$id];
        } else {
            throw new SoapFault("Client", "Producto no encontrado con ID: $id");
        }
        
    }

    public function listarProductos() {
        $productos = $this->cargarProductos();
        return array_values($productos);
    }
}


try {
    $server = new SoapServer($wsdl);
    $server->setClass('inventario');
    $server->handle();
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Error en el servidor SOAP: " . $e->getMessage();
}
?>
