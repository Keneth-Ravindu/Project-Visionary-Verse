<?php

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);

        $viewFile = "../app/views/" . $view . ".php";

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View does not exist: " . $view);
        }
    }

    public function model($model)
    {
        $modelFile = "../app/models/" . $model . ".php";

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }

        die("Model does not exist: " . $model);
    }
}