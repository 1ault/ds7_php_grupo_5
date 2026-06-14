document.addEventListener('DOMContentLoaded', function () {

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Toast ────────────────────────────────────────────────────────────────
    function mostrarToast(mensaje, exito) {
        var toast = document.getElementById('toast');
        toast.textContent = mensaje;
        toast.style.display = 'block';
        toast.style.position = 'fixed';
        toast.style.bottom = '2rem';
        toast.style.right = '2rem';
        toast.style.padding = '0.75rem 1.25rem';
        toast.style.borderRadius = '0.5rem';
        toast.style.fontWeight = 'bold';
        toast.style.fontSize = '0.95rem';
        toast.style.zIndex = '999';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
        toast.style.color = '#fff';
        toast.style.backgroundColor = exito ? '#28a745' : '#dc3545';
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () {
            toast.style.display = 'none';
        }, 3000);
    }

    // ── Actualizar estado via fetch ──────────────────────────────────────────
    function actualizarEstado(boton) {
        var uid    = boton.dataset.uid;
        var fila   = boton.closest('tr');
        var select = fila.querySelector('[data-select-estado]');
        var estado = select.value;
        var badge  = fila.querySelector('[data-estado-badge]');

        boton.disabled = true;
        boton.textContent = '...';

        fetch('/api/admin/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'usuario_id=' + uid +
                  '&estado_solicitud=' + encodeURIComponent(estado) +
                  '&csrf_token=' + encodeURIComponent(csrfToken)
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.ok) {
                // Actualizar badge
                var clases = {
                    'considerado':    'badge-considerado',
                    'no considerado': 'badge-no-considerado',
                    'no revisado':    'badge-no-revisado'
                };
                badge.className   = 'badge ' + (clases[estado] || 'badge-no-revisado');
                badge.textContent = estado;
                // Actualizar data-estado-badge para filtros
                badge.setAttribute('data-estado-badge', estado);
                mostrarToast('Estado actualizado correctamente.', true);
            } else {
                mostrarToast('Error al actualizar el estado.', false);
            }
        })
        .catch(function () {
            mostrarToast('Error de conexión.', false);
        })
        .finally(function () {
            boton.disabled = false;
            boton.textContent = 'Guardar';
        });
    }

    // ── Delegación de eventos en la tabla ───────────────────────────────────
    var tabla = document.getElementById('tabla-aspirantes');
    if (tabla) {
        tabla.addEventListener('click', function (e) {
            var boton = e.target.closest('.btn-guardar-estado');
            if (boton) actualizarEstado(boton);
        });
    }

    // ── Filtro de búsqueda y estado ──────────────────────────────────────────
    var filtroBuscar = document.getElementById('filtro-buscar');
    var filtroEstado = document.getElementById('filtro-estado');

    function filtrarTabla() {
        var textoBuscar = filtroBuscar.value.toLowerCase().trim();
        var estadoFiltro = filtroEstado.value.toLowerCase();
        var filas = tabla.querySelectorAll('tbody tr');

        filas.forEach(function (fila) {
            var textoFila  = fila.textContent.toLowerCase();
            var badgeTexto = (fila.querySelector('[data-estado-badge]') || {}).textContent || '';
            badgeTexto = badgeTexto.trim().toLowerCase();

            var coincideTexto  = textoBuscar === '' || textoFila.includes(textoBuscar);
            var coincideEstado = estadoFiltro === '' || badgeTexto === estadoFiltro;

            fila.style.display = (coincideTexto && coincideEstado) ? '' : 'none';
        });
    }

    if (filtroBuscar) filtroBuscar.addEventListener('input', filtrarTabla);
    if (filtroEstado) filtroEstado.addEventListener('change', filtrarTabla);

});
