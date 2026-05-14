import { Seleccion } from "./Seleccion.js";
import { Tienda } from "./Tienda.js";
import { Combate } from "./Combate.js";

<<<<<<< HEAD
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
=======
const container = document.querySelector(".game-container");
const canvas = document.querySelector(".game-canvas");
const context = canvas.getContext("2d");

let gameStatus = {
    "canvas": {
        "container": container,
        "canvas": canvas,
        "context": context,
    },
    "assets": {
        "fondo": {},
        "personaje": {},
        "enemigo": {},
    },
    "input": {
        "gui": true,
    },
    "gui": [],
};


async function gameInit()
{
    try{
        gameStatus = await game.data.state.load();

        console.log("Test");
        console.log(gameStatus);
        console.log(container);
        console.log(canvas);
        console.log(gameStatus.canvas);

        gameStatus.canvas.container = container;
        gameStatus.canvas.canvas = canvas;
        gameStatus.canvas.context = context;

        gameStatus.assets.fondo.main = await game.assets.load.fondo({ name: "main" });
        gameStatus.assets.personaje.kangre = await game.assets.load.personaje({ name: "kangre" });
        gameStatus.assets.enemigo.champi = await game.assets.load.enemigo({ name: "champi" });
        
        console.log("------");
        console.log(gameStatus.canvas);
    } catch (e) 
    {
        console.log(e);

        gameStatus.assets.fondo.main = await game.assets.load.fondo({ name: "main" });
        gameStatus.assets.personaje.kangre = await game.assets.load.personaje({ name: "kangre" });
        gameStatus.assets.enemigo.champi = await game.assets.load.enemigo({ name: "champi" });

        const guiDialog = {
            type: "dialog",
            id: "dialog",
            text: "...",
            position_x: 0 - 2,
            position_y: 0 - 2,
            width: 330, 
            height: gameStatus.canvas.canvas.height + 4
        };
        gameStatus.gui.push(guiDialog);


        const button_position_y = 45;
        const button_position_x = 121
        const guiButtonTextAtaque =
        {
            type: "button_text",
            id: "ataque",
            text: "Ataque",
            position_x: gameStatus.canvas.canvas.width - button_position_x * 2, 
            position_y: gameStatus.canvas.canvas.height - button_position_y, 
            width: 120, 
            height: 40, 
            action: "atacar"
        };
        gameStatus.gui.push(guiButtonTextAtaque);


        const guiButtonTextHabilidad =
        {
            type: "button_text",
            id: "habilidad",
            text: "Habilidad",
            position_x: gameStatus.canvas.canvas.width - button_position_x * 3, 
            position_y: gameStatus.canvas.canvas.height - button_position_y, 
            width: 120, 
            height: 40, 
            action: "habilidad"
        };
        gameStatus.gui.push(guiButtonTextHabilidad);


        const guiButtonTextInventario =
        {
            type: "button_text",
            id: "inventario",
            text: "Inventario",
            position_x: gameStatus.canvas.canvas.width - button_position_x * 4, 
            position_y: gameStatus.canvas.canvas.height - button_position_y, 
            width: 120, 
            height: 40, 
            action: "inventario"
        };
        gameStatus.gui.push(guiButtonTextInventario);

        console.log(gameStatus);
        console.log(gameStatus.assets.personaje);
        const gameState = await game.data.state.save({ gameStatus: gameStatus });



    }
    console.log(gameStatus);
    //gameStatus.assets.enemigo.champi = await game.assets.load.enemigos({ name: "champi" });
    //gameStatus.assets.enemigo.jojo = await game.assets.load.enemigos({ name: "jojo" });
 
    console.log("Game");
    console.log(gameStatus.assets.fondo.main);
    console.log(gameStatus.assets.personaje.kangre);

    window.requestAnimationFrame(gameLoop);
}

gameInit();

function gameLoop()
{
    console.log("Frame");
    gameStatus.canvas.context.clearRect
    (
        0, 
        0, 
        gameStatus.canvas.canvas.width, 
        gameStatus.canvas.canvas.height
    );


    gameStatus.canvas.context.drawImage(
        gameStatus.assets.fondo.main.image,
        gameStatus.assets.fondo.main.position_x,
        gameStatus.assets.fondo.main.position_y
    );


    gameStatus.canvas.context.drawImage(
        gameStatus.assets.personaje.kangre.image,
        gameStatus.assets.personaje.kangre.position_x,
        gameStatus.assets.personaje.kangre.position_y
    );


    gameStatus.canvas.context.drawImage(
        gameStatus.assets.enemigo.champi.image,
        gameStatus.assets.enemigo.champi.position_x,
        gameStatus.assets.enemigo.champi.position_y
    );

    for (const gui of gameStatus.gui) {
        
        if (gui.type === "button_text")
        {

            game.gui.drawButtonText
            (
                {
                    gameStatus: gameStatus,
                    id: gui.id,
                    text: gui.text,
                    position_x: gui.position_x, 
                    position_y: gui.position_y,
                    width: gui.width,
                    height: gui.height,
                    action: gui.action
                }
            );
        }

        if (gui.type === "dialog")
        {
            game.gui.drawDialog
            (
                {
                    gameStatus: gameStatus,
                    id: gui.id,
                    text: gui.text,
                    position_x: gui.position_x,
                    position_y: gui.position_y,
                    width: gui.width,
                    height: gui.height,
                }
            );
        }
    }
   
    //game.data.state.save();
    window.requestAnimationFrame(gameLoop);
}


canvas.addEventListener("mousedown", async (event) => {
    const rect = canvas.getBoundingClientRect();

    const mouse_position_x = event.clientX - rect.left;
    const mouse_position_y = event.clientY - rect.top;
    console.log(mouse_position_y);
    console.log(mouse_position_x);




    if (gameStatus.input.gui == false) { 
        console.log("User no input"); 
        return;
    }

    for (const btn of gameStatus.gui) 
    {
        console.log(btn.type);
        if (btn.type === "button_text")
        {
            if (
                mouse_position_x >= btn.position_x &&
                mouse_position_x <= btn.position_x + btn.width &&
                mouse_position_y >= btn.position_y &&
                mouse_position_y <= btn.position_y + btn.height
            ) {
                gameStatus.input.gui = false;
                console.log("click:", btn.action);

                const gameStatusUpdate = await game.signal.buttonSignal({
                    gameStatus,
                    signal: btn.action
                });

                gameStatus = await game.data.update({ gameStatus: gameStatus, gameStatusUpdate: gameStatusUpdate });
                break;
            }
        }
    }


});
>>>>>>> 324c2646896017040376d9eb086b34de89808e33
