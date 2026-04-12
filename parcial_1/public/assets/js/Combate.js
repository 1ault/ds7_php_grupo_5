import { estadoJuego, irA } from "./index.js";

const TODOS_PERSONAJES = [
    { nombre: 'Hongo',   src: 'assets/img/champi.webp', vida: 100, mana: 100, habilidad: 'Espora',       especial: 'Espora Venenosa', danoNormal: 25, danoEspecial: 40, costoNormal: 15, costoEspecial: 35 },
    { nombre: 'Cangrejo',src: 'assets/img/kangre.webp', vida: 120, mana: 80,  habilidad: 'Corte Limpio', especial: 'Pinza Aplastante',danoNormal: 35, danoEspecial: 55, costoNormal: 20, costoEspecial: 40 },
    { nombre: 'Axolote', src: 'assets/img/jojo.webp',   vida: 110, mana: 120, habilidad: 'Burbuja',      especial: 'Torrente',        danoNormal: 20, danoEspecial: 45, costoNormal: 10, costoEspecial: 30 },
];

export class Combate {
    constructor({ onFinRonda }) {
        this.onFinRonda = onFinRonda;
    }

    init(personajeElegido, itemsEquipados) {
        // Clonar stats del jugador
        const base = TODOS_PERSONAJES.find(p => p.nombre === personajeElegido.nombre);
        this.jugador = { ...base, vidaMax: base.vida, manaMax: base.mana };

        // Aplicar bonus de items
        this.bonusAtaque = 0;
        this.pociones    = 0;
        itemsEquipados.forEach(item => {
            if (item.tipo === 'arma')   this.bonusAtaque += item.efecto;
            if (item.tipo === 'pocion') this.pociones++;
            if (item.tipo === 'defensa') this.jugador.vidaMax += item.efecto;
        });
        this.jugador.vida = this.jugador.vidaMax;

        // Elegir enemigo aleatorio (distinto al jugador)
        const posibles = TODOS_PERSONAJES.filter(p => p.nombre !== this.jugador.nombre);
        const baseEnemigo = posibles[Math.floor(Math.random() * posibles.length)];
        this.enemigo = { ...baseEnemigo, vidaMax: baseEnemigo.vida, manaMax: baseEnemigo.mana };

        this.turnoJugador = true;
        this.log = [];

        this._renderUI();
        this._log('⚔️ ¡Combate iniciado!');
    }

    _renderUI() {
        // Jugador
        document.getElementById('jugador-img').src    = this.jugador.src;
        document.getElementById('jugador-nombre').textContent = this.jugador.nombre;
        document.getElementById('jugador-nivel').textContent  = estadoJuego.ronda;

        // Enemigo
        document.getElementById('enemigo-img').src    = this.enemigo.src;
        document.getElementById('enemigo-nombre').textContent = this.enemigo.nombre;
        document.getElementById('enemigo-nivel').textContent  = estadoJuego.ronda;

        this._actualizarBarras();

        // Botones
        document.getElementById('btn-habilidad-normal').onclick   = () => this._accion('normal');
        document.getElementById('btn-habilidad-especial').onclick  = () => this._accion('especial');
        document.getElementById('btn-pocion').onclick              = () => this._accion('pocion');

        // Nombre de habilidades en botones
        document.getElementById('btn-habilidad-normal').textContent   = `🗡️ ${this.jugador.habilidad}`;
        document.getElementById('btn-habilidad-especial').textContent  = `✨ ${this.jugador.especial}`;
    }

