<?php
// Desactivar caché de WSDL para evitar persistencias molestas
ini_set("soap.wsdl_cache_enabled", "0");

$resultado = null;
$error = null;
$num1 = '';
$num2 = '';
$opSeleccionada = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num1 = isset($_POST['numero1']) ? $_POST['numero1'] : '';
    $num2 = isset($_POST['numero2']) ? $_POST['numero2'] : '';
    $opSeleccionada = isset($_POST['operacion']) ? $_POST['operacion'] : '';

    if ($num1 === '' || $num2 === '' || !is_numeric($num1) || !is_numeric($num2)) {
        $error = "Por favor, introduce valores numéricos válidos en ambos campos.";
    } else {
        // Configurar el cliente SOAP usando el archivo WSDL
        $wsdl = "http://localhost/Laboratorio_7/servicioOperations.wsdl";

        try {
            $cliente = new SoapClient($wsdl);
            
            $a = floatval($num1);
            $b = floatval($num2);

            switch ($opSeleccionada) {
                case 'sumar':
                    $resultado = $cliente->sumar($a, $b);
                    break;
                case 'restar':
                    $resultado = $cliente->restar($a, $b);
                    break;
                case 'multiplicar':
                    $resultado = $cliente->multiplicar($a, $b);
                    break;
                case 'dividir':
                    $resultado = $cliente->dividir($a, $b);
                    break;
                default:
                    $error = "Operación no válida seleccionada.";
            }
        } catch (SoapFault $e) {
            // Capturar la excepción SOAP arrojada por el servidor (ej. división por cero)
            $error = "Error de SOAP: " . $e->getMessage();
        } catch (Exception $e) {
            $error = "Error general: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora SOAP - Cliente</title>
    <!-- Fuente moderna desde Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1 id="main-title">Matemáticas SOAP</h1>
                <p class="subtitle">Calculadora cliente consumiendo servicio SOAP PHP</p>
            </div>

            <form method="POST" action="cliente.php" id="calc-form">
                <div class="form-group">
                    <label for="numero1">Número A</label>
                    <div class="input-wrapper">
                        <input type="number" step="any" name="numero1" id="numero1" 
                               value="<?php echo htmlspecialchars($num1); ?>" 
                               class="input-control" placeholder="Ej. 10" required autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label for="numero2">Número B</label>
                    <div class="input-wrapper">
                        <input type="number" step="any" name="numero2" id="numero2" 
                               value="<?php echo htmlspecialchars($num2); ?>" 
                               class="input-control" placeholder="Ej. 5" required autocomplete="off">
                    </div>
                </div>

                <label>Selecciona la Operación</label>
                <div class="grid-operations">
                    <button type="submit" name="operacion" value="sumar" class="btn-op" id="btn-sumar">
                        +
                        <span>Sumar</span>
                    </button>
                    <button type="submit" name="operacion" value="restar" class="btn-op" id="btn-restar">
                        -
                        <span>Restar</span>
                    </button>
                    <button type="submit" name="operacion" value="multiplicar" class="btn-op" id="btn-multiplicar">
                        &times;
                        <span>Multiplicar</span>
                    </button>
                    <button type="submit" name="operacion" value="dividir" class="btn-op" id="btn-dividir">
                        &divide;
                        <span>Dividir</span>
                    </button>
                </div>
            </form>

            <?php if ($resultado !== null): ?>
                <div class="result-panel result-success" id="result-success-box">
                    <div class="result-title">Resultado de la <?php 
                        switch ($opSeleccionada) {
                            case 'sumar': echo 'Suma'; break;
                            case 'restar': echo 'Resta'; break;
                            case 'multiplicar': echo 'Multiplicación'; break;
                            case 'dividir': echo 'División'; break;
                        }
                    ?></div>
                    <div class="result-value"><?php echo htmlspecialchars($num1) . ' ' . 
                        ($opSeleccionada == 'sumar' ? '+' : ($opSeleccionada == 'restar' ? '-' : ($opSeleccionada == 'multiplicar' ? '*' : '/'))) . 
                        ' ' . htmlspecialchars($num2) . ' = ' . htmlspecialchars($resultado); ?></div>
                </div>
            <?php endif; ?>

            <?php if ($error !== null): ?>
                <div class="result-panel result-error" id="result-error-box">
                    <div class="result-title">Error Detectado</div>
                    <div class="result-value"><?php echo htmlspecialchars($error); ?></div>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>Servidor SOAP en: <a href="servicioOperations.wsdl" target="_blank">servicioOperations.wsdl</a></p>
        </div>
    </div>
</body>
</html>
