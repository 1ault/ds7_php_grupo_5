<?php
declare(strict_types=1);

namespace Root\Program\Mod;

class Data
{

    public function __construct
    () 
    {
    }

    
    static public function save($game_state)
    {
        file_put_contents(
            __DIR__ . "/../../../storage/game_state.json",
            json_encode($game_state, JSON_PRETTY_PRINT)
        );
    }

    public function load()
    {
    }
}
