<?php
declare(strict_types=1);

namespace Root\Program\Mod;
abstract class Mundo
{
    protected string $nombre;

    public function __construct
    (string $nombre) 
    {
        $this->nombre = $nombre;
    }

    public function run()
    {
    }

}
