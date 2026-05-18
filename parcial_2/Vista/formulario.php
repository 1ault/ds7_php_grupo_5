<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Aspirante</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<div class="contenedor">

    <h2>Formulario de Aspirante</h2>

    <?php if (!empty($mensaje)): ?>
    <div class="mensaje">
        <?= htmlspecialchars($_SESSION['user_log'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php endif; ?>

    <form method="POST">

        <div class="grupo">
            <label>Cédula o Pasaporte</label>
            <input type="text" name="cedula"required pattern="^([0-9]{1,2}-[0-9]{1,4}-[0-9]{1,6}|[PEEN]-[0-9]{1,4}-[0-9]{1,6}|[A-Z0-9]{6,15})$" maxlength="15" title="Ingrese una cédula panameña válida o un pasaporte válido.">
        </div>

        <div class="grupo">
            <label>Nombre</label>
            <input type="text" name="nombre" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$" maxlength="25" title="El nombre solo debe contener letras." required>
        </div>

        <div class="grupo">
            <label>Apellido</label>
            <input type="text" name="apellido" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$" maxlength="25" title="El apellido solo debe contener letras." required>
        </div>

        <div class="grupo">
    <label>Estado Civil</label>
    <select name="estado_civil">
        <option value=""> Seleccione una opción </option>
        <option value="Soltero">Soltero</option>
        <option value="Casado">Casado</option>
    </select>
    </div>

    <div class="grupo">
      <label>Género</label>
      <select name="genero" required>
        <option value="">Seleccione una opción</option>
        <option value="Masculino">Masculino </option>
        <option value="Femenino">Femenino</option>
      </select>
    </div>

    <div class="grupo">
       <label>Tipo de Sangre</label>
       <select name="tipo_sangre">
        <option value=""> Seleccione una opción</option>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
       </select>
    </div>

    <div class="grupo">
      <label>Fecha de Nacimiento</label>
      <input type="date" name="fecha_nacimiento" max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
    </div>

    <div class="grupo">
     <label>Nacionalidad</label>
     <select name="nacionalidad" required>
        <option value="">
            Seleccione una nacionalidad
        </option>
        <option value="Afgana">Afgana</option>
        <option value="Albanesa">Albanesa</option>
        <option value="Alemana">Alemana</option>
        <option value="Andorrana">Andorrana</option>
        <option value="Angoleña">Angoleña</option>
        <option value="Antiguana">Antiguana</option>
        <option value="Argentina">Argentina</option>
        <option value="Armenia">Armenia</option>
        <option value="Australiana">Australiana</option>
        <option value="Austriaca">Austriaca</option>
        <option value="Azerbaiyana">Azerbaiyana</option>
        <option value="Bahameña">Bahameña</option>
        <option value="Bareiní">Bareiní</option>
        <option value="Bangladesí">Bangladesí</option>
        <option value="Barbadense">Barbadense</option>
        <option value="Belga">Belga</option>
        <option value="Beliceña">Beliceña</option>
        <option value="Beninesa">Beninesa</option>
        <option value="Bielorrusa">Bielorrusa</option>
        <option value="Boliviana">Boliviana</option>
        <option value="Bosnia">Bosnia</option>
        <option value="Botsuana">Botsuana</option>
        <option value="Brasileña">Brasileña</option>
        <option value="Británica">Británica</option>
        <option value="Bruneana">Bruneana</option>
        <option value="Búlgara">Búlgara</option>
        <option value="Burkinesa">Burkinesa</option>
        <option value="Burundesa">Burundesa</option>
        <option value="Camboyana">Camboyana</option>
        <option value="Camerunesa">Camerunesa</option>
        <option value="Canadiense">Canadiense</option>
        <option value="Chadiana">Chadiana</option>
        <option value="Chilena">Chilena</option>
        <option value="China">China</option>
        <option value="Chipriota">Chipriota</option>
        <option value="Colombiana">Colombiana</option>
        <option value="Congoleña">Congoleña</option>
        <option value="Costarricense">Costarricense</option>
        <option value="Croata">Croata</option>
        <option value="Cubana">Cubana</option>
        <option value="Danesa">Danesa</option>
        <option value="Dominicana">Dominicana</option>
        <option value="Ecuatoriana">Ecuatoriana</option>
        <option value="Egipcia">Egipcia</option>
        <option value="Salvadoreña">Salvadoreña</option>
        <option value="Emiratí">Emiratí</option>
        <option value="Eritrea">Eritrea</option>
        <option value="Eslovaca">Eslovaca</option>
        <option value="Eslovena">Eslovena</option>
        <option value="Española">Española</option>
        <option value="Estadounidense">Estadounidense</option>
        <option value="Estonia">Estonia</option>
        <option value="Etíope">Etíope</option>
        <option value="Filipina">Filipina</option>
        <option value="Finlandesa">Finlandesa</option>
        <option value="Francesa">Francesa</option>
        <option value="Gabonesa">Gabonesa</option>
        <option value="Gambiana">Gambiana</option>
        <option value="Georgiana">Georgiana</option>
        <option value="Ghanesa">Ghanesa</option>
        <option value="Granadina">Granadina</option>
        <option value="Griega">Griega</option>
        <option value="Guatemalteca">Guatemalteca</option>
        <option value="Guineana">Guineana</option>
        <option value="Guyonesa">Guyonesa</option>
        <option value="Haitiana">Haitiana</option>
        <option value="Hondureña">Hondureña</option>
        <option value="Húngara">Húngara</option>
        <option value="India">India</option>
        <option value="Indonesia">Indonesia</option>
        <option value="Iraní">Iraní</option>
        <option value="Iraquí">Iraquí</option>
        <option value="Irlandesa">Irlandesa</option>
        <option value="Islandesa">Islandesa</option>
        <option value="Israelí">Israelí</option>
        <option value="Italiana">Italiana</option>
        <option value="Jamaiquina">Jamaiquina</option>
        <option value="Japonesa">Japonesa</option>
        <option value="Jordana">Jordana</option>
        <option value="Kazaja">Kazaja</option>
        <option value="Keniana">Keniana</option>
        <option value="Kirguisa">Kirguisa</option>
        <option value="Kiribatiana">Kiribatiana</option>
        <option value="Kuwaití">Kuwaití</option>
        <option value="Laosiana">Laosiana</option>
        <option value="Lesotense">Lesotense</option>
        <option value="Letona">Letona</option>
        <option value="Libanesa">Libanesa</option>
        <option value="Liberiana">Liberiana</option>
        <option value="Libia">Libia</option>
        <option value="Liechtensteiniana">Liechtensteiniana</option>
        <option value="Lituana">Lituana</option>
        <option value="Luxemburguesa">Luxemburguesa</option>
        <option value="Macedonia">Macedonia</option>
        <option value="Malasia">Malasia</option>
        <option value="Malauí">Malauí</option>
        <option value="Maldiva">Maldiva</option>
        <option value="Maliense">Maliense</option>
        <option value="Maltesa">Maltesa</option>
        <option value="Marroquí">Marroquí</option>
        <option value="Mauriciana">Mauriciana</option>
        <option value="Mexicana">Mexicana</option>
        <option value="Moldava">Moldava</option>
        <option value="Monegasca">Monegasca</option>
        <option value="Mongola">Mongola</option>
        <option value="Namibia">Namibia</option>
        <option value="Nepalesa">Nepalesa</option>
        <option value="Nicaragüense">Nicaragüense</option>
        <option value="Nigeriana">Nigeriana</option>
        <option value="Noruega">Noruega</option>
        <option value="Neozelandesa">Neozelandesa</option>
        <option value="Panameña">Panameña</option>
        <option value="Paraguaya">Paraguaya</option>
        <option value="Peruana">Peruana</option>
        <option value="Polaca">Polaca</option>
        <option value="Portuguesa">Portuguesa</option>
        <option value="Qatarí">Qatarí</option>
        <option value="Rumana">Rumana</option>
        <option value="Rusa">Rusa</option>
        <option value="Senegalesa">Senegalesa</option>
        <option value="Serbia">Serbia</option>
        <option value="Singapurense">Singapurense</option>
        <option value="Siria">Siria</option>
        <option value="Somalí">Somalí</option>
        <option value="Sudafricana">Sudafricana</option>
        <option value="Sueca">Sueca</option>
        <option value="Suiza">Suiza</option>
        <option value="Tailandesa">Tailandesa</option>
        <option value="Tanzana">Tanzana</option>
        <option value="Tunecina">Tunecina</option>
        <option value="Turca">Turca</option>
        <option value="Ucraniana">Ucraniana</option>
        <option value="Ugandesa">Ugandesa</option>
        <option value="Uruguaya">Uruguaya</option>
        <option value="Venezolana">Venezolana</option>
        <option value="Vietnamita">Vietnamita</option>
        <option value="Yemení">Yemení</option>
        <option value="Zambiana">Zambiana</option>
        <option value="Zimbabuense">Zimbabuense</option>
     </select>
    </div>

    <div class="grupo">
      <label>Teléfono</label>
      <input   type="tel"
        name="telefono" pattern="^6[0-9]{3}-[0-9]{4}$" maxlength="9" placeholder="6123-4567" title="Formato válido: 6123-4567" required>
    </div>
    
    <div class="grupo">
            <label>Residencia</label>
            <input type="text" name="residencia" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s#.,-]{5,100}$" maxlength="100" title="Ingrese una dirección válida." required>
    </div>

    <div class="grupo">
        <label>Correo</label>
        <input type="email" name="correo" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$" maxlength="50" placeholder="ejemplo@correo.com" autocomplete="email" title="Ingrese un correo válido." required>
    </div>

    <button type="submit">Guardar Solicitud</button>
 </form>

</div>
</body>
</html>
