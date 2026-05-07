const PERSONAJES = [
    {
        nombre: 'Hongo',
        src:    'assets/img/champi.webp',  
        x:      80,
        stats: {
            Vida:      100,
            Mana:      100,
            Nivel:     1,
            Habilidad: 'Espora',
            Especial:  'Espora Venenosa',
            Pasiva:    'Regenera 5 vida/turno'
        }
    },
    {
        nombre: 'Cangrejo',
        src:    'assets/img/kangre.webp',  
        x:      300,
        stats: {
            Vida:      120,
            Mana:      80,
            Nivel:     1,
            Habilidad: 'Corte Limpio',
            Especial:  'Pinza Aplastante',
            Pasiva:    'Reduce daño recibido 10%'
        }
    },
    {
        nombre: 'Axolote',
        src:    'assets/img/jojo.webp',   
        x:      520,
        stats: {
            Vida:      110,
            Mana:      120,
            Nivel:     1,
            Habilidad: 'Burbuja',
            Especial:  'Torrente',
            Pasiva:    'Recupera mana al subir nivel'
        }
    }
];

const ANCHO  = 180;
const ALTO   = 220;
const Y_BASE = 120;

export class Seleccion {
    constructor({ canvas, onElegir }) {
        this.canvas    = canvas;
        this.ctx       = canvas.getContext('2d');
        this.onElegir  = onElegir;
        this.seleccion = null;
        this.imagenes  = [];

        this._cargarImagenes().then(() => this._dibujar());
        this.canvas.addEventListener('click', (e) => this._onClick(e));
        this.canvas.addEventListener('mousemove', (e) => this._onHover(e));
    }

    async _cargarImagenes() {
        this.imagenes = await Promise.all(
            PERSONAJES.map(p => new Promise(res => {
                const img = new Image();
                img.onload = () => res(img);
                img.src = p.src;
            }))
        );
    }

    _dibujar() {
        const ctx = this.ctx;
        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        PERSONAJES.forEach((p, i) => {
            const seleccionado = this.seleccion === i;

            // Fondo del personaje
            ctx.fillStyle = seleccionado ? '#e2b04a22' : '#bbcce0';
            ctx.strokeStyle = seleccionado ? '#e2b04a' : '#ff0000e8';
            ctx.lineWidth = seleccionado ? 3 : 1;
            ctx.beginPath();
            ctx.roundRect(p.x, Y_BASE, ANCHO, ALTO, 12);
            ctx.fill();
            ctx.stroke();

            // Imagen
            if (this.imagenes[i]) {
                ctx.drawImage(this.imagenes[i], p.x + 15, Y_BASE + 10, 150, 150);
            }

            // Nombre
            ctx.fillStyle = seleccionado ? '#e2b04a' : '#fff';
            ctx.font = 'bold 18px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(p.nombre, p.x + ANCHO / 2, Y_BASE + ALTO - 16);
        });
    }

    _onClick(e) {
        const rect = this.canvas.getBoundingClientRect();
        const mx   = e.clientX - rect.left;
        const my   = e.clientY - rect.top;

        PERSONAJES.forEach((p, i) => {
            if (mx >= p.x && mx <= p.x + ANCHO && my >= Y_BASE && my <= Y_BASE + ALTO) {
                this.seleccion = i;
                this._dibujar();
                this._mostrarStats(i);
            }
        });
    }

    _onHover(e) {
        const rect = this.canvas.getBoundingClientRect();
        const mx   = e.clientX - rect.left;
        const my   = e.clientY - rect.top;

        let sobreAlguno = false;
        PERSONAJES.forEach((p) => {
            if (mx >= p.x && mx <= p.x + ANCHO && my >= Y_BASE && my <= Y_BASE + ALTO) {
                sobreAlguno = true;
            }
        });
        this.canvas.style.cursor = sobreAlguno ? 'pointer' : 'default';
    }

    _mostrarStats(i) {
        const p       = PERSONAJES[i];
        const nombre  = document.getElementById('stats-nombre');
        const lista   = document.getElementById('stats-lista');
        const btnEleg = document.getElementById('btn-elegir');

        nombre.textContent = p.nombre;
        lista.innerHTML = Object.entries(p.stats)
            .map(([k, v]) => `<li>${k}: <span>${v}</span></li>`)
            .join('');

        btnEleg.disabled = false;
        btnEleg.onclick  = () => this.onElegir(p);
    }
}