class Mundo {
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

    image.src = '/assets/img/screen/1.png';
    console.log(image);
  }
}

(function () {
    const mundo = new Mundo({
        element: document.querySelector(".game-container")
    });

    mundo.run();
})();

