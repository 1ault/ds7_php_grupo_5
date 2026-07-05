<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Layer8;
use PDO;

class Pelicula
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Layer8::get();
    }

    /** Todas las películas con sus géneros concatenados */
    public function obtenerTodas(): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT p.id, p.titulo, p.descripcion, p.tipo, p.anio, p.poster_url,
                    GROUP_CONCAT(g.nombre ORDER BY g.nombre SEPARATOR ", ") AS generos,
                    GROUP_CONCAT(g.id    ORDER BY g.nombre SEPARATOR ",")   AS genero_ids
             FROM peliculas p
             LEFT JOIN pelicula_genero pg ON pg.pelicula_id = p.id
             LEFT JOIN generos g ON g.id = pg.genero_id
             GROUP BY p.id
             ORDER BY p.titulo ASC'
        );
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Una película con sus géneros */
    public function obtenerPorId(int $id): ?array
    {
        $consulta = $this->conexion->prepare(
            'SELECT p.id, p.titulo, p.descripcion, p.tipo, p.anio, p.poster_url,
                    GROUP_CONCAT(g.nombre ORDER BY g.nombre SEPARATOR ", ") AS generos,
                    GROUP_CONCAT(g.id    ORDER BY g.nombre SEPARATOR ",")   AS genero_ids
             FROM peliculas p
             LEFT JOIN pelicula_genero pg ON pg.pelicula_id = p.id
             LEFT JOIN generos g ON g.id = pg.genero_id
             WHERE p.id = :id
             GROUP BY p.id
             LIMIT 1'
        );
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        $row = $consulta->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Recomendaciones: películas cuyos géneros coincidan con las preferencias
     * del usuario. Excluye lo que ya vio.
     */
    public function obtenerRecomendaciones(int $usuario_id): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT p.id, p.titulo, p.descripcion, p.tipo, p.anio, p.poster_url,
                    GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR ", ") AS generos,
                    COUNT(DISTINCT pg2.genero_id) AS coincidencias
             FROM peliculas p
             JOIN pelicula_genero pg  ON pg.pelicula_id = p.id
             JOIN preferencias    pr  ON pr.genero_id   = pg.genero_id
                                     AND pr.usuario_id  = :uid
             LEFT JOIN pelicula_genero pg2 ON pg2.pelicula_id = p.id
             LEFT JOIN generos g ON g.id = pg2.genero_id
             WHERE p.id NOT IN (
                 SELECT pelicula_id FROM historial WHERE usuario_id = :uid2
             )
             GROUP BY p.id
             ORDER BY coincidencias DESC, p.titulo ASC
             LIMIT 12'
        );
        $consulta->bindValue(':uid',  $usuario_id, PDO::PARAM_INT);
        $consulta->bindValue(':uid2', $usuario_id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Películas del historial del usuario (las últimas vistas) */
    public function obtenerHistorial(int $usuario_id, int $limite = 6): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT p.id, p.titulo, p.tipo, p.anio, p.poster_url,
                    MAX(h.visto_at) AS visto_at,
                    GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR ", ") AS generos
             FROM historial h
             JOIN peliculas p ON p.id = h.pelicula_id
             LEFT JOIN pelicula_genero pg ON pg.pelicula_id = p.id
             LEFT JOIN generos g ON g.id = pg.genero_id
             WHERE h.usuario_id = :uid
             GROUP BY p.id
             ORDER BY visto_at DESC
             LIMIT :lim'
        );
        $consulta->bindValue(':uid', $usuario_id, PDO::PARAM_INT);
        $consulta->bindValue(':lim', $limite,      PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Registrar que un usuario vio una película */
    public function registrarVista(int $usuario_id, int $pelicula_id): void
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO historial (usuario_id, pelicula_id) VALUES (:uid, :pid)'
        );
        $consulta->bindValue(':uid', $usuario_id,  PDO::PARAM_INT);
        $consulta->bindValue(':pid', $pelicula_id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Insertar nueva película y sus géneros */
    public function insertar(array $datos, array $genero_ids): int
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO peliculas (titulo, descripcion, tipo, anio, poster_url)
             VALUES (:titulo, :descripcion, :tipo, :anio, :poster_url)'
        );
        $consulta->bindValue(':titulo',      $datos['titulo']);
        $consulta->bindValue(':descripcion', $datos['descripcion']);
        $consulta->bindValue(':tipo',        $datos['tipo']);
        $consulta->bindValue(':anio',        $datos['anio'],       PDO::PARAM_INT);
        $consulta->bindValue(':poster_url',  $datos['poster_url']);
        $consulta->execute();
        $id = (int) $this->conexion->lastInsertId();
        $this->sincronizarGeneros($id, $genero_ids);
        return $id;
    }

    /** Actualizar película existente */
    public function actualizar(int $id, array $datos, array $genero_ids): bool
    {
        $consulta = $this->conexion->prepare(
            'UPDATE peliculas
             SET titulo=:titulo, descripcion=:descripcion, tipo=:tipo,
                 anio=:anio, poster_url=:poster_url
             WHERE id=:id'
        );
        $consulta->bindValue(':titulo',      $datos['titulo']);
        $consulta->bindValue(':descripcion', $datos['descripcion']);
        $consulta->bindValue(':tipo',        $datos['tipo']);
        $consulta->bindValue(':anio',        $datos['anio'],       PDO::PARAM_INT);
        $consulta->bindValue(':poster_url',  $datos['poster_url']);
        $consulta->bindValue(':id',          $id,                  PDO::PARAM_INT);
        $consulta->execute();
        $this->sincronizarGeneros($id, $genero_ids);
        return true;
    }

    /** Eliminar película */
    public function eliminar(int $id): bool
    {
        $consulta = $this->conexion->prepare('DELETE FROM peliculas WHERE id=:id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount() > 0;
    }

    /** Guardar calificación (INSERT … ON DUPLICATE KEY UPDATE) */
    public function calificar(int $usuario_id, int $pelicula_id, int $puntuacion): void
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO calificaciones (usuario_id, pelicula_id, puntuacion)
             VALUES (:uid, :pid, :pts)
             ON DUPLICATE KEY UPDATE puntuacion = :pts2'
        );
        $consulta->bindValue(':uid',  $usuario_id,  PDO::PARAM_INT);
        $consulta->bindValue(':pid',  $pelicula_id, PDO::PARAM_INT);
        $consulta->bindValue(':pts',  $puntuacion,  PDO::PARAM_INT);
        $consulta->bindValue(':pts2', $puntuacion,  PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Calificación del usuario para una película */
    public function obtenerCalificacion(int $usuario_id, int $pelicula_id): int
    {
        $consulta = $this->conexion->prepare(
            'SELECT puntuacion FROM calificaciones
             WHERE usuario_id=:uid AND pelicula_id=:pid LIMIT 1'
        );
        $consulta->bindValue(':uid', $usuario_id,  PDO::PARAM_INT);
        $consulta->bindValue(':pid', $pelicula_id, PDO::PARAM_INT);
        $consulta->execute();
        $row = $consulta->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['puntuacion'] : 0;
    }

    /** Estadísticas para el panel admin: géneros más visitados */
    public function estadisticasGeneros(): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT g.nombre, COUNT(h.id) AS visitas
             FROM historial h
             JOIN peliculas p ON p.id = h.pelicula_id
             JOIN pelicula_genero pg ON pg.pelicula_id = p.id
             JOIN generos g ON g.id = pg.genero_id
             GROUP BY g.id
             ORDER BY visitas DESC
             LIMIT 10'
        );
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Sincroniza géneros de una película (borra y re-inserta) */
    private function sincronizarGeneros(int $pelicula_id, array $genero_ids): void
    {
        $del = $this->conexion->prepare(
            'DELETE FROM pelicula_genero WHERE pelicula_id=:pid'
        );
        $del->bindValue(':pid', $pelicula_id, PDO::PARAM_INT);
        $del->execute();

        $ins = $this->conexion->prepare(
            'INSERT IGNORE INTO pelicula_genero (pelicula_id, genero_id) VALUES (:pid, :gid)'
        );
        foreach ($genero_ids as $gid) {
            $ins->bindValue(':pid', $pelicula_id, PDO::PARAM_INT);
            $ins->bindValue(':gid', (int)$gid,    PDO::PARAM_INT);
            $ins->execute();
        }
    }
}
