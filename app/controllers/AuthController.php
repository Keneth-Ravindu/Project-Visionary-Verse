<?php

class AuthController extends Controller
{
    public function index()
    {
        $this->view('auth/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('auth/login', [
                'error' => ''
            ]);
            return;
        }

        $userModel = $this->model('User');

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->view('auth/login', [
                'error' => 'Email and password are required.'
            ]);
            return;
        }

        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->view('auth/login', [
                'error' => 'Invalid email or password.'
            ]);
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $this->view('auth/login', [
                'error' => 'Invalid email or password.'
            ]);
            return;
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = strtolower($user['role']);

        if ($_SESSION['user_role'] === 'admin') {
            header('Location: /pvv/public/dashboard/admin');
            exit;
        }

        if ($_SESSION['user_role'] === 'staff') {
            header('Location: /pvv/public/dashboard/staff');
            exit;
        }

        if ($_SESSION['user_role'] === 'client') {
            header('Location: /pvv/public/dashboard/client');
            exit;
        }

        header('Location: /pvv/public/auth/login');
        exit;
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header('Location: /pvv/public/auth/login');
        exit;
    }
}
