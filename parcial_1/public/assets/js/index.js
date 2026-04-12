import * as game from "./game.js";

const container = document.querySelector(".game-container");
const canvas = document.querySelector(".game-canvas");
const context = canvas.getContext("2d");

const gameData = {
  container,
  canvas,
  context,
};

canvas.addEventListener("mousedown", (event) => {

    console.log("click");
});

function gameLoop()
{
    console.log("Frame");
    gameData.context.clearRect(0, 0, gameData.canvas.width, gameData.canvas.height);

    game.assets.load.fondo({ gameData: gameData, name: "main" });
    game.assets.load.personaje({ gameData: gameData, name: "kangre" });
   
    // window.requestAnimationFrame(gameLoop);
}

window.requestAnimationFrame(gameLoop);

// game.assets.load.personaje({ gameData: gameData, name: "champi" });
//game.assets.load.personaje({ gameData: gameData, name: "jojo" });
