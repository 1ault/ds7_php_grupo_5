<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Aspirante</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>
<div class="contenedor">

    <h2>Formulario de Producto</h2>

    <form action="/form" method="POST">
        <div class="grupo">
            <label for="id">id</label>
            <input 
                type="text" 
                name="id"
                id="id"
               
                value="<?= isset($id) ? $id : '' ?>"
                >
        </div>

        <div class="grupo">
            <label for="nombre">Nombre</label>
            <input 
                type="text" 
                name="nombre"
                id="nombre"
               
                value="<?= isset($nombre) ? $nombre : '' ?>"
                >
        </div>

        <div class="grupo">
            <label for="marca">Marca</label>
            <input 
                type="text" 
                name="marca"
                id="marca"
               
                value="<?= isset($marca) ? $marca : '' ?>"
                >
        </div>

        <div class="grupo">
            <label for="precio">Precio</label>
        <input 
            type="number" 
            name="precio"
            id="precio"
            step="0.01"
           
            value="<?= isset($precio) ? $precio : '' ?>"
            >
        </div>

        <div class="grupo">
          <label for="stock">Stock</label>
        <input 
            type="number" 
            name="stock"
            id="stock"
           
            value="<?= isset($stock) ? $stock : '' ?>"
            >
        </div>

        <div class="grupo">
           <label>Tipo De Producto</label>
            <select name="tipo_de_producto">

                <option value="">
                    Seleccione una opción
                </option>

                <option
                    value="pc"
                    <?= ($tipo_de_producto ?? '') === "pc"
                        ? "selected"
                        : "" ?>>
                    pc
                </option>

                <option
                    value="laptop"
                    <?= ($tipo_de_producto ?? '') === "laptop"
                        ? "selected"
                        : "" ?>>
                    laptop
                </option>

                <option
                    value="telefono"
                    <?= ($tipo_de_producto ?? '') === "telefono"
                        ? "selected"
                        : "" ?>>
                    telefono
                </option>
            </select> 
        </div>



    <button type="submit">
        Guardar Solicitud
    </button>
    <a href="/form">
        <button type="button">
            Limpiar
        </button>
    </a>

 </form>

</div>
</body>
</html>
