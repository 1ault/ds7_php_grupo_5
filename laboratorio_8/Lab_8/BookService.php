<?php
require_once __DIR__ . '/BookRepository.php';

class BookService
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function listBooks(): array
    {
        return $this->repository->findAll();
    }

    public function getBook(int $id): ?Book
    {
        return $this->repository->findById($id);
    }

    public function createBook(array $input): array
    {
        $validation = $this->validateBookData($input, true);
        if ($validation['errors']) {
            return ['errors' => $validation['errors']];
        }

        $book = new Book(
            $this->repository->nextId(),
            $validation['data']['titulo'],
            $validation['data']['autor'],
            $validation['data']['anio_publicacion'],
            $validation['data']['genero']
        );

        if (!$this->repository->add($book)) {
            return ['errors' => ['No se pudo guardar el libro']];
        }

        return ['book' => $book];
    }

    public function updateBook(int $id, array $input): array
    {
        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return ['not_found' => true];
        }

        $validation = $this->validateBookData($input, false);
        if ($validation['errors']) {
            return ['errors' => $validation['errors']];
        }

        $updated = new Book(
            $existing->id,
            $validation['data']['titulo'] ?? $existing->titulo,
            $validation['data']['autor'] ?? $existing->autor,
            $validation['data']['anio_publicacion'] ?? $existing->anio_publicacion,
            $validation['data']['genero'] ?? $existing->genero
        );

        if (!$this->repository->update($updated)) {
            return ['errors' => ['No se pudo actualizar el libro']];
        }

        return ['book' => $updated];
    }

    public function deleteBook(int $id): array
    {
        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return ['not_found' => true];
        }

        if (!$this->repository->delete($id)) {
            return ['errors' => ['No se pudo eliminar el libro']];
        }

        return ['deleted' => true];
    }

    private function validateBookData(array $data, bool $requireAllFields = true): array
    {
        $fields = [
            'titulo' => 'Título',
            'autor' => 'Autor',
            'anio_publicacion' => 'Año de publicación',
            'genero' => 'Género',
        ];

        $errors = [];
        $bookData = [];

        foreach ($fields as $field => $label) {
            if (!array_key_exists($field, $data)) {
                if ($requireAllFields) {
                    $errors[] = "$label es obligatorio";
                }
                continue;
            }

            $value = $data[$field];
            if ($field === 'anio_publicacion') {
                if (!is_int($value) && !ctype_digit((string)$value)) {
                    $errors[] = "$label debe ser un número entero";
                } else {
                    $bookData[$field] = (int)$value;
                }
            } else {
                if (!is_string($value) || trim($value) === '') {
                    $errors[] = "$label no puede estar vacío";
                } else {
                    $bookData[$field] = trim($value);
                }
            }
        }

        return ['errors' => $errors, 'data' => $bookData];
    }
}
