<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Solicitud</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<div class="contenedor">

    <h2>Actualizar Información</h2>

    <p class="mensaje">
        Estado de solicitud:
        <?= htmlspecialchars($datosAspirante["estado_solicitud"]) ?>
    </p>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden" name="accion" value="actualizar">

        <div class="grupo">
            <label>Cédula o Pasaporte</label>
            <input type="text" name="cedula" value="<?= htmlspecialchars($datosAspirante["cedula_pasaporte"]) ?>" required pattern="^([0-9]{1,2}-[0-9]{1,4}-[0-9]{1,6}|[PEEN]-[0-9]{1,4}-[0-9]{1,6}|[A-Z0-9]{6,15})$" maxlength="15" title="Ingrese una cédula panameña válida o un pasaporte válido.">
        </div>

        <div class="grupo">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($datosAspirante["nombre"]) ?>" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$" maxlength="25" title="El nombre solo debe contener letras."required>
        </div>

        <div class="grupo">
            <label>Apellido</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($datosAspirante["apellido"]) ?>" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$" maxlength="25" title="El apellido solo debe contener letras." required>
        </div>

        <div class="grupo">
            <label>Estado Civil</label>
            <select name="estado_civil">
                <option value="">Seleccione una opción</option>
                <option value="Soltero"
                    <?= $datosAspirante["estado_civil"] === "Soltero" ? "selected" : "" ?>>
                    Soltero
                </option>
                <option value="Casado"
                    <?= $datosAspirante["estado_civil"] === "Casado" ? "selected" : "" ?>>
                    Casado
                </option>
            </select>
        </div>

        <div class="grupo">
            <label>Género</label>
            <select name="genero" required>
                <option value="">Seleccione una opción</option>
                <option value="Masculino"
                    <?= $datosAspirante["genero"] === "Masculino" ? "selected" : "" ?>>
                    Masculino
                </option>
                <option value="Femenino"
                    <?= $datosAspirante["genero"] === "Femenino" ? "selected" : "" ?>>
                    Femenino
                </option>
            </select>
        </div>

        <div class="grupo">
            <label>Tipo de Sangre</label>
            <select name="tipo_sangre">
                <option value="">Seleccione una opción</option>
                <?php
                    $tipos = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
                    foreach ($tipos as $tipo):
                ?>
                    <option value="<?= $tipo ?>"
                        <?= $datosAspirante["tipo_sangre"] === $tipo ? "selected" : "" ?>>
                        <?= $tipo ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grupo">
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($datosAspirante["fecha_nacimiento"]) ?>" max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
        </div>

        <div class="grupo">
            <label>Nacionalidad</label>
            <select name="nacionalidad" required>
                <option value="">Seleccione una nacionalidad</option>
                <?php
                    $nacionalidades = [
                        "Afgana", "Albanesa", "Alemana", "Andorrana", "Angoleña",
                        "Antiguana", "Argentina", "Armenia", "Australiana", "Austriaca",
                        "Azerbaiyana", "Bahameña", "Bareiní", "Bangladesí", "Barbadense",
                        "Belga", "Beliceña", "Beninesa", "Bielorrusa", "Boliviana",
                        "Bosnia", "Botsuana", "Brasileña", "Británica", "Bruneana",
                        "Búlgara", "Burkinesa", "Burundesa", "Camboyana", "Camerunesa",
                        "Canadiense", "Chadiana", "Chilena", "China", "Chipriota",
                        "Colombiana", "Congoleña", "Costarricense", "Croata", "Cubana",
                        "Danesa", "Dominicana", "Ecuatoriana", "Egipcia", "Salvadoreña",
                        "Emiratí", "Eritrea", "Eslovaca", "Eslovena", "Española",
                        "Estadounidense", "Estonia", "Etíope", "Filipina", "Finlandesa",
                        "Francesa", "Gabonesa", "Gambiana", "Georgiana", "Ghanesa",
                        "Granadina", "Griega", "Guatemalteca", "Guineana", "Guyonesa",
                        "Haitiana", "Hondureña", "Húngara", "India", "Indonesia",
                        "Iraní", "Iraquí", "Irlandesa", "Islandesa", "Israelí",
                        "Italiana", "Jamaiquina", "Japonesa", "Jordana", "Kazaja",
                        "Keniana", "Kirguisa", "Kiribatiana", "Kuwaití", "Laosiana",
                        "Lesotense", "Letona", "Libanesa", "Liberiana", "Libia",
                        "Liechtensteiniana", "Lituana", "Luxemburguesa", "Macedonia",
                        "Malasia", "Malauí", "Maldiva", "Maliense", "Maltesa",
                        "Marroquí", "Mauriciana", "Mexicana", "Moldava", "Monegasca",
                        "Mongola", "Namibia", "Nepalesa", "Nicaragüense", "Nigeriana",
                        "Noruega", "Neozelandesa", "Panameña", "Paraguaya", "Peruana",
                        "Polaca", "Portuguesa", "Qatarí", "Rumana", "Rusa",
                        "Senegalesa", "Serbia", "Singapurense", "Siria", "Somalí",
                        "Sudafricana", "Sueca", "Suiza", "Tailandesa", "Tanzana",
                        "Tunecina", "Turca", "Ucraniana", "Ugandesa", "Uruguaya",
                        "Venezolana", "Vietnamita", "Yemení", "Zambiana", "Zimbabuense"
                    ];
                    foreach ($nacionalidades as $nacionalidad):
                ?>
                    <option value="<?= htmlspecialchars($nacionalidad) ?>"
                        <?= $datosAspirante["nacionalidad"] === $nacionalidad ? "selected" : "" ?>>
                        <?= htmlspecialchars($nacionalidad) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grupo">
            <label>Teléfono</label>
            <input type="tel" name="telefono" value="<?= htmlspecialchars($datosAspirante["telefono"]) ?>" pattern="^6[0-9]{3}-[0-9]{4}$" maxlength="9" placeholder="6123-4567" title="Formato válido: 6123-4567" required>
        </div>

        <div class="grupo">
            <label>Residencia</label>
            <input type="text" name="residencia" value="<?= htmlspecialchars($datosAspirante["residencia"]) ?>" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s#.,-]{5,100}$" maxlength="100" title="Ingrese una dirección válida." required>
        </div>

        <div class="grupo">
            <label>Correo</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($datosAspirante["correo"]) ?>" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$" maxlength="50" placeholder="ejemplo@correo.com" autocomplete="email" title="Ingrese un correo válido." required>
        </div>

        <button type="submit">
            Actualizar Información
        </button>

    </form>

</div>

</body>
</html>