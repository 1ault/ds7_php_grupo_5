<<<<<<< HEAD
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
                    src: "assets/img/kangre.webp"
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
=======
export const signal = {
    async buttonSignal({ gameStatus, signal })
    {
        console.log(signal);
        const response = await fetch("/api/input_signal.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            body: JSON.stringify({ gameStatus, signal })
        });

        let data;

        try {
            data = await response.json();
        } catch (e) {
            console.error(e);
            throw new Error(`Error: invalid json response`);
        }

        if (!response.ok || data.error) {
            throw new Error(`${data.error}`);
        }

        return data;
    },
};

export const data = {
    async update({ gameStatus, gameStatusUpdate })
    {
        
        gameStatusUpdate.canvas.container = gameStatus.canvas.container;
        gameStatusUpdate.canvas.canvas = gameStatus.canvas.canvas;
        gameStatusUpdate.canvas.context = gameStatus.canvas.context;

        gameStatusUpdate.assets.fondo.main = await assets.load.fondo({ name: "main" });
        gameStatusUpdate.assets.personaje.kangre = await assets.load.personaje({ name: "kangre" });
        gameStatusUpdate.assets.enemigo.champi = await assets.load.enemigo({ name: "champi" });

        return gameStatusUpdate;
    },
    state: {
       async save ({ gameStatus }) 
        {

            const response = await fetch("/api/save_game_state.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=utf-8"
                },
                body: JSON.stringify(gameStatus)
            });


            let data;

            try {
                data = await response.json();
            } catch (e) {
                console.error(e);
                throw new Error(`Error: invalid json response`);
            }

            if (!response.ok || data.error) {
                throw new Error(`Error: ${data.error}`);
            }

        },
        async load()
        {
            const response = await fetch("/api/get_game.php?game_state=state", {
                method: "GET",
            });

            if (!response.ok) {
                throw new Error(`HTTP error: ${response.status}`);
            }

            let data;

            try {
                data = await response.json();
            } catch (e) {
                throw new Error(`json response error: ${e.message}`);
            }

            if (data.error) {
                throw new Error(`api error: ${data.error}`);
            }

            return data;
        }
    },
};

export const gui = {
    drawButtonText
    ({
        gameStatus,
        id,
        text,
        position_x,
        position_y,
        width,
        height,
        action
    })
    {
        const context = gameStatus.canvas.context;
        context.fillStyle = "#555555";
        context.fillRect
        (
            position_x, 
            position_y, 
            width, 
            height
        );

        context.fillStyle = "#ffffff";
        context.fillText
        (
            text, 
            position_x + 10, 
            position_y + 25
        );

        gameStatus.gui.push({
            id,
            text,
            position_x,
            position_y,
            width,
            height,
            action
        });
    },

    drawLabelText
    (
        gameStatus, 
        id,
        position_x,
        position_y,
        width,
        height
    ) {
        const ctx = gameStatus.canvas.context;

        ctx.fillStyle = "#555555";
        ctx.fillRect(
            btn.position_x,
            btn.position_y,
            btn.width,
            btn.height
        );

        ctx.fillStyle = "#ffffff";
        ctx.font = "16px Arial";
        ctx.fillText(
            btn.text,
            btn.position_x + 10,
            btn.position_y + 25
        );
    },

    drawDialog
    ({
        gameStatus,
        id,
        text,
        position_x,
        position_y,
        width,
        height
    })
    {
        const context = gameStatus.canvas.context;

        // box
        context.fillStyle = "rgba(0,0,0,0.7)";
        context.fillRect
        (
            position_x, 
            position_y, 
            width, 
            height
        );

        // border
        context.strokeStyle = "white";
        context.strokeRect
        (
            position_x, 
            position_y, 
            width, 
            height
        );

        // text
        context.fillStyle = "white";
        context.font = "16px Arial";

        const lines = text.split("\n");

        lines.forEach((line, i) => {
            context.fillText
            (
                line, 
                position_x + 10, 
                position_y + 25 + i * 18
            );
        });


        gameStatus.gui.push({
            id,
            text,
            position_x,
            position_y,
            width,
            height,
        });
    },
};


export const assets = {
    load: {
        async fondo({ name })
        {
            const response = await fetch("/api/load_fondo.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=utf-8"
                },
                body: JSON.stringify(name)
            });

            let data;

            try {
                data = await response.json();
            } catch (e) {
                console.error(e);
                throw new Error(`Error: invalid json response`);
            }

            if (!response.ok || data.error) {
                throw new Error(`Error: ${data.error}`);
            }

            const image = new Image();
            image.src = data.sprite;
             
            const position_x = 0;
            const position_y = 0;

            const sprite = data.sprite;

            return { image, sprite, position_x, position_y };
        },

        async enemigo({ name })
        {
            const response = await fetch("/api/load_enemigo.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=utf-8"
                },
                body: JSON.stringify({
                    "name": name
                })
            });

            let data;

            try {
                data = await response.json();
            } catch (e) {
                console.error(e);
                throw new Error(`Error: invalid json response`);
            }

            if (!response.ok || data.error) {
                throw new Error(`Error: ${data.error}`);
            }

            const image = new Image();
            image.src = data.sprite;

            const position_x = data.position_x;
            const position_y = data.position_y;
            const sprite = data.sprite;
            const nombre = data.nombre;
            const vida = data.vida;
            const mana = data.mana;
            const habilidades = data.habilidades;

            return { image, sprite, position_x, position_y, nombre, vida, mana, habilidades };
        },

        async personaje({ name })
        {
            const response = await fetch("/api/load_personaje.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=utf-8"
                },
                body: JSON.stringify({
                    "name": name
                })
            });

            let data;

            try {
                data = await response.json();
            } catch (e) {
                console.log(`>`);
                console.log(`Not load personaje: ${name}`);
                console.error(e);
                throw new Error(`Error: invalid json response`);
                console.log(`<`);
            }

            if (!response.ok || data.error) {
                throw new Error(`Error: ${data.error}`);
            }

            console.log(data);

            const image = new Image();
            image.src = data.sprite;
            
            const position_x = data?.position_x ?? 0;
            const position_y = data?.position_y ?? 0;
            const sprite = data?.sprite ?? "";
            const nombre = data?.nombre ?? "";
            const vida = data?.vida ?? 0;
            const mana = data?.mana ?? 0;
            const habilidades = data?.habilidades ?? [];
            
            console.log(sprite);

            return { image, position_x, position_y, sprite, nombre, vida, mana, habilidades };

        },
  },
};
>>>>>>> 324c2646896017040376d9eb086b34de89808e33
