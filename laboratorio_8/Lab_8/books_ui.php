<?php
// Interfaz de usuario para gestionar libros (archivo PHP)
// Contenido HTML + JS para listar y crear libros via API
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Gestor de Libros - UI</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; background:#f7f7f7 }
    .container{ max-width:800px; margin:0 auto; background:white; padding:16px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,.08) }
    label{ display:block; margin-top:8px }
    input, select { width:100%; padding:8px; margin-top:4px }
    button{ margin-top:12px; padding:10px 14px }
    pre{ background:#222; color:#fff; padding:12px; border-radius:6px; overflow:auto }
  </style>
</head>
<body>
  <div class="container">
    <h1>Gestión de Libros</h1>

    <form id="bookForm">
      <label>Título del Libro <input name="titulo" required /></label>
      <label>Autor del Libro <input name="autor" required /></label>
      <label>Año de publicación <input name="anio_publicacion" type="number" required /></label>
      <label>Género <input name="genero" required /></label>
      <button type="submit">Agregar libro</button>
    </form>

    <hr />

    <div>
      <button id="refresh">Refrescar lista</button>
      <div id="status" style="margin-top:8px"></div>
      <h3>Libros</h3>
      <table id="booksTable" style="width:100%; border-collapse: collapse; margin-top:12px">
        <thead>
          <tr>
            <th style="text-align:left; border-bottom:1px solid #ccc; padding:8px">ID</th>
            <th style="text-align:left; border-bottom:1px solid #ccc; padding:8px">Título</th>
            <th style="text-align:left; border-bottom:1px solid #ccc; padding:8px">Autor</th>
            <th style="text-align:left; border-bottom:1px solid #ccc; padding:8px">Año</th>
            <th style="text-align:left; border-bottom:1px solid #ccc; padding:8px">Género</th>
            <th style="text-align:left; border-bottom:1px solid #ccc; padding:8px">Acciones</th>
          </tr>
        </thead>
        <tbody id="booksBody">
          <tr><td colspan="6" style="padding:8px">Cargando...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const api = 'get_libros.php';
    const form = document.getElementById('bookForm');
    const booksBody = document.getElementById('booksBody');
    const status = document.getElementById('status');
    const refreshBtn = document.getElementById('refresh');

    let editingId = null;

    async function fetchBooks(){
      status.textContent = '';
      try{
        const res = await fetch(api);
        const data = await res.json();
        renderBooks(data);
      }catch(e){
        booksBody.innerHTML = `<tr><td colspan="6" style="padding:8px">Error: ${e.message}</td></tr>`;
      }
    }

    function renderBooks(books){
      if (!Array.isArray(books) || books.length === 0) {
        booksBody.innerHTML = '<tr><td colspan="6" style="padding:8px">No hay libros.</td></tr>';
        return;
      }

      booksBody.innerHTML = books.map(book => `
        <tr>
          <td style="padding:8px; border-bottom:1px solid #eee">${book.id}</td>
          <td style="padding:8px; border-bottom:1px solid #eee">${book.titulo}</td>
          <td style="padding:8px; border-bottom:1px solid #eee">${book.autor}</td>
          <td style="padding:8px; border-bottom:1px solid #eee">${book.anio_publicacion}</td>
          <td style="padding:8px; border-bottom:1px solid #eee">${book.genero}</td>
          <td style="padding:8px; border-bottom:1px solid #eee">
            <button type="button" data-action="edit" data-id="${book.id}">Editar</button>
            <button type="button" data-action="delete" data-id="${book.id}">Eliminar</button>
          </td>
        </tr>`).join('');
    }

    function fillForm(book){
      form.titulo.value = book.titulo;
      form.autor.value = book.autor;
      form.anio_publicacion.value = book.anio_publicacion;
      form.genero.value = book.genero;
      editingId = book.id;
      status.textContent = `Editando libro #${book.id}`;
      form.querySelector('button[type="submit"]').textContent = 'Guardar cambios';
    }

    async function sendRequest(method, id, body){
      const url = id ? `${api}?id=${encodeURIComponent(id)}` : api;
      const options = { method, headers: {'Content-Type':'application/json'} };
      if (body) options.body = JSON.stringify(body);
      const res = await fetch(url, options);
      const data = await res.json();
      return { res, data };
    }

    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const fd = new FormData(form);
      const body = {
        titulo: fd.get('titulo'),
        autor: fd.get('autor'),
        anio_publicacion: Number(fd.get('anio_publicacion')),
        genero: fd.get('genero')
      };

      try{
        status.textContent = editingId ? 'Actualizando...' : 'Enviando...';
        const method = editingId ? 'PUT' : 'POST';
        const { res, data } = await sendRequest(method, editingId, body);

        if (!res.ok) {
          status.textContent = 'Error: ' + (data.error || JSON.stringify(data));
          return;
        }

        status.textContent = editingId ? 'Libro actualizado.' : 'Libro agregado correctamente.';
        form.reset();
        editingId = null;
        form.querySelector('button[type="submit"]').textContent = 'Agregar libro';
        fetchBooks();
      }catch(err){
        status.textContent = 'Error: '+err.message;
      }
    });

    booksBody.addEventListener('click', async (e)=>{
      const button = e.target.closest('button');
      if (!button) return;
      const action = button.dataset.action;
      const id = button.dataset.id;
      if (action === 'edit') {
        const res = await fetch(`${api}?id=${encodeURIComponent(id)}`);
        const book = await res.json();
        if (!res.ok) {
          status.textContent = 'Error: ' + (book.error || JSON.stringify(book));
          return;
        }
        fillForm(book);
      }
      if (action === 'delete') {
        if (!confirm('¿Eliminar este libro?')) return;
        const { res, data } = await sendRequest('DELETE', id);
        if (!res.ok) {
          status.textContent = 'Error: ' + (data.error || JSON.stringify(data));
          return;
        }
        status.textContent = 'Libro eliminado.';
        fetchBooks();
      }
    });

    refreshBtn.addEventListener('click', fetchBooks);

    fetchBooks();
  </script>
</body>
</html>
