<?php
declare(strict_types=1);

namespace Root\Program;

use Root\Program\Mod\Fondo;
use Root\Program\Mod\Personaje\Usuario\Cangrejo;
use Root\Program\Mod\Personaje\Enemigo\Hongo as EnemigoHongo;
use Root\Program\Mod\Personaje\Usuario\Hongo as UsuarioHongo;
use Root\Program\Mod\Data;
use Root\ProgramaMod\Personaje\Usuario\Kangre as UsuarioKangre;

enum HttpStatus: int {
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case NOT_FOUND = 404;
    case INTERNAL_SERVER_ERROR = 500;
}

class Game
{

    static public function response($data, $status = 200) 
    {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }



    static public function signalAtaqueGameState()
    {
        

        $file = __DIR__ . "/../storage/game_state.json";

        $json = file_get_contents($file);

        if ($json === false) {
            Game::response(["error" => "not load storage"], HttpStatus::NOT_FOUND);
            exit;
        }

        $data = json_decode($json, true);

        if (!is_array($data)) {
            Game::response(["error" => "not load storage"], HttpStatus::NOT_FOUND);
            exit;
        }


        $personaje = $data['assets']['personaje']['kangre'];

        $data['assets']['enemigo']['champi']['vida'] -=
            $personaje['habilidades'][0]["dano_base"];

        $enemigo_vida = $data['assets']['enemigo']['champi']['vida'];
        // $personajeClass = new UsuarioKangre();
        //$personajeClass->setVida($personaje['vida']);
        //$personajeClass->setMana($personaje['mana']);
        
        $data['gui'][0]['text'] = sprintf("[Usuario]: Ataco\n[Enemigo]: -5 vida\n[Enemigo][vida] = %d", $enemigo_vida);
        // $data['gui'][0]['text'] = sprintf("[Usuario]: Ataco\n[Enemigo]: -5 vida\n[Enemigo][vida] = ");
        //$data['gui'][0]['text'] = sprintf("[Usuario]");
         





        file_put_contents(
            __DIR__ . "/../storage/game_state.json",
            json_encode($data, JSON_PRETTY_PRINT)
        );

        http_response_code(HttpStatus::OK->value);
        echo json_encode($data);
        exit;
    }

    static public function loadGameState()
    {
        http_response_code(HttpStatus::OK->value);
        $json = file_get_contents( 
            __DIR__ . "/../storage/game_state.json",
        );
        echo $json;
        exit;
    }

    static public function saveGameState($game_state)
    {
        http_response_code(HttpStatus::OK->value);

        echo json_encode($game_state);

        file_put_contents(
            __DIR__ . "/../storage/game_state.json",
            json_encode($game_state, JSON_PRETTY_PRINT)
        );
        exit;
    }

    static public function getFondoMain(): Fondo
    {
        http_response_code(HttpStatus::OK->value);
        $data = new Fondo('main', '/assets/img/fondo.webp');
        echo json_encode($data);
        exit; 
    }

    static public function getPersonajeKangre(): Personaje
    {    
        http_response_code(HttpStatus::OK->value);
        $data = new Cangrejo();
        echo json_encode($data);
        exit; 
    }

    static public function getPersonajeChampi(): Personaje
    {    
        http_response_code(HttpStatus::OK->value);
        $data = new UsuarioHongo();
        echo json_encode($data);
        exit; 
    }

    static public function getEnemigoChampi(): Personaje
    {
        http_response_code(HttpStatus::OK->value);
        $data = new EnemigoHongo();
        echo json_encode($data);
        exit; 
    }

}
