<form action="/api/auth/register" method="POST">
    <label for="">Registro</label>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" placeholder="nombre" required>

    <label for="email">Correo:</label>
    <input type="email" id="email" name="email" placeholder="example@local.com" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" placeholder="contrasena" required>

    <label for="telefono">Telefono</label>
    <input type="tel" id="telefono" name="telefono" placeholder="123456769">

    <label for="date">Fecha de nacimiento</label>
    <input type="date" id="date" value="2017-06-01">

    <fieldset>
      <legend>Genero</legend>
      <label><input type="radio" name="genero" value="male"> Male</label>
      <label><input type="radio" name="genero" value="female"> Female</label>
      <label><input type="radio" name="genero" value="vacio"> No especificar</label>
      <label><input type="radio" name="genero" value="other" id="genero_other"> Other</label>
      
      <div id="genero_custom_box" style="display:none;">
        <label for="genero_custom">Por favor especifique:</label>
        <input type="text" id="genero_custom" name="genero_custom" placeholder="Por favor especifique">
      </div>
    </fieldset>

    <label for="nacionalidad">Nacionalidad:</label>
    <input list="nacionalidades" id="nacionalidad" name="nacionalidad" placeholder="...">
    <datalist id="nacionalidades">
      <option value="Panama">
      <option value="Mexico">
      <option value="España">
    </datalist>

    <label for="residencia">Dirrecion residencial</label>
    <input type="text" name="autor" placeholder="...">

    <button type="submit">Guardar</button>
</form>

<a href="/login" class="button-href">Inicia sesión</a>

<?php if (!empty($logs)): ?>
    <ul class="log">
        <?php foreach ($logs as $log): ?>
            <li><?= htmlspecialchars($log, ENT_QUOTES, "UTF-8"); ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>

