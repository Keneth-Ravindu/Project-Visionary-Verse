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

    protected function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /pvv/public/auth/login");
            exit;
        }
    }

    protected function requireRole($role)
    {
        $this->requireLogin();

        $currentRole = $_SESSION['user_role'] ?? '';

        if (strtolower($currentRole) !== strtolower($role)) {
            $this->redirectByRole();
        }
    }

    protected function requireAnyRole(array $roles)
    {
        $this->requireLogin();

        $currentRole = strtolower($_SESSION['user_role'] ?? '');
        $allowed = array_map('strtolower', $roles);

        if (!in_array($currentRole, $allowed, true)) {
            $this->redirectByRole();
        }
    }

    protected function redirectByRole()
    {
        $role = strtolower($_SESSION['user_role'] ?? '');

        if ($role === 'admin') {
            header("Location: /pvv/public/dashboard/admin");
            exit;
        }

        if ($role === 'staff') {
            header("Location: /pvv/public/dashboard/staff");
            exit;
        }

        if ($role === 'client') {
            header("Location: /pvv/public/dashboard/client");
            exit;
        }

        header("Location: /pvv/public/auth/login");
        exit;
    }
}