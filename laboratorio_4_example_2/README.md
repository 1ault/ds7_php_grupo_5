/listar
/crear
/editar


(/) Formulario => Solicite al usuario su nombre; 
=> (Post)
Crear un segundo archivo => que reciba el nombre del usuario, lo guarde en una cookie; 
=> (Cookie.php)
Cookie => Valida solo 5 minutos;


(/home) Pagina de bienvenida => Verificar si la cookie existe:
    Si existe => Mostrar mensaje (Nombre del usuario);
    No Existe => Mostrar mensaje ("No se ha ingresado el nombre", Enlase de salir)

(/home) Pagina de bienvenida => Tener:
    Boton de salida (Borrar cookie);

Archivos:
    1: Formulario;
    2: Home; 
    3: Reciba la cookie y lo guarde (Valida 5 minutos);
