<?php

class Book
{
    public $id;
    public $titulo;
    public $autor;
    public $anio_publicacion;
    public $genero;

    public function __construct($id, $titulo, $autor, $anio_publicacion, $genero)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->anio_publicacion = $anio_publicacion;
        $this->genero = $genero;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'autor' => $this->autor,
            'anio_publicacion' => $this->anio_publicacion,
            'genero' => $this->genero,
        ];
    }
}
