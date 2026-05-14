<?php

    echo "Practica #1";
    printf('<br>');
    echo "Integrantes:";
    printf('<br>');
    echo "Jonathan Quinto - 8-1007-1971";
    printf('<br>');
    echo "Alexander Castroverde - 8-1017-805";
    printf('<br>');
    echo "Abdias Ruedas - 8-1011-2210";
    printf('<br>');
    echo "Aqui colocan su nombre y numero de carne si necesitan otro espacio simplemente añaden otro echo y printf para el salto de linea";
    printf('<br>');
    printf('<br>');
    
    printf("Problema #1");
    printf('<br>');
$celsius = random_int(20, 80); 

$fahrenheit = ($celsius * 9/5) + 32;

if ($fahrenheit < 50) {
    $clasificacion = "Frío";
} elseif ($fahrenheit <= 86) {
    $clasificacion = "Templado";
} else {
    $clasificacion = "Caliente";
}
echo "Temperatura tecleada: " . $celsius . "°C <br>";
echo "Temperatura conversion: " . $fahrenheit . "°F <br>";
echo "Clasificación: " . $clasificacion;


printf('<br>');
printf('<br>');

printf("Problema #2");
printf('<br>');

$peso = random_int(20, 300); 
$altura = random_int(150, 200); 

$altura_m = $altura / 100;

$imc = $peso / ($altura_m * $altura_m);

if ($imc < 18.5) {
    $clasificacion = "Bajo peso";
} elseif ($imc < 25) {
    $clasificacion = "Peso normal";
} elseif ($imc < 30) {
    $clasificacion = "Sobrepeso";
} else {
    $clasificacion = "Obesidad";
}

echo "PESO INGRESADO: " . $peso . " kg <br>";
echo "ALTURA INGRESADA: " . $altura . " cm <br>";
echo "IMC: " . round($imc, 2) . "<br>";
echo "Clasificación: " . $clasificacion;


printf('<br>');
printf('<br>');

printf("Problema #3");
printf('<br>');


$primerNUM = random_int(1, 100);
$segundoNUM = random_int(1, 100);
$operador = "-";

switch ($operador ) {
    case "+":
        $resultado = $primerNUM + $segundoNUM;
        break;
    case "-":
        $resultado = $primerNUM - $segundoNUM;
        break;
    case "*":
        $resultado = $primerNUM * $segundoNUM;
        break;
    case "/":
        if ($segundoNUM != 0) {
            $resultado = $primerNUM / $segundoNUM;
        } else {
            $resultado = "Error: División por cero";
        }
        break;
    default:
        $resultado = "Operador no válido";
}

echo "Primer número: " . $primerNUM . "<br>";
echo "Segundo número: " . $segundoNUM . "<br>";
echo "Operador: " . $operador . "<br>";
echo "Resultado: " . $primerNUM . " " . $operador . " " . $segundoNUM . " = " . $resultado;
?>


