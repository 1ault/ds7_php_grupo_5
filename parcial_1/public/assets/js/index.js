import * as game from "./game.js";

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
        "gui": false,
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
            text: "Hello\nWelcome to the game!",
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


canvas.addEventListener("mousedown", (event) => {
    const rect = canvas.getBoundingClientRect();

    const mouse_position_x = event.clientX - rect.left;
    const mouse_position_y = event.clientY - rect.top;
    console.log(mouse_position_y);
    console.log(mouse_position_x);

    gameStatus.gui.forEach(btn => {

        if (gameStatus.input.gui) { return; }
        

        if (
            mouse_position_x >= btn.position_x &&
            mouse_position_x <= btn.position_x + btn.width &&
            mouse_position_y >= btn.position_y &&
            mouse_position_y <= btn.position_y + btn.height
        ) {

            gameStatus.input.gui = true;
            console.log("click", btn.action);

            game.signal.buttonSignal({
                signal: btn.action
            });
        }
    });
});
