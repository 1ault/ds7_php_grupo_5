<?php
require_once __DIR__ . '/Book.php';

class BookRepository
{
    private $storageFile;

    public function __construct($storageFile)
    {
        $this->storageFile = $storageFile;
    }

    public function findAll(): array
    {
        $books = [];
        $content = @file_get_contents($this->storageFile);

        if ($content === false) {
            return [];
        }

        $data = json_decode($content, true);
        if (!is_array($data)) {
            return [];
        }

        foreach ($data as $item) {
            if (isset($item['id'], $item['titulo'], $item['autor'], $item['anio_publicacion'], $item['genero'])) {
                $books[] = new Book(
                    (int)$item['id'],
                    (string)$item['titulo'],
                    (string)$item['autor'],
                    (int)$item['anio_publicacion'],
                    (string)$item['genero']
                );
            }
        }

        return $books;
    }

    public function findById(int $id): ?Book
    {
        foreach ($this->findAll() as $book) {
            if ($book->id === $id) {
                return $book;
            }
        }
        return null;
    }

    public function add(Book $book): bool
    {
        $books = $this->findAll();
        $books[] = $book;
        return $this->saveAll($books);
    }

    public function update(Book $book): bool
    {
        $books = $this->findAll();
        foreach ($books as $index => $existing) {
            if ($existing->id === $book->id) {
                $books[$index] = $book;
                return $this->saveAll($books);
            }
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $books = $this->findAll();
        foreach ($books as $index => $book) {
            if ($book->id === $id) {
                array_splice($books, $index, 1);
                return $this->saveAll($books);
            }
        }
        return false;
    }

    public function nextId(): int
    {
        $books = $this->findAll();
        $maxId = 0;
        foreach ($books as $book) {
            if ($book->id > $maxId) {
                $maxId = $book->id;
            }
        }
        return $maxId + 1;
    }

    private function saveAll(array $books): bool
    {
        $payload = array_map(fn(Book $book) => $book->toArray(), $books);
        return file_put_contents($this->storageFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }
}
