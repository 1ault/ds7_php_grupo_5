
ls /usr/local/www/web1/Data/
cat /usr/local/www/web1/Data/0.json

$persona = [
    "nombre" => "Maria",
    "edad" => 25,
    "ciudad" => "Madrid",
    "hobbies" => ["musica", "cine", "deporte"]
];

$jsonString = json_encode($persona);

file_put_contents("persona.json", $jsonString);


$jsonString = file_get_contents("persona.json");

$personaObj = json_decode($jsonString);
$personaArray = json_decode($jsonString, true);

echo $personaObj->nombre;
echo "</br>";
echo $personaArray["hobbies"][0];

