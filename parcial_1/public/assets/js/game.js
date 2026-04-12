import { Mundo } from "./Mundo.js";
import { GameObject } from "./GameObject.js";

export class game {
    constructor({ canvas }) {
        this.canvas = canvas;
        this.ctx = canvas.getContext("2d");
        this.running = false;

        // Canvas responsive
        this._resize();
        window.addEventListener("resize", () => this._resize());

        // Escena inicial
        this.mundo = new Mundo({
            fondoSrc: "main",
            personajes: [
                new GameObject({
                    x: 100,
                    y: 300,
                    src: "/assets/img/kangre.webp"
                })
            ]
        });
    }

    _resize() {
        // Mantiene relación 16:9 dentro de la ventana
        const ratio = 16 / 9;
        const winW = window.innerWidth;
        const winH = window.innerHeight;

        let w = winW;
        let h = winW / ratio;

        if (h > winH) {
            h = winH;
            w = winH * ratio;
        }

        this.canvas.width = Math.floor(w);
        this.canvas.height = Math.floor(h);
    }

    start() {
        this.running = true;
        this._loop();
    }

    stop() {
        this.running = false;
    }

    _loop() {
        if (!this.running) return;

        // Limpiar
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Update + Draw
        this.mundo.update();
        this.mundo.draw(this.ctx, this.canvas.width, this.canvas.height);

        requestAnimationFrame(() => this._loop());
    }
}