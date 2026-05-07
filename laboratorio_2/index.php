    <?php
    include "empleado.php";
    include "colaborador.php";

    //colaboradores tiempo completo
$colaboradorTC = new Colaborador("Karla russo", 1000, 01);

//, colaboradores por comisión (salario = salario base + comisión
$colaboradorC = new Colaborador("Alexander ariza", 200, 02);

 //colaboradores por hora (salario = tarifa * hora).
$colaboradorH = new Colaborador("Maria fernanda", 4.75, 03);


/* colaboradorTC->$nombre = 
    colaboradorTC->$salarioBase = 
    colaboradorTC->$identificacion =


    colaboradorC->$nombre =
    colaboradorC->$salarioBase=
    colaboradorC->$identificacion = 

    colaboradorH->$nombre =
    colaboradorH->salarioBase =
    colaboradorH->identificacion = */

    
    /*Desarrollar un sistema en PHP que modele diferentes tipos de empleados y calcule su salario, aplicando: 
    Clases y objetos 
    Atributos públicos, privados y protegidos 
    Constructores 
    Herencia 
    Abstracción 
    Polimorfismo 
    No se permite input del usuario. 
    Los datos deben estar definidos en el código. 
    Estructura:  
    Clase abstracta “Colaborador”, con atributos para nombre, el salario base, y su identificación. 
    Constructor para inicializar los atributos. 
    Métodos Abstractos 
    Clases hijas para los colaboradores tiempo completo, colaboradores por comisión (salario = salario base + comisión), colaboradores por hora (salario = tarifa * hora). 
    Aplique polimorfismo para calcular los salarios de los colaboradores.
    */
$empleadoTC = new Procesos();


echo "Salario Karla: " . $col1->calcularSalario() . "<br>";
echo "Salario Alexander: " . $col2->calcularSalario() . "<br>";
echo "Salario Maria: " . $col3->calcularSalario() . "<br>";
?>