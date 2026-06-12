/**
 * home.js — CineMatch
 * DS7 Grupo 5
 * Maneja: tema claro/oscuro (cookie), filtros del catálogo
 */

// ── Tema claro / oscuro ──────────────────────────────────────────────────────

const htmlEl  = document.documentElement;
const btnTema = document.getElementById('btn-tema');

function getTema() {
    const cookie = document.cookie.split(';').find(c => c.trim().startsWith('cm_tema='));
    return cookie ? cookie.split('=')[1].trim() : 'light';
}

function setTema(tema) {
    htmlEl.setAttribute('data-theme', tema);
    document.cookie = 'cm_tema=' + tema + '; path=/; max-age=' + (30 * 86400);
    if (btnTema) btnTema.textContent = tema === 'dark' ? '☀️' : '🌙';
}

// Aplicar tema guardado al cargar
setTema(getTema());

if (btnTema) {
    btnTema.addEventListener('click', () => {
        const nuevo = getTema() === 'dark' ? 'light' : 'dark';
        setTema(nuevo);
    });
}

// ── Checkbox visual (perfil) ─────────────────────────────────────────────────

document.querySelectorAll('.checkbox-label').forEach(label => {
    const checkbox = label.querySelector('input[type="checkbox"]');
    if (checkbox) {
        checkbox.addEventListener('change', () => {
            label.classList.toggle('checked', checkbox.checked);
        });
    }
});

// ── Filtros del catálogo ─────────────────────────────────────────────────────

const btnFiltrar  = document.getElementById('btn-filtrar');
const gridCatalog = document.getElementById('grid-catalogo');
const sinResult   = document.getElementById('sin-resultados');

if (btnFiltrar && gridCatalog) {
    btnFiltrar.addEventListener('click', filtrar);

    // Filtrar también al presionar Enter
    ['filtro-busqueda', 'filtro-tipo', 'filtro-genero'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('keydown', e => { if (e.key === 'Enter') filtrar(); });
    });

    // Auto-filtrar en selects
    ['filtro-tipo', 'filtro-genero'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', filtrar);
    });

    function filtrar() {
        const busqueda = (document.getElementById('filtro-busqueda')?.value || '').toLowerCase().trim();
        const tipo     = (document.getElementById('filtro-tipo')?.value     || '').toLowerCase().trim();
        const genero   = (document.getElementById('filtro-genero')?.value   || '').toLowerCase().trim();

        const cards = gridCatalog.querySelectorAll('.card-pelicula');
        let visibles = 0;

        cards.forEach(card => {
            const titulo  = (card.dataset.titulo  || '').toLowerCase();
            const cardTipo= (card.dataset.tipo     || '').toLowerCase();
            const generos = (card.dataset.generos  || '').toLowerCase();

            const matchBusqueda = busqueda === '' || titulo.includes(busqueda);
            const matchTipo     = tipo     === '' || cardTipo === tipo;
            const matchGenero   = genero   === '' || generos.includes(genero.toLowerCase());

            const mostrar = matchBusqueda && matchTipo && matchGenero;
            card.style.display = mostrar ? '' : 'none';
            if (mostrar) visibles++;
        });

        if (sinResult) sinResult.style.display = visibles === 0 ? 'block' : 'none';
    }
}
