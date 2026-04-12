import { estadoJuego } from "./index.js";

const ITEMS_TIENDA = [
    { id: 'pocion_vida',  nombre: 'Poción de Vida',  tipo: 'pocion', efecto: 50,  valor: 50,  icono: '❤️' },
    { id: 'pocion_mana',  nombre: 'Poción de Mana',  tipo: 'pocion', efecto: 50,  valor: 100, icono: '💧' },
    { id: 'espada',       nombre: 'Espada',           tipo: 'arma',   efecto: 15,  valor: 150, icono: '⚔️' },
    { id: 'escudo',       nombre: 'Escudo Defensivo', tipo: 'defensa',efecto: 10,  valor: 150, icono: '🛡️' },
];

export class Tienda {
    constructor({ contenedor, onEquipar }) {
        this.contenedor = contenedor;
        this.onEquipar  = onEquipar;
        this.carrito    = []; // items comprados
    }

    init() {
        const p = estadoJuego.personajeElegido;
        const monedas = 200; // monedas iniciales

        this.monedas = monedas;
        this.carrito = [];
        this._render();
    }

    _render() {
        this.contenedor.innerHTML = `
            <div class="tienda-wrapper">

                <div class="tienda-header">
                    <h2>🏪 Tienda</h2>
                    <span class="tienda-monedas">💰 <span id="monedas-valor">${this.monedas}</span></span>
                </div>

                <div class="tienda-items">
                    ${ITEMS_TIENDA.map(item => `
                        <div class="tienda-item" id="item-${item.id}">
                            <div class="item-icono">${item.icono}</div>
                            <div class="item-info">
                                <p class="item-nombre">${item.nombre}</p>
                                <p class="item-efecto">${item.tipo === 'pocion' ? `+${item.efecto} ${item.tipo === 'pocion_mana' ? 'mana' : 'vida'}` : `+${item.efecto} daño/defensa`}</p>
                            </div>
                            <button class="btn btn-comprar" data-id="${item.id}" data-valor="${item.valor}">
                                $${item.valor}
                            </button>
                        </div>
                    `).join('')}
                </div>

                <div class="tienda-carrito">
                    <h3>🎒 Equipado</h3>
                    <ul id="carrito-lista"><li>Ningún ítem aún</li></ul>
                </div>

                <button class="btn" id="btn-equipar">Equipar y Jugar ▶</button>

            </div>
        `;

        // Eventos de compra
        this.contenedor.querySelectorAll('.btn-comprar').forEach(btn => {
            btn.addEventListener('click', () => {
                const id    = btn.dataset.id;
                const valor = parseInt(btn.dataset.valor);
                this._comprar(id, valor);
            });
        });

        // Botón equipar
        document.getElementById('btn-equipar').addEventListener('click', () => {
            this.onEquipar(this.carrito);
        });
    }

    _comprar(id, valor) {
        if (this.monedas < valor) return;

        const item = ITEMS_TIENDA.find(i => i.id === id);
        if (!item) return;

        this.monedas -= valor;
        this.carrito.push(item);

        // Actualizar monedas
        document.getElementById('monedas-valor').textContent = this.monedas;

        // Actualizar carrito
        const lista = document.getElementById('carrito-lista');
        lista.innerHTML = this.carrito
            .map(i => `<li>${i.icono} ${i.nombre}</li>`)
            .join('');

        // Deshabilitar botón si no alcanza
        this.contenedor.querySelectorAll('.btn-comprar').forEach(btn => {
            if (parseInt(btn.dataset.valor) > this.monedas) {
                btn.disabled = true;
            }
        });
    }
}