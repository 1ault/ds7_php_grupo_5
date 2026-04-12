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
        if (gano) estadoJuego.rondasGanadas++;
        estadoJuego.ronda++;

        if (estadoJuego.ronda > 3) {
            // Fin del juego
            irA('pantalla-fin');
            const msg = estadoJuego.rondasGanadas >= 2 ? '🏆 ¡Ganaste Campeón!' : '💀 Perdiste...';
            document.getElementById('pantalla-fin').innerHTML = `
                <div style="text-align:center">
                    <h1 style="color:#e2b04a;font-size:3rem">${msg}</h1>
                    <p style="color:#ccc;margin:16px 0">Rondas ganadas: ${estadoJuego.rondasGanadas}/3</p>
                    <button class="btn" onclick="location.reload()">🔄 Jugar de nuevo</button>
                </div>`;
        } else {
            // Siguiente ronda — volver a tienda
            irA('pantalla-tienda');
            tienda.init();
        }
    }
});