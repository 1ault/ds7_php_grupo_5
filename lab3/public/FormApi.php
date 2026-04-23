<?php


class FormApi
{ 
    private array $form_data;
    private string $request_method;

    public function __construct()
    {
        $this->request_method = $_SERVER['REQUEST_METHOD'];

        $this->form_data = match ($this->request_method) {
            'POST' => $_POST,
            'GET'  => $_GET,
            default => []
        };
    }

    public function getRequestMethod()
    {
        return $this->request_method;
    }

    public function getKey(string $key)
    {
        if (!isset($this->form_data[$key])) 
        {
            return "NULL";
        }

        return $this->form_data[$key];
    }
}

$form = new FormApi();


$file = match ($form->getRequestMethod()) {
    'POST' => "FormSalidaPost.php",
    'GET'  => "FormSalidaGet.php",
    default => null
};


if (!$file) {
    exit("Vacio request_method");
}

require_once __DIR__ . "/" . $file;
