<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Solicitud — RH System</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<!-- ── Navbar ─────────────────────────────────────────────────────── -->
<nav class="navbar navbar-aspirante">
    <span class="navbar-brand">📋 RH System</span>
    <div class="navbar-info">
        <span>👤 <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        <a href="/post/usuario/logout" class="btn-logout">Cerrar sesión</a>
    </div>
</nav>

<!-- ── Contenido principal ────────────────────────────────────────── -->
<div class="main-content">
<div class="contenedor aspirante-contenedor">

    <h2>Mi Solicitud</h2>

    <!-- Mensajes flash -->
    <?php if (!empty($_SESSION['user_logs']) && is_array($_SESSION['user_logs'])): ?>
        <?php
            $exitoMsgs = ['Solicitud guardada correctamente.', 'Solicitud actualizada correctamente.'];
            $esExito   = in_array($_SESSION['user_logs'][0] ?? '', $exitoMsgs);
        ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
            <div class="mensaje <?= $esExito ? 'mensaje-ok' : '' ?>">
                <?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['user_logs']); ?>
    <?php endif; ?>

    <?php
        $tieneS = !empty($solicitud);
        $accion = $tieneS ? '/post/aspirante/update' : '/post/aspirante/guardar';
        $boton  = $tieneS ? 'Actualizar Solicitud'  : 'Guardar Solicitud';

        $v = [
            'cedula'           => $tieneS ? $solicitud['cedula_pasaporte'] : '',
            'nombre'           => $tieneS ? $solicitud['nombre']           : '',
            'apellido'         => $tieneS ? $solicitud['apellido']         : '',
            'estado_civil'     => $tieneS ? $solicitud['estado_civil']     : '',
            'genero'           => $tieneS ? $solicitud['genero']           : '',
            'tipo_sangre'      => $tieneS ? $solicitud['tipo_sangre']      : '',
            'fecha_nacimiento' => $tieneS ? $solicitud['fecha_nacimiento'] : '',
            'nacionalidad'     => $tieneS ? $solicitud['nacionalidad']     : '',
            'telefono'         => $tieneS ? $solicitud['telefono']         : '',
            'residencia'       => $tieneS ? $solicitud['residencia']       : '',
            'correo'           => $tieneS ? $solicitud['correo']           : '',
        ];
    ?>

    <!-- Badge de estado (solo si ya tiene solicitud) -->
    <?php if ($tieneS): ?>
        <?php
            $estado      = $solicitud['estado_solicitud'];
            $claseEstado = match($estado) {
                'considerado'    => 'badge-considerado',
                'no considerado' => 'badge-no-considerado',
                default          => 'badge-no-revisado',
            };
            $bloqueado = $estado !== 'no revisado';
        ?>
        <p class="solicitud-fecha">
            Estado actual:
            <span class="badge <?= $claseEstado ?>">
                <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?>
            </span>
        </p>
        <?php if ($bloqueado): ?>
            <p class="intro-form" style="color:#856404; background:#fff3cd; padding:10px 14px; border-radius:8px; margin-bottom:16px;">
                ⚠ Su solicitud ya fue revisada y no puede ser modificada.
            </p>
        <?php endif; ?>
    <?php else: ?>
        <p class="intro-form">Complete el formulario para enviar su solicitud.</p>
    <?php endif; ?>

    <!-- Formulario -->
    <form action="<?= $accion ?>" method="POST">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <?php
        // Si la solicitud fue revisada, deshabilitar todos los campos
        $dis = ($tieneS && $bloqueado) ? 'disabled' : '';
        ?>

        <!-- Sección: Identificación -->
        <fieldset class="fieldset-seccion">
            <legend>Identificación</legend>

            <div class="grupo-fila">
                <div class="grupo">
                    <label for="cedula">Cédula o Pasaporte <span class="requerido">*</span></label>
                    <input type="text" name="cedula" id="cedula" <?= $dis ?>
                        value="<?= htmlspecialchars($v['cedula'], ENT_QUOTES, 'UTF-8') ?>"
                        pattern="^([0-9]{1,2}-[0-9]{1,4}-[0-9]{1,6}|[PEN]-[0-9]{1,4}-[0-9]{1,6}|[A-Z0-9]{6,15})$"
                        maxlength="15" placeholder="8-123-4567"
                        title="Cédula panameña o pasaporte válido." required>
                </div>
                <div class="grupo">
                    <label for="nacionalidad">Nacionalidad <span class="requerido">*</span></label>
                    <select name="nacionalidad" id="nacionalidad" <?= $dis ?> required>
                        <option value="">Seleccione</option>
                        <?php
                        $nacs = ["Afgana","Albanesa","Alemana","Andorrana","Angoleña","Antiguana","Argentina","Armenia","Australiana","Austriaca","Azerbaiyana","Bahameña","Bareiní","Bangladesí","Barbadense","Belga","Beliceña","Beninesa","Bielorrusa","Boliviana","Bosnia","Botsuana","Brasileña","Británica","Bruneana","Búlgara","Burkinesa","Burundesa","Camboyana","Camerunesa","Canadiense","Chadiana","Chilena","China","Chipriota","Colombiana","Congoleña","Costarricense","Croata","Cubana","Danesa","Dominicana","Ecuatoriana","Egipcia","Salvadoreña","Emiratí","Eritrea","Eslovaca","Eslovena","Española","Estadounidense","Estonia","Etíope","Filipina","Finlandesa","Francesa","Gabonesa","Gambiana","Georgiana","Ghanesa","Granadina","Griega","Guatemalteca","Guineana","Guyonesa","Haitiana","Hondureña","Húngara","India","Indonesia","Iraní","Iraquí","Irlandesa","Islandesa","Israelí","Italiana","Jamaiquina","Japonesa","Jordana","Kazaja","Keniana","Kirguisa","Kiribatiana","Kuwaití","Laosiana","Lesotense","Letona","Libanesa","Liberiana","Libia","Liechtensteiniana","Lituana","Luxemburguesa","Macedonia","Malasia","Malauí","Maldiva","Maliense","Maltesa","Marroquí","Mauriciana","Mexicana","Moldava","Monegasca","Mongola","Namibia","Nepalesa","Nicaragüense","Nigeriana","Noruega","Neozelandesa","Panameña","Paraguaya","Peruana","Polaca","Portuguesa","Qatarí","Rumana","Rusa","Senegalesa","Serbia","Singapurense","Siria","Somalí","Sudafricana","Sueca","Suiza","Tailandesa","Tanzana","Tunecina","Turca","Ucraniana","Ugandesa","Uruguaya","Venezolana","Vietnamita","Yemení","Zambiana","Zimbabuense"];
                        foreach ($nacs as $nac):
                        ?>
                            <option value="<?= htmlspecialchars($nac, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $v['nacionalidad'] === $nac ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nac, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </fieldset>

        <!-- Sección: Datos Personales -->
        <fieldset class="fieldset-seccion">
            <legend>Datos Personales</legend>

            <div class="grupo-fila">
                <div class="grupo">
                    <label for="nombre">Nombre <span class="requerido">*</span></label>
                    <input type="text" name="nombre" id="nombre" <?= $dis ?>
                        value="<?= htmlspecialchars($v['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$" maxlength="25"
                        title="Solo letras, 2-25 caracteres." required>
                </div>
                <div class="grupo">
                    <label for="apellido">Apellido <span class="requerido">*</span></label>
                    <input type="text" name="apellido" id="apellido" <?= $dis ?>
                        value="<?= htmlspecialchars($v['apellido'], ENT_QUOTES, 'UTF-8') ?>"
                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$" maxlength="25"
                        title="Solo letras, 2-25 caracteres." required>
                </div>
            </div>

            <div class="grupo-fila">
                <div class="grupo">
                    <label for="genero">Género <span class="requerido">*</span></label>
                    <select name="genero" id="genero" <?= $dis ?> required>
                        <option value="">Seleccione</option>
                        <?php foreach (['Masculino','Femenino'] as $op): ?>
                            <option value="<?= $op ?>" <?= $v['genero'] === $op ? 'selected' : '' ?>><?= $op ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grupo">
                    <label for="estado_civil">Estado Civil</label>
                    <select name="estado_civil" id="estado_civil" <?= $dis ?>>
                        <option value="">Seleccione</option>
                        <?php foreach (['Soltero','Casado'] as $op): ?>
                            <option value="<?= $op ?>" <?= $v['estado_civil'] === $op ? 'selected' : '' ?>><?= $op ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grupo-fila">
                <div class="grupo">
                    <label for="fecha_nacimiento">Fecha de Nacimiento <span class="requerido">*</span></label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" <?= $dis ?>
                        value="<?= htmlspecialchars($v['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') ?>"
                        max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
                </div>
                <div class="grupo">
                    <label for="tipo_sangre">Tipo de Sangre</label>
                    <select name="tipo_sangre" id="tipo_sangre" <?= $dis ?>>
                        <option value="">Seleccione</option>
                        <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $op): ?>
                            <option value="<?= $op ?>" <?= $v['tipo_sangre'] === $op ? 'selected' : '' ?>><?= $op ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </fieldset>

        <!-- Sección: Contacto -->
        <fieldset class="fieldset-seccion">
            <legend>Contacto</legend>

            <div class="grupo-fila">
                <div class="grupo">
                    <label for="telefono">Teléfono <span class="requerido">*</span></label>
                    <input type="tel" name="telefono" id="telefono" <?= $dis ?>
                        value="<?= htmlspecialchars($v['telefono'], ENT_QUOTES, 'UTF-8') ?>"
                        pattern="^6[0-9]{3}-[0-9]{4}$" maxlength="9"
                        placeholder="6123-4567" title="Formato: 6123-4567" required>
                </div>
                <div class="grupo">
                    <label for="correo">Correo <span class="requerido">*</span></label>
                    <input type="email" name="correo" id="correo" <?= $dis ?>
                        value="<?= htmlspecialchars($v['correo'], ENT_QUOTES, 'UTF-8') ?>"
                        maxlength="254" placeholder="ejemplo@correo.com"
                        autocomplete="email" required>
                </div>
            </div>

            <div class="grupo">
                <label for="residencia">Residencia <span class="requerido">*</span></label>
                <input type="text" name="residencia" id="residencia" <?= $dis ?>
                    value="<?= htmlspecialchars($v['residencia'], ENT_QUOTES, 'UTF-8') ?>"
                    pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s#.,-]{5,100}$" maxlength="100"
                    title="Ingrese una dirección válida." required>
            </div>
        </fieldset>

        <p class="nota-requerido"><span class="requerido">*</span> Campos obligatorios</p>

        <?php if (!$tieneS || !$bloqueado): ?>
            <button type="submit"><?= $boton ?></button>
        <?php endif; ?>

    </form>

</div>
</div>

<!-- ── Footer ─────────────────────────────────────────────────────── -->
<footer class="site-footer">RH System &copy; <?= date('Y') ?> — DS7 Grupo 5</footer>

</body>
</html>
