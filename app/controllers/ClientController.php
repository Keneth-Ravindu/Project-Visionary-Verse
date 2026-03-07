<?php

class ClientController extends Controller
{
    public function index()
    {
        $clientModel = $this->model('Client');
        $clients = $clientModel->getAllClients();

        $this->view('clients/index', ['clients' => $clients]);
    }

    public function store()
    {
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

            header('Location: /Project-Visionary-Verse/public/client/index');
            exit;
        }

        header('Location: /Project-Visionary-Verse/public/client/index');
        exit;
    }

    public function edit($id = null)
    {
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/client/index');
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

        header('Location: /Project-Visionary-Verse/public/client/index');
        exit;
    }
    public function toggle($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/client/index');
            exit;
        }

        $status = trim($_POST['status'] ?? '');

        if ($status !== 'active' && $status !== 'inactive') {
            die('Invalid status.');
        }

        $clientModel = $this->model('Client');
        $clientModel->updateClientStatus($id, $status);

        header('Location: /Project-Visionary-Verse/public/client/index');
        exit;
    }

    public function delete($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/client/index');
            exit;
        }

        $clientModel = $this->model('Client');
        $clientModel->deleteClient($id);

        header('Location: /Project-Visionary-Verse/public/client/index');
        exit;
    }    

}