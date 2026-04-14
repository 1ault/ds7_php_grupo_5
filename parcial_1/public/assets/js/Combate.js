import { estadoJuego, irA } from "./index.js";

export class Combate {
    constructor({ onFinRonda }) {
        this.onFinRonda = onFinRonda;
    }

    async init(personajeElegido, itemsEquipados) {
        const res = await fetch('api/combate_iniciar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                personaje: personajeElegido.nombre,
                items: itemsEquipados
            })
        });

        const estado = await res.json();
        this.estado  = estado;

        this._renderUI();
        this._log('⚔️ ¡Combate iniciado!');
        this._actualizarBarras();
    }

    _renderUI() {
        const { jugador, enemigo } = this.estado;

        document.getElementById('jugador-img').src            = `assets/img/${jugador.nombre === 'Hongo' ? 'champi' : jugador.nombre === 'Cangrejo' ? 'kangre' : 'jojo'}.webp`;
        document.getElementById('jugador-nombre').textContent = jugador.nombre;
        document.getElementById('jugador-nivel').textContent  = estadoJuego.ronda;
        document.getElementById('enemigo-img').src            = `assets/img/${enemigo.nombre === 'Hongo' ? 'champi' : enemigo.nombre === 'Cangrejo' ? 'kangre' : 'jojo'}.webp`;
        document.getElementById('enemigo-nombre').textContent = enemigo.nombre;
        document.getElementById('enemigo-nivel').textContent  = estadoJuego.ronda;

        document.getElementById('btn-habilidad-normal').textContent  = `🗡️ ${jugador.habilidades[0]}`;
        document.getElementById('btn-habilidad-especial').textContent = `✨ ${jugador.habilidades[1]}`;

        document.getElementById('btn-habilidad-normal').onclick  = () => this._accion('normal');
        document.getElementById('btn-habilidad-especial').onclick = () => this._accion('especial');
        document.getElementById('btn-pocion').onclick             = () => this._accion('pocion');

        this._iniciarIdle();
    }

    async _accion(tipo) {
        this._deshabilitarBotones(true);

        const res = await fetch('api/combate_accion.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: tipo })
        });

        const resultado = await res.json();

        if (resultado.error) {
            this._log(`❌ ${resultado.error}`);
            this._deshabilitarBotones(false);
            return;
        }

        // Animaciones de ataque
        if (tipo !== 'pocion') {
            this._animarAtaque('jugador', 'enemigo');
        }

        // Animación del contraataque enemigo (llega después)
        setTimeout(() => {
            if (!resultado.fin) {
                this._animarAtaque('enemigo', 'jugador');
            }
        }, 700);

        // Actualizar estado y UI después de que terminen las animaciones
        setTimeout(() => {
            this.estado.jugador = resultado.jugador;
            this.estado.enemigo = resultado.enemigo;

            resultado.log.forEach(msg => this._log(msg));
            this._actualizarBarras();

            if (resultado.fin) {
                const perdedor = resultado.gano ? 'enemigo' : 'jugador';
                this._animarMuerte(perdedor);
                setTimeout(() => this.onFinRonda(resultado.gano), 1500);
                return;
            }

            this._deshabilitarBotones(false);
        }, 1100);
    }

    // ── Animaciones ──────────────────────────────────────────

    _iniciarIdle() {
        document.getElementById('jugador-img').classList.add('anim-idle');
        document.getElementById('enemigo-img').classList.add('anim-idle');
    }

    _animarAtaque(atacante, receptor) {
        const imgAtacante = document.getElementById(`${atacante}-img`);
        const imgReceptor = document.getElementById(`${receptor}-img`);

        imgAtacante.classList.remove('anim-idle');
        imgReceptor.classList.remove('anim-idle');

        imgAtacante.classList.add(`anim-atacar-${atacante}`);

        setTimeout(() => {
            imgReceptor.classList.add('anim-dano');
        }, 250);

        setTimeout(() => {
            imgAtacante.classList.remove(`anim-atacar-${atacante}`);
            imgReceptor.classList.remove('anim-dano');
            imgAtacante.classList.add('anim-idle');
            imgReceptor.classList.add('anim-idle');
        }, 600);
    }

    _animarMuerte(quien) {
        const img = document.getElementById(`${quien}-img`);
        img.classList.remove('anim-idle');
        img.classList.add('anim-morir');
    }

    // ── Helpers ───────────────────────────────────────────────

    _actualizarBarras() {
        const { jugador, enemigo } = this.estado;
        document.getElementById('jugador-vida-barra').style.width = (jugador.vida / jugador.vida_max * 100) + '%';
        document.getElementById('jugador-mana-barra').style.width = (jugador.mana / jugador.mana_max * 100) + '%';
        document.getElementById('enemigo-vida-barra').style.width = (enemigo.vida / enemigo.vida_max * 100) + '%';
        document.getElementById('enemigo-mana-barra').style.width = (enemigo.mana / enemigo.mana_max * 100) + '%';
    }

    _deshabilitarBotones(deshabilitar) {
        ['btn-habilidad-normal', 'btn-habilidad-especial', 'btn-pocion'].forEach(id => {
            document.getElementById(id).disabled = deshabilitar;
        });
    }

    _log(mensaje) {
        const lista = document.getElementById('log-lista');
        lista.innerHTML += `<li>${mensaje}</li>`;
        lista.scrollTop = lista.scrollHeight;
    }
}