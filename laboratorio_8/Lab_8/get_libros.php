<?php
// Front controller para la API de libros
// Muestra errores para facilitar la depuración (quitar en producción)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

$storageFile = __DIR__ . '/libros_data.json';

require_once __DIR__ . '/Book.php';
require_once __DIR__ . '/BookRepository.php';
require_once __DIR__ . '/BookService.php';
require_once __DIR__ . '/BookController.php';

$repository = new BookRepository($storageFile);
$service = new BookService($repository);
$controller = new BookController($service);
$controller->handleRequest();
