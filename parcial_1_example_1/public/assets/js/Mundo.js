import { GameObject } from "./GameObject.js";

export class Mundo {
    constructor({ fondoSrc, personajes = [] }) {
        this.fondoSprite = null;
        this.personajes = personajes;
        this.loaded = false;

        // Carga el fondo desde la API PHP
        this._cargarFondo(fondoSrc);
    }

    async _cargarFondo(fondoSrc) {
        try {
            const res = await fetch("/api/load_fondo.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name: fondoSrc })
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const data = await res.json();

            const img = new Image();
            img.onload = () => {
                this.fondoImg = img;
                this.loaded = true;
            };
            img.src = data.sprite;

        } catch (err) {
            console.error("Error cargando fondo:", err);
        }
    }

    update() {
        // Aquí irá lógica de movimiento, colisiones, etc.
    }

    draw(ctx, canvasWidth, canvasHeight) {
        // Fondo
        if (this.fondoImg) {
            ctx.drawImage(this.fondoImg, 0, 0, canvasWidth, canvasHeight);
        } else {
            // Placeholder mientras carga
            ctx.fillStyle = "#1a1a2e";
            ctx.fillRect(0, 0, canvasWidth, canvasHeight);
        }

        // Personajes
        for (const personaje of this.personajes) {
            personaje.draw(ctx);
        }
    }
}