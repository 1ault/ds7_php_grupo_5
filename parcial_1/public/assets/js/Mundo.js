import { GameObject } from "./GameObject.js";

export class Mundo
{
  constructor(config) {
    this.element = config.element;
    this.canvas  = this.element.querySelector(".game-canvas");
    this.ctx     = this.canvas.getContext("2d");
  }

  run() {


    const image = new Image();

    image.onload = () => {
      this.ctx.drawImage(image, 0, 0);
    };

    image.src = '/assets/img/fondo.webp';
    console.log(image);



    const personaje = new GameObject
    (
        {
            positionX: 250,
            positionY: 300,
            src: '/assets/img/kangre.webp'
        }
    );
    personaje.sprite.draw(this.ctx);


    const playerImage = new Image();

    playerImage.onload = () => {
      this.ctx.drawImage
        (
            playerImage, 
            playerPositionX, 
            playerPositionY
        );
    };
    playerImage.src = ;
    console.log(playerImage);


  }
}
