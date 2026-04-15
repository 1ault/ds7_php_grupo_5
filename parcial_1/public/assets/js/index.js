import { Seleccion } from "./Seleccion.js";
import { Tienda } from "./Tienda.js";
import { Combate } from "./Combate.js";

export const estadoJuego = {
    personajeElegido: null,
    itemsEquipados:   [],
    ronda:            1,
    rondasGanadas:    0,
};

export function irA(idPantalla) {
    document.querySelectorAll('.pantalla').forEach(p => p.classList.add('oculto'));
    document.getElementById(idPantalla).classList.remove('oculto');
}

//portada
const btnJugar = document.getElementById("btn-jugar");
const portada = document.getElementById("portada");
const pantallaSeleccion = document.getElementById("pantalla-seleccion");

btnJugar.addEventListener("click", () => {
    portada.classList.add("oculto"); // oculta portada
    pantallaSeleccion.classList.remove("oculto"); // muestra juego
});

// Pantalla 1: Selección
const canvasSeleccion = document.getElementById('canvas-seleccion');
new Seleccion({
    canvas: canvasSeleccion,
    onElegir: (personaje) => {
        estadoJuego.personajeElegido = personaje;
        irA('pantalla-tienda');
        tienda.init();
    }
});

// Pantalla 2: Tienda
const tienda = new Tienda({
    contenedor: document.getElementById('pantalla-tienda'),
    onEquipar: (items) => {
        estadoJuego.itemsEquipados = items;
        irA('pantalla-combate');
        combate.init(estadoJuego.personajeElegido, estadoJuego.itemsEquipados);
    }
});

// Pantalla 3: Combate
const combate = new Combate({
    onFinRonda: (gano) => {
        if (!gano) {
        // 🔥 PERDISTE INMEDIATAMENTE
        irA('pantalla-fin');
        document.getElementById('pantalla-fin').innerHTML = `
            <div style="text-align:center">
                <h1 style="color:red;font-size:3rem">💀 Perdiste...</h1>
                <button class="btn" onclick="location.reload()">🔄 Jugar de nuevo</button>
            </div>`;
        return;
    }

    // ✅ Ganó la ronda
    estadoJuego.rondasGanadas++;
    estadoJuego.ronda++;

    // 🏆 Ganó las 3 rondas
    if (estadoJuego.rondasGanadas >= 3) {
        irA('pantalla-fin');
        document.getElementById('pantalla-fin').innerHTML = `
            <div style="text-align:center">
                <h1 style="color:#e2b04a;font-size:3rem">🏆 ¡Ganaste Campeón!</h1>
                <button class="btn" onclick="location.reload()">🔄 Jugar de nuevo</button>
            </div>`;
        return;
        } else {
            // Siguiente ronda — volver a tienda
            irA('pantalla-tienda');
            tienda.init();
        }
    }
});