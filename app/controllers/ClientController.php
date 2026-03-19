<?php

class ClientController extends Controller
{
    public function index()
    {
        $this->requireRole('admin');

        $clientModel = $this->model('Client');
        $notificationModel = $this->model('Notification');

        $clients = $clientModel->getAllClients();

        $currentUserId = $_SESSION['user_id'] ?? 1;
        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('clients/index', [
            'clients' => $clients,
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function store()
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientModel = $this->model('Client');

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $company = trim($_POST['company'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $status = trim($_POST['status'] ?? 'active');

            if ($name === '' || $email === '' || $company === '') {
                die('Name, email, and company are required.');
            }

            $data = [
                'user_id' => null,
                'name' => $name,
                'email' => $email,
                'company' => $company,
                'phone' => $phone,
                'status' => $status
            ];

            $clientModel->createClient($data);

            header('Location: /pvv/public/client/index');
            exit;
        }

        header('Location: /pvv/public/client/index');
        exit;
    }

    public function edit($id = null)
    {
        $this->requireRole('admin');

        if (!$id) {
            die('Client ID is required.');
        }

        $clientModel = $this->model('Client');
        $client = $clientModel->getClientById($id);

        if (!$client) {
            die('Client not found.');
        }

        header('Content-Type: application/json');
        echo json_encode($client);
        exit;
    }

    public function update($id = null)
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/client/index');
            exit;
        }

        $clientModel = $this->model('Client');

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $status = trim($_POST['status'] ?? 'active');

        if ($name === '' || $email === '' || $company === '') {
            die('Name, email, and company are required.');
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'company' => $company,
            'phone' => $phone,
            'status' => $status
        ];

        $clientModel->updateClient($id, $data);

        header('Location: /pvv/public/client/index');
        exit;
    }

    public function toggle($id = null)
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/client/index');
            exit;
        }

        $status = trim($_POST['status'] ?? '');

        if ($status !== 'active' && $status !== 'inactive') {
            die('Invalid status.');
        }

        $clientModel = $this->model('Client');
        $clientModel->updateClientStatus($id, $status);

        header('Location: /pvv/public/client/index');
        exit;
    }

    public function delete($id = null)
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/client/index');
            exit;
        }

        $clientModel = $this->model('Client');
        $clientModel->deleteClient($id);

        header('Location: /pvv/public/client/index');
        exit;
    }
}