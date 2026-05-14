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

    static public function signalGameStateHabilidad()
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
        $personaje_vida = $personaje['vida'];
        $personaje_mana = $personaje['mana'];
        $personaje_habilidad_dano = $personaje['habilidades'][1]["dano_base"];
        $personaje_habilidad_nombre = $personaje['habilidades'][1]["nombre"];
        $personaje_habilidad_coste = $personaje['habilidades'][1]['coste'];

        $text = "";
        

        if ($personaje_vida < 0)
        { 
            $text .= sprintf("- (info): Kangre esta muerto. Vida restante: %d\n", $personaje_vida);
            $data['gui'][0]['text'] = $text;
            self::response
            (
                $data,
                HttpStatus::OK->value
            );
        }

        if (($personaje_mana - $personaje_habilidad_coste) < 0)
        { 
            $text .= sprintf("- (info): No tienes suficiente mana. Mana restante: %d", $personaje_mana); 
            $data['gui'][0]['text'] = $text;
            self::response
            (
                $data,
                HttpStatus::OK->value
            );
        }
        $data['assets']['personaje']['kangre']['mana'] -= $personaje_habilidad_coste;

        $text .= sprintf("- (Kangre): Uso %s\n", $personaje_habilidad_nombre);

        if (rand(0, 1) === 1) {
            $personaje_habilidad_dano *= 2;
            $text .= sprintf("- (Kangre): Acerto daño critico: %d\n", $personaje_habilidad_dano);
        } else {
            $text .= sprintf("- (Kangre): Acerto daño normal: %d\n", $personaje_habilidad_dano);
        }

        $enemigo_vida = $data['assets']['enemigo']['champi']['vida'] -= $personaje_habilidad_dano;

        $text .= sprintf("- (info): Kangre si tiene mana. Mana restante: %d\n", $personaje_mana);
        $text .= sprintf("- (info): Kangre no esta muerto. Vida restante: %d\n", $personaje_vida);

        $text .= sprintf("- (Kangre): Tiene %d de vida\n", $personaje_vida);
        $text .= sprintf("- (Enemigo): Recibio %s de daño. Vida restante: %d\n", $personaje_habilidad_dano, $enemigo_vida);

        if ($enemigo_vida <= 0)
        { 
            $text .= sprintf("- (info): Enemigo ha sido derrotado\n");
        }
        if ($enemigo_vida <= -10)
        { 
            $text .= sprintf("- (info): Enemigo ha sido derrotado dejalo descansar en paz\n");
        }

        $data['gui'][0]['text'] = $text;
         
        file_put_contents(
            __DIR__ . "/../storage/game_state.json",
            json_encode($data, JSON_PRETTY_PRINT)
        );

        self::response
        (
            $data,
            HttpStatus::OK->value
        ); 
    }

    static public function signalGameStateAtacar()
    {
        
        $text = "";
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
        $personaje_vida = $personaje['vida'];
        $personaje_habilidad_dano = $personaje['habilidades'][0]["dano_base"];
        $personaje_habilidad_nombre = $personaje['habilidades'][0]["nombre"];
        
        $text = "";

        if ($personaje_vida < 0)
        { 
            $text .= sprintf("- (info): Kangre esta muerto. Vida restante: %d\n", $personaje_vida);
            $data['gui'][0]['text'] = $text;
            self::response
            (
                $data,
                HttpStatus::OK->value
            );
        }

        $text .= sprintf("- (Kangre): Uso %s\n", $personaje_habilidad_nombre);

        if (rand(0, 1) === 1) {
            $personaje_habilidad_dano *= 2;
            $text .= sprintf("- (Kangre): Acerto daño critico: %d\n", $personaje_habilidad_dano);
        } else {
            $text .= sprintf("- (Kangre): Acerto daño normal: %d\n", $personaje_habilidad_dano);
        }

        $enemigo_vida = $data['assets']['enemigo']['champi']['vida'] -= $personaje_habilidad_dano;

        $text .= sprintf("- (info): Kangre no esta muerto. Vida restante: %d\n", $personaje_vida);

        $text .= sprintf("- (Kangre): Tiene %d de vida\n", $personaje_vida);
        $text .= sprintf("- (Enemigo): Recibio %s de daño. Vida restante: %d\n", $personaje_habilidad_dano, $enemigo_vida);

        if ($enemigo_vida <= 0)
        { 
            $text .= sprintf("- (info): Enemigo ha sido derrotado\n");
        }
        if ($enemigo_vida <= -10)
        { 
            $text .= sprintf("- (info): Enemigo ha sido derrotado dejalo descansar en paz\n");
        }


        $data['gui'][0]['text'] = $text;
         
        file_put_contents(
            __DIR__ . "/../storage/game_state.json",
            json_encode($data, JSON_PRETTY_PRINT)
        );

        self::response
        (
            $data,
            HttpStatus::OK->value
        );
    }

    static public function loadGameState()
    {
        $path = __DIR__ . "/../storage/game_state.json";
        if (!is_readable($path)) {
            self::response(
                ['error' => 'file not found or not readable'],
                HttpStatus::NOT_FOUND->value
            );
        }

        $json = file_get_contents($path);
        if ($json === false) {
            self::response
            (
                ['error' => 'cannot read file'],
                HttpStatus::INTERNAL_SERVER_ERROR->value
            );
        }

        $data = json_decode($json, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            self::response
                (
                    ['error' => 'invalid json'], 
                    HttpStatus::INTERNAL_SERVER_ERROR->value
                ); 
        }

        self::response($data);
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
<<<<<<< HEAD
        $fondo = new Fondo('main', 'assets/img/fondo.webp');
        echo json_encode($fondo);
=======
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
>>>>>>> 324c2646896017040376d9eb086b34de89808e33
        exit; 
    }

}
