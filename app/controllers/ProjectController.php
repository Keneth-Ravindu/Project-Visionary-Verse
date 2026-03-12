<?php

class ProjectController extends Controller
{
    public function index()
    {
        $this->requireLogin();

        $projectModel = $this->model('Project');
        $notificationModel = $this->model('Notification');

        $userRole = $_SESSION['user_role'] ?? 'admin';
        $userEmail = $_SESSION['user_email'] ?? '';

        if ($userRole === 'client') {
            $projects = $projectModel->getProjectsByClientEmail($userEmail);
            $clients = [];
        } else {
            $projects = $projectModel->getAllProjects();
            $clients = $projectModel->getAllClients();
        }

        $currentUserId = $_SESSION['user_id'] ?? 1;
        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('projects/index', [
            'projects' => $projects,
            'clients' => $clients,
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function store()
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pvv/public/project/index');
            exit;
        }

        $projectModel = $this->model('Project');

        $name = trim($_POST['name'] ?? '');
        $client_id = trim($_POST['client_id'] ?? '');
        $service = trim($_POST['service'] ?? '');
        $status = trim($_POST['status'] ?? 'To Do');
        $due_date = trim($_POST['due_date'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $client_id === '' || $service === '' || $due_date === '') {
            die('Project name, client, service, and due date are required.');
        }

        $data = [
            'name' => $name,
            'client_id' => $client_id,
            'service' => $service,
            'status' => $status,
            'due_date' => $due_date,
            'description' => $description
        ];

        $projectModel->createProject($data);

        header('Location: /pvv/public/project/index');
        exit;
    }

    public function edit($id = null)
    {
        $this->requireRole('admin');

        if (!$id) {
            die('Project ID is required.');
        }

        $projectModel = $this->model('Project');
        $project = $projectModel->getProjectById($id);

        if (!$project) {
            die('Project not found.');
        }

        header('Content-Type: application/json');
        echo json_encode($project);
        exit;
    }

    public function update($id = null)
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/project/index');
            exit;
        }

        $projectModel = $this->model('Project');

        $name = trim($_POST['name'] ?? '');
        $client_id = trim($_POST['client_id'] ?? '');
        $service = trim($_POST['service'] ?? '');
        $status = trim($_POST['status'] ?? 'To Do');
        $due_date = trim($_POST['due_date'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $client_id === '' || $service === '' || $due_date === '') {
            die('Project name, client, service, and due date are required.');
        }

        $data = [
            'name' => $name,
            'client_id' => $client_id,
            'service' => $service,
            'status' => $status,
            'due_date' => $due_date,
            'description' => $description
        ];

        $projectModel->updateProject($id, $data);

        header('Location: /pvv/public/project/index');
        exit;
    }

    public function delete($id = null)
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/project/index');
            exit;
        }

        $projectModel = $this->model('Project');
        $projectModel->deleteProject($id);

        header('Location: /pvv/public/project/index');
        exit;
    }

    public function toggleStatus($id = null)
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/project/index');
            exit;
        }

        $currentStatus = trim($_POST['current_status'] ?? 'To Do');

        $order = ['To Do', 'In Progress', 'Review', 'Completed'];
        $index = array_search($currentStatus, $order, true);

        if ($index === false) {
            $nextStatus = 'To Do';
        } else {
            $nextStatus = $order[($index + 1) % count($order)];
        }

        $projectModel = $this->model('Project');
        $projectModel->updateProjectStatus($id, $nextStatus);

        header('Location: /pvv/public/project/index');
        exit;
    }
}