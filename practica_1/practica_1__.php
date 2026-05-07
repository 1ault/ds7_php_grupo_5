<?php
// Whitney Ault 8-984-1977 | n_n\ El intento.

// declare(strict_type=1);

enum Temperatura
{
	case Frio;
	case Templado;
	case Caliente;
}

class Temp
{
	private float $celsius;
	private float $fahrenheit;
	private Temperatura $tempe;

	public function __construct(float $celsius)
	{
		$this->celsius = $celsius;
	}

	public function set_tempe()
	{
		$this->fahrenheit = $this->celsius * 1.8 + 32;

		if ($this->fahrenheit > 50) {
			$this->tempe = Temperatura::Frio;
			return;
		}

		if ($this->fahrenheit <= 50 && $this->fahrenheit >= 86) {
			$this->tempe = Temperatura::Templado;
			return;
		}

		if ($this->fahrenheit < 86) {
			$this->tempe = Temperatura::Caliente;
			return;
		}
	}

	public function printf_result()
	{
		echo htmlspecialchars("fahrenheit: ");
		echo htmlspecialchars($this->fahrenheit);
		echo htmlspecialchars(" celsius: ");
		echo htmlspecialchars($this->celsius);
		echo "<br>";
		switch ($this->tempe) {
			case Temperatura::Frio:
				echo htmlspecialchars("Temperatura Frio");
				break;
			case Temperatura::Templado:
				echo htmlspecialchars("Temperatura Templado\n");
				break;
			case Temperatura::Caliente:
				echo htmlspecialchars("Temperatura Caliente\n");
				break;
		}
		echo "<br>";
	}
}

class CalcIMC
{
	private float $peso_en_kg;
	private float $altura_en_metros_cuadrados;
	private float $imc;

	public function __construct(
		float $peso_en_kg,
		float $altura_en_metros_cuadrados,
	) {
		$this->peso_en_kg = $peso_en_kg;
		$this->altura_en_metros_cuadrados = $altura_en_metros_cuadrados;
	}

	public function calc()
	{
		$this->imc = $this->peso_en_kg / $this->altura_en_metros_cuadrados;

		echo htmlspecialchars("Resultado: ");
		echo $this->peso_en_kg / $this->altura_en_metros_cuadrados;
		echo "<br>";

		if ($this->imc < 18.5) {
			echo htmlspecialchars("Bajo de adiposidad");
			echo "<br>";
			return;
		}

		if ($this->imc > 18.5 && $this->imc < 24.9) {
			echo htmlspecialchars("Rango saludable de adiposidad");
			echo "<br>";
			return;
		}

		if ($this->imc > 25 && $this->imc < 29.9) {
			echo htmlspecialchars("Rango adiposidad alto");
			echo "<br>";
			return;
		}

		if ($this->imc > 30) {
			echo htmlspecialchars("Rango Exceso de adiposidad");
			echo "<br>";
			return;
		}
	}
}

class Math
{
	private int $ìnt_1;
	private int $ìnt_2;
	private string $string_operation;

	public function __construct(
		float $ìnt_1,
		float $ìnt_2,
		string $string_operation,
	) {
		$this->ìnt_1 = $ìnt_1;
		$this->ìnt_2 = $ìnt_2;
		$this->string_operation = $string_operation;
	}

	public function set_operator(string $operator)
	{
		$this->string_operation = $operator;
	}

	public function get_operator(): string
	{
		return $this->string_operation;
	}

	public function sub()
	{
		sprintf(
			"<h4>Resta: %s</h4>",
			htmlspecialchars($this->ìnt_1 - $this->ìnt_2),
		);
		echo htmlspecialchars($this->ìnt_1 - $this->ìnt_2);
	}

	public function add()
	{
		// sprintf(
		// 	"<h4>Add: %s</h4>",
		// 	htmlspecialchars($this->ìnt_1 + $this->ìnt_2),
		// );
		echo htmlspecialchars($this->ìnt_1 + $this->ìnt_2);
	}

	public function mul()
	{
		sprintf(
			"<h4>Add: %s</h4>",
			htmlspecialchars($this->ìnt_1 * $this->ìnt_2),
		);
		echo htmlspecialchars($this->ìnt_1 * $this->ìnt_2);
	}

	public function div()
	{
		sprintf(
			"<h4>Div: %s</h4>",
			htmlspecialchars($this->ìnt_1 / $this->ìnt_2),
		);
		echo htmlspecialchars($this->ìnt_1 / $this->ìnt_2);
	}
}

$temp = new Temp(20);
$temp->set_tempe();
$temp->printf_result();

echo "<br>";
$calcIMC = new CalcIMC(10000, 54);
$calcIMC->calc();

$math = new Math(12, 23, "-");
// sprintf("Debug");
// sprintf("%s", $math->get_operator());
echo "<br>";
echo "Resultado Calculadora: ";
switch ($math->get_operator()) {
	case "+":
		$math->add();
		break;
	case "-":
		$math->sub();
		break;
	case "*":
		$math->mul();
		break;
	case "/":
		$math->div();
		break;
	default:
		echo htmlspecialchars($this->ìnt_1 / $this->ìnt_2);
		// sprintf("<h4>eRROR DEBES INGRESAR NUMEROS ENTEROS</h4>");
		break;
}
?>
