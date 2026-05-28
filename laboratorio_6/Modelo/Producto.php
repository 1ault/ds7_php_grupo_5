<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use FilesystemIterator;
use JsonSerializable;

class Producto implements JsonSerializable
{
    public function __construct
    (        
        private readonly int $id,
        private readonly string $nombre,
        private readonly string $marca,
        private readonly float $precio,
        private readonly int $stock,
        private readonly string $tipo_de_producto
    )
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'marca' => $this->marca,
            'precio' => $this->precio,
            'stock' => $this->stock, 
            'tipo_de_producto' => $this->tipo_de_producto
        ];
    }

    public static function getPathCouterFiles(): int
    {    
        $file_system_iterator = new FilesystemIterator
            ( 
                PATH_DATA, 
                FilesystemIterator::SKIP_DOTS
            );
        // printf("There were %d Files", iterator_count($fi));
        return iterator_count($file_system_iterator);
    }
    
    public static function saveFile(mixed $json): mixed
    {
        $path = PATH_DATA . Self::getPathCouterFiles() . ".json";

        file_put_contents
        (
            $path,
            $json
        );

        return
        $personaArray = json_decode($json, true);
    }

    public static function existsFile(int $id): bool
    {  
        $path = PATH_DATA . $id . ".json";

        if (file_exists($path))
        {
           return true;
        }

        return false;
    }


    public static function editFile(int $id, mixed $json): mixed
    {  
        $path = PATH_DATA . $id . ".json";
 
        $jsonString = file_get_contents
        (
            $path
        );


        $old_json = json_decode($jsonString, true);
        $new_json = json_decode($json, true);

        $new_json['id'] = $old_json['id'];

        $end_json = json_encode($new_json);

        file_put_contents
        (
            $path,
            $end_json
        );

        return $json;
    }

    public static function loadFile(int $id): mixed
    {  
        $path = PATH_DATA . $id . ".json";

        $jsonString = file_get_contents
        (
            $path
        );

        return json_decode($jsonString, true);
    }

}
