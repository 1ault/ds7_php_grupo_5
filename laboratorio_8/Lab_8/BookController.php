<?php
require_once __DIR__ . '/BookService.php';

class BookController
{
    private $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $id = $this->getIdFromQuery();
        if ($method === 'GET') {
            $this->handleGet($id);
            return;
        }

        if ($method === 'POST') {
            $this->handlePost();
            return;
        }

        if ($method === 'PUT') {
            $this->handlePut($id);
            return;
        }

        if ($method === 'DELETE') {
            $this->handleDelete($id);
            return;
        }

        $this->sendResponse(405, ['error' => 'Método no permitido — solo GET, POST, PUT y DELETE disponibles']);
    }

    private function handleGet(?int $id): void
    {
        if ($id !== null) {
            $book = $this->service->getBook($id);
            if ($book === null) {
                $this->sendResponse(404, ['error' => 'Libro no encontrado']);
            }
            $this->sendResponse(200, $book->toArray());
        }

        $books = array_map(fn(Book $book) => $book->toArray(), $this->service->listBooks());
        $this->sendResponse(200, $books);
    }

    private function handlePost(): void
    {
        $input = $this->getJsonInput();
        if ($input === null) {
            $this->sendResponse(400, ['error' => 'Cuerpo JSON requerido']);
        }

        $result = $this->service->createBook($input);
        if (!empty($result['errors'])) {
            $this->sendResponse(422, ['errors' => $result['errors']]);
        }

        $this->sendResponse(201, $result['book']->toArray());
    }

    private function handlePut(?int $id): void
    {
        if ($id === null) {
            $this->sendResponse(400, ['error' => 'ID de libro inválido']);
        }

        $input = $this->getJsonInput();
        if ($input === null) {
            $this->sendResponse(400, ['error' => 'Cuerpo JSON requerido']);
        }

        $result = $this->service->updateBook($id, $input);
        if (!empty($result['not_found'])) {
            $this->sendResponse(404, ['error' => 'Libro no encontrado']);
        }
        if (!empty($result['errors'])) {
            $this->sendResponse(422, ['errors' => $result['errors']]);
        }

        $this->sendResponse(200, ['message' => 'Libro actualizado', 'book' => $result['book']->toArray()]);
    }

    private function handleDelete(?int $id): void
    {
        if ($id === null) {
            $this->sendResponse(400, ['error' => 'ID de libro inválido']);
        }

        $result = $this->service->deleteBook($id);
        if (!empty($result['not_found'])) {
            $this->sendResponse(404, ['error' => 'Libro no encontrado']);
        }
        if (!empty($result['errors'])) {
            $this->sendResponse(500, ['error' => $result['errors'][0]]);
        }

        $this->sendResponse(200, ['message' => 'Libro eliminado']);
    }

    private function getJsonInput(): ?array
    {
        $rawBody = file_get_contents('php://input');
        if (empty($rawBody)) {
            return null;
        }

        $data = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(400, [
                'error' => 'JSON inválido',
                'message' => json_last_error_msg(),
            ]);
        }

        return is_array($data) ? $data : null;
    }

    private function getIdFromQuery(): ?int
    {
        if (!isset($_GET['id'])) {
            return null;
        }

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        return $id === false ? null : $id;
    }

    private function sendResponse(int $statusCode, $data = null): void
    {
        http_response_code($statusCode);
        if ($data !== null) {
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
}