    _accion(tipo) {
        if (!this.turnoJugador) return;

        if (tipo === 'normal') {
            const critico = Math.random() < 0.2;
            let dano = this.jugador.danoNormal + this.bonusAtaque;
            if (critico) dano = Math.floor(dano * 1.5);
            if (this.jugador.mana < this.jugador.costoNormal) {
                this._log('❌ No tienes suficiente mana'); return;
            }
            this.jugador.mana -= this.jugador.costoNormal;
            this._danoA(this.enemigo, dano, critico, this.jugador.habilidad);

        } else if (tipo === 'especial') {
            const critico = Math.random() < 0.25;
            let dano = this.jugador.danoEspecial + this.bonusAtaque;
            if (critico) dano = Math.floor(dano * 1.8);
            if (this.jugador.mana < this.jugador.costoEspecial) {
                this._log('❌ No tienes suficiente mana'); return;
            }
            this.jugador.mana -= this.jugador.costoEspecial;
            this._danoA(this.enemigo, dano, critico, this.jugador.especial);

        } else if (tipo === 'pocion') {
            if (this.pociones <= 0) {
                this._log('❌ No tienes pociones'); return;
            }
            this.pociones--;
            const cura = 50;
            this.jugador.vida = Math.min(this.jugador.vidaMax, this.jugador.vida + cura);
            this._log(`❤️ ${this.jugador.nombre} usó una poción y recuperó ${cura} de vida`);
        }

        this._actualizarBarras();
        if (this._verificarFin()) return;

        // Turno enemigo
        this.turnoJugador = false;
        this._deshabilitarBotones(true);
        setTimeout(() => this._turnoEnemigo(), 1000);
    }

    _turnoEnemigo() {
        const usaEspecial = this.enemigo.mana >= this.enemigo.costoEspecial && Math.random() < 0.4;
        const critico     = Math.random() < 0.2;
        let dano, nombre;

        if (usaEspecial) {
            dano   = this.enemigo.danoEspecial;
            nombre = this.enemigo.especial;
            this.enemigo.mana -= this.enemigo.costoEspecial;
        } else {
            dano   = this.enemigo.danoNormal;
            nombre = this.enemigo.habilidad;
            this.enemigo.mana -= this.enemigo.costoNormal;
        }

        if (critico) dano = Math.floor(dano * 1.5);
        this._danoA(this.jugador, dano, critico, nombre);
        this._actualizarBarras();

        if (this._verificarFin()) return;

        this.turnoJugador = true;
        this._deshabilitarBotones(false);
    }

    _danoA(objetivo, dano, critico, nombreHabilidad) {
        objetivo.vida = Math.max(0, objetivo.vida - dano);
        const quien = objetivo === this.enemigo ? this.jugador.nombre : this.enemigo.nombre;
        let msg = `${quien} usó ${nombreHabilidad} — ${objetivo.nombre} recibió ${dano} de daño. Vida: ${objetivo.vida}`;
        if (critico) msg = `💥 ¡Crítico! ` + msg;
        this._log(msg);
    }

    _verificarFin() {
        if (this.enemigo.vida <= 0) {
            this._log(`🏆 ¡${this.enemigo.nombre} fue derrotado!`);
            this._deshabilitarBotones(true);
            setTimeout(() => this.onFinRonda(true), 1500);
            return true;
        }
        if (this.jugador.vida <= 0) {
            this._log(`💀 ¡${this.jugador.nombre} fue derrotado!`);
            this._deshabilitarBotones(true);
            setTimeout(() => this.onFinRonda(false), 1500);
            return true;
        }
        return false;
    }

    _actualizarBarras() {
        const jVidaPct  = (this.jugador.vida  / this.jugador.vidaMax)  * 100;
        const jManaPct  = (this.jugador.mana  / this.jugador.manaMax)  * 100;
        const eVidaPct  = (this.enemigo.vida  / this.enemigo.vidaMax)  * 100;
        const eManaPct  = (this.enemigo.mana  / this.enemigo.manaMax)  * 100;

        document.getElementById('jugador-vida-barra').style.width = jVidaPct + '%';
        document.getElementById('jugador-mana-barra').style.width = jManaPct + '%';
        document.getElementById('enemigo-vida-barra').style.width = eVidaPct + '%';
        document.getElementById('enemigo-mana-barra').style.width = eManaPct + '%';
    }

    _deshabilitarBotones(deshabilitar) {
        ['btn-habilidad-normal','btn-habilidad-especial','btn-pocion'].forEach(id => {
            document.getElementById(id).disabled = deshabilitar;
        });
    }

    _log(mensaje) {
        this.log.push(mensaje);
        const lista = document.getElementById('log-lista');
        lista.innerHTML += `<li>${mensaje}</li>`;
        lista.scrollTop = lista.scrollHeight;
    }
}