<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Esta línea arregla todas las rutas de golpe -->
    <base href="/Parcial1/ds7_php_grupo_5/parcial_1/public/">
    <title>Juego</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

    <!-- PORTADA -->
    <div class="portada" id="portada">
        <div class="contenido-portada">
            <h1 id="titulo">PARCIAL 1</h1>
            <h1 id="subtitulo">PHP</h1>

            <div class="integrantes">
                <h3>Integrantes:</h3>
                <ul id="nombres">    
                    <li>Jonathan Quinto | 8-1007-1971</li>
                    <li>Whitney Ault | 8-984-1977</li>
                    <li>Abdias Rueda | 8-1011-2210</li>
                    <li>Nadesh Valdes | 4-779-2117</li>
                    <li>Alexander Castroverde | 8-1017-805</li>
                </ul>
            </div>
        </div>
        <button class="btn" id="btn-jugar">Jugar</button>
        <div class="canvas-container">
            <img src="assets/img/tres.webp" alt="">
        </div>
    </div>

    <div class="pantalla oculto" id="pantalla-seleccion">
        <div class="seleccion-stats" id="stats-panel">
            <h2 id="stats-nombre">Elige un personaje</h2>
            <ul id="stats-lista">
                <li>Haz clic sobre uno</li>
            </ul>
            <button class="btn" id="btn-elegir" disabled>Elegir</button>
        </div>
        <canvas id="canvas-seleccion" width="800" height="500"></canvas>
    </div>

    <div class="pantalla oculto" id="pantalla-tienda"></div>
    <div class="pantalla oculto" id="pantalla-combate">
    <div class="combate-wrapper">

        <div class="combate-log" id="combate-log">
            <h3>⚔️ Combate iniciado</h3>
            <ul id="log-lista"></ul>
        </div>

        <div class="combate-arena">

            <div class="combate-personaje" id="bloque-enemigo">
                <img id="enemigo-img" src="" alt="">
                <p id="enemigo-nombre">???</p>
                <div class="barra-contenedor">
                    <label>Vida</label>
                    <div class="barra"><div class="barra-fill roja" id="enemigo-vida-barra"></div></div>
                </div>
                <div class="barra-contenedor">
                    <label>Mana</label>
                    <div class="barra"><div class="barra-fill azul" id="enemigo-mana-barra"></div></div>
                </div>
                <p class="nivel-txt">Nivel <span id="enemigo-nivel">1</span></p>
            </div>

            <div class="combate-vs">VS</div>

            <div class="combate-personaje" id="bloque-jugador">
                <img id="jugador-img" src="" alt="">
                <p id="jugador-nombre">???</p>
                <div class="barra-contenedor">
                    <label>Vida</label>
                    <div class="barra"><div class="barra-fill roja" id="jugador-vida-barra"></div></div>
                </div>
                <div class="barra-contenedor">
                    <label>Mana</label>
                    <div class="barra"><div class="barra-fill azul" id="jugador-mana-barra"></div></div>
                </div>
                <p class="nivel-txt">Nivel <span id="jugador-nivel">1</span></p>
            </div>

        </div>

        <div class="combate-acciones">
            <button class="btn btn-habilidad" id="btn-habilidad-normal">🗡️ Habilidad Normal</button>
            <button class="btn btn-habilidad" id="btn-habilidad-especial">✨ Habilidad Especial</button>
            <div id="contenedor-pociones"></div>
        </div>

    </div>
</div>
    <div class="pantalla oculto" id="pantalla-entre-rondas"></div>
    <div class="pantalla oculto" id="pantalla-fin"></div>

    <script type="module" src="assets/js/index.js"></script>
</body>
</html>