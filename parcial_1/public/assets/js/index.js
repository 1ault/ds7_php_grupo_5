import * as game from "./game.js";

const container = document.querySelector(".game-container");
const canvas = document.querySelector(".game-canvas");
const context = canvas.getContext("2d");

const gameData = {
  container,
  canvas,
  context,
};

game.assets.load.fondo({ gameData: gameData, name: "main" });
