<?php
declare(strict_types=1);
// Vista/Aspirante/index.php
// Pantalla principal del aspirante: muestra su solicitud si ya la envió,
// o el formulario para completarla por primera vez.
//
// Variables inyectadas por AspiranteController::vistaAspirante():
//   $solicitud (array|null) — fila de la tabla aspirantes del usuario actual

$pageTitle  = 'Mi Solicitud — Aspirante';
$layoutRole = 'aspirante';
require_once __DIR__ . '/../Layout/header.php';
?>

<div class="contenedor aspirante-contenedor">

    <h2>Mi Solicitud de Empleo</h2>

    <?php /* ── Mensajes flash ── */ ?>
    <?php if (!empty($_SESSION['user_logs']) && is_array($_SESSION['user_logs'])): ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
            <div class="mensaje <?= str_starts_with($log, 'Solicitud guardada') ? 'mensaje-ok' : '' ?>">
                <?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endforeach ?>
        <?php unset($_SESSION['user_logs']); ?>
    <?php endif; ?>

    <?php if (!empty($solicitud)): ?>
        <?php /* ════════════════════════════════════════════
               ESTADO: ya tiene solicitud enviada
               ════════════════════════════════════════════ */ ?>

        <div class="estado-badge estado-<?= htmlspecialchars($solicitud['estado_solicitud'], ENT_QUOTES, 'UTF-8') ?>">
            <?php
            $estadoLabel = match($solicitud['estado_solicitud']) {
                'no revisado'    => '🕐 En revisión',
                'considerado'    => '✅ Considerado',
                'no considerado' => '❌ No considerado',
                default          => htmlspecialchars($solicitud['estado_solicitud'], ENT_QUOTES, 'UTF-8'),
            };
            echo $estadoLabel;
            ?>
        </div>

        <p class="solicitud-fecha">
            Solicitud enviada el: 
            <strong><?= htmlspecialchars($solicitud['created_at'], ENT_QUOTES, 'UTF-8') ?></strong>
        </p>

        <div class="datos-grid">
            <div class="dato-item"><span class="dato-label">Cédula / Pasaporte</span><span><?= htmlspecialchars($solicitud['cedula_pasaporte'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Nombre</span><span><?= htmlspecialchars($solicitud['nombre'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Apellido</span><span><?= htmlspecialchars($solicitud['apellido'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Estado Civil</span><span><?= htmlspecialchars($solicitud['estado_civil'] ?: '—', ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Género</span><span><?= htmlspecialchars($solicitud['genero'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Tipo de Sangre</span><span><?= htmlspecialchars($solicitud['tipo_sangre'] ?: '—', ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Fecha de Nacimiento</span><span><?= htmlspecialchars($solicitud['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Nacionalidad</span><span><?= htmlspecialchars($solicitud['nacionalidad'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Teléfono</span><span><?= htmlspecialchars($solicitud['telefono'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item"><span class="dato-label">Residencia</span><span><?= htmlspecialchars($solicitud['residencia'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="dato-item dato-item--full"><span class="dato-label">Correo</span><span><?= htmlspecialchars($solicitud['correo'], ENT_QUOTES, 'UTF-8') ?></span></div>
        </div>

        <?php if ($solicitud['estado_solicitud'] === 'no revisado'): ?>
        <p class="nota-update">
            ¿Necesitas corregir un dato? Puedes actualizar tu solicitud mientras esté pendiente.
        </p>
        <form action="/post/aspirante/update" method="POST" class="form-update-toggle">
            <button type="button" id="btn-editar" class="btn-secundario">✏️ Editar solicitud</button>
        </form>
        <?php endif; ?>

    <?php else: ?>
        <?php /* ════════════════════════════════════════════
               ESTADO: aún no ha enviado solicitud
               ════════════════════════════════════════════ */ ?>

        <p class="intro-form">Completa el formulario a continuación para enviar tu solicitud de empleo.</p>

        <form action="/post/aspirante/guardar" method="POST" class="form-aspirante">

            <fieldset class="fieldset-seccion">
                <legend>Identificación</legend>

                <div class="grupo">
                    <label for="cedula">Cédula o Pasaporte <span class="requerido">*</span></label>
                    <input type="text" name="cedula" id="cedula"
                        pattern="^([0-9]{1,2}-[0-9]{1,4}-[0-9]{1,6}|[PEN]-[0-9]{1,4}-[0-9]{1,6}|[A-Z0-9]{6,15})$"
                        maxlength="15"
                        placeholder="8-123-4567 o E-123-456789 o AB123456"
                        title="Cédula panameña, cédula especial o pasaporte."
                        required>
                </div>

                <div class="grupo-fila">
                    <div class="grupo">
                        <label for="nombre">Nombre <span class="requerido">*</span></label>
                        <input type="text" name="nombre" id="nombre"
                            pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$"
                            maxlength="25" title="Solo letras." required>
                    </div>
                    <div class="grupo">
                        <label for="apellido">Apellido <span class="requerido">*</span></label>
                        <input type="text" name="apellido" id="apellido"
                            pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,25}$"
                            maxlength="25" title="Solo letras." required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="fieldset-seccion">
                <legend>Datos Personales</legend>

                <div class="grupo-fila">
                    <div class="grupo">
                        <label for="estado_civil">Estado Civil</label>
                        <select name="estado_civil" id="estado_civil">
                            <option value="">Seleccione...</option>
                            <option value="Soltero">Soltero/a</option>
                            <option value="Casado">Casado/a</option>
                        </select>
                    </div>
                    <div class="grupo">
                        <label for="genero">Género <span class="requerido">*</span></label>
                        <select name="genero" id="genero" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="grupo">
                        <label for="tipo_sangre">Tipo de Sangre</label>
                        <select name="tipo_sangre" id="tipo_sangre">
                            <option value="">Seleccione...</option>
                            <option>A+</option><option>A-</option>
                            <option>B+</option><option>B-</option>
                            <option>AB+</option><option>AB-</option>
                            <option>O+</option><option>O-</option>
                        </select>
                    </div>
                </div>

                <div class="grupo-fila">
                    <div class="grupo">
                        <label for="fecha_nacimiento">Fecha de Nacimiento <span class="requerido">*</span></label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                            max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
                    </div>
                    <div class="grupo">
                        <label for="nacionalidad">Nacionalidad <span class="requerido">*</span></label>
                        <select name="nacionalidad" id="nacionalidad" required>
                            <option value="">Seleccione...</option>
                            <?php
                            $nacionalidades = ["Afgana","Albanesa","Alemana","Andorrana","Angoleña","Antiguana","Argentina","Armenia","Australiana","Austriaca","Azerbaiyana","Bahameña","Bareiní","Bangladesí","Barbadense","Belga","Beliceña","Beninesa","Bielorrusa","Boliviana","Bosnia","Botsuana","Brasileña","Británica","Bruneana","Búlgara","Burkinesa","Burundesa","Camboyana","Camerunesa","Canadiense","Chadiana","Chilena","China","Chipriota","Colombiana","Congoleña","Costarricense","Croata","Cubana","Danesa","Dominicana","Ecuatoriana","Egipcia","Salvadoreña","Emiratí","Eritrea","Eslovaca","Eslovena","Española","Estadounidense","Estonia","Etíope","Filipina","Finlandesa","Francesa","Gabonesa","Gambiana","Georgiana","Ghanesa","Granadina","Griega","Guatemalteca","Guineana","Guyonesa","Haitiana","Hondureña","Húngara","India","Indonesia","Iraní","Iraquí","Irlandesa","Islandesa","Israelí","Italiana","Jamaiquina","Japonesa","Jordana","Kazaja","Keniana","Kirguisa","Kiribatiana","Kuwaití","Laosiana","Lesotense","Letona","Libanesa","Liberiana","Libia","Liechtensteiniana","Lituana","Luxemburguesa","Macedonia","Malasia","Malauí","Maldiva","Maliense","Maltesa","Marroquí","Mauriciana","Mexicana","Moldava","Monegasca","Mongola","Namibia","Nepalesa","Nicaragüense","Nigeriana","Noruega","Neozelandesa","Panameña","Paraguaya","Peruana","Polaca","Portuguesa","Qatarí","Rumana","Rusa","Senegalesa","Serbia","Singapurense","Siria","Somalí","Sudafricana","Sueca","Suiza","Tailandesa","Tanzana","Tunecina","Turca","Ucraniana","Ugandesa","Uruguaya","Venezolana","Vietnamita","Yemení","Zambiana","Zimbabuense"];
                            foreach ($nacionalidades as $n):
                            ?>
                                <option value="<?= htmlspecialchars($n, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($n, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="fieldset-seccion">
                <legend>Contacto</legend>

                <div class="grupo-fila">
                    <div class="grupo">
                        <label for="telefono">Teléfono <span class="requerido">*</span></label>
                        <input type="tel" name="telefono" id="telefono"
                            pattern="^6[0-9]{3}-[0-9]{4}$" maxlength="9"
                            placeholder="6123-4567"
                            title="Formato: 6123-4567" required>
                    </div>
                    <div class="grupo">
                        <label for="correo">Correo Electrónico <span class="requerido">*</span></label>
                        <input type="email" name="correo" id="correo"
                            pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$"
                            maxlength="254" placeholder="ejemplo@correo.com"
                            autocomplete="email" required>
                    </div>
                </div>

                <div class="grupo">
                    <label for="residencia">Residencia <span class="requerido">*</span></label>
                    <input type="text" name="residencia" id="residencia"
                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s#.,-]{5,100}$"
                        maxlength="100"
                        placeholder="Ej: Calle 50, Edificio Torres, Apto 3B"
                        title="Ingrese una dirección válida." required>
                </div>
            </fieldset>

            <p class="nota-requerido"><span class="requerido">*</span> Campos obligatorios</p>

            <button type="submit" class="btn-primario">📨 Enviar Solicitud</button>

        </form>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../Layout/footer.php'; ?>
