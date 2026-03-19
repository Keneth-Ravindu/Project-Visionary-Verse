<?php

class TaskController extends Controller
{
    public function index()
    {
        $this->requireAnyRole(['admin', 'staff']);

        $taskModel = $this->model('Task');
        $notificationModel = $this->model('Notification');

        $tasks = $taskModel->getAllTasks();
        $projects = $taskModel->getAllProjects();
        $users = $taskModel->getAllUsers();

        $currentUserId = $_SESSION['user_id'] ?? 1;

        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('tasks/index', [
            'tasks' => $tasks,
            'projects' => $projects,
            'users' => $users,
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function store()
    {
        $this->requireAnyRole(['admin', 'staff']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pvv/public/task/index');
            exit;
        }

        $taskModel = $this->model('Task');

        $name = trim($_POST['name'] ?? '');
        $project_id = trim($_POST['project_id'] ?? '');
        $assignee_id = trim($_POST['assignee_id'] ?? '');
        $priority = trim($_POST['priority'] ?? 'Medium');
        $status = trim($_POST['status'] ?? 'To Do');
        $deadline = trim($_POST['deadline'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $project_id === '' || $assignee_id === '' || $deadline === '') {
            die('Task name, project, assignee, and deadline are required.');
        }

        $data = [
            'name' => $name,
            'project_id' => $project_id,
            'assignee_id' => $assignee_id,
            'priority' => $priority,
            'status' => $status,
            'deadline' => $deadline,
            'description' => $description
        ];

        $taskModel->createTask($data);

        $notificationModel = $this->model('Notification');

        $message = 'You have been assigned to "' . $name . '"';

        $notificationModel->createNotification(
            $assignee_id,
            'task',
            $message
        );

        header('Location: /pvv/public/task/index');
        exit;
    }

    public function edit($id = null)
    {
        $this->requireAnyRole(['admin', 'staff']);

        if (!$id) {
            die('Task ID is required.');
        }

        $taskModel = $this->model('Task');
        $task = $taskModel->getTaskById($id);

        if (!$task) {
            die('Task not found.');
        }

        header('Content-Type: application/json');
        echo json_encode($task);
        exit;
    }

    public function update($id = null)
    {
        $this->requireAnyRole(['admin', 'staff']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/task/index');
            exit;
        }

        $taskModel = $this->model('Task');

        $name = trim($_POST['name'] ?? '');
        $project_id = trim($_POST['project_id'] ?? '');
        $assignee_id = trim($_POST['assignee_id'] ?? '');
        $priority = trim($_POST['priority'] ?? 'Medium');
        $status = trim($_POST['status'] ?? 'To Do');
        $deadline = trim($_POST['deadline'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $project_id === '' || $assignee_id === '' || $deadline === '') {
            die('Task name, project, assignee, and deadline are required.');
        }

        $data = [
            'name' => $name,
            'project_id' => $project_id,
            'assignee_id' => $assignee_id,
            'priority' => $priority,
            'status' => $status,
            'deadline' => $deadline,
            'description' => $description
        ];

        $taskModel->updateTask($id, $data);

        header('Location: /pvv/public/task/index');
        exit;
    }

    public function delete($id = null)
    {
        $this->requireAnyRole(['admin', 'staff']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/task/index');
            exit;
        }

        $taskModel = $this->model('Task');
        $taskModel->deleteTask($id);

        header('Location: /pvv/public/task/index');
        exit;
    }

    public function moveStatus($id = null)
    {
        $this->requireAnyRole(['admin', 'staff']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/task/index');
            exit;
        }

        $currentStatus = trim($_POST['current_status'] ?? 'To Do');

        $order = ['To Do', 'In Progress', 'Review', 'Done'];
        $index = array_search($currentStatus, $order, true);

        if ($index === false) {
            $nextStatus = 'To Do';
        } else {
            $nextStatus = $order[($index + 1) % count($order)];
        }

        $taskModel = $this->model('Task');

        $task = $taskModel->getTaskById($id);

        $taskModel->updateTaskStatus($id, $nextStatus);

        $notificationModel = $this->model('Notification');

        $message = 'Task "' . $task['name'] . '" moved to ' . $nextStatus;

        $notificationModel->createNotification(
            $task['assignee_id'],
            'task',
            $message
        );

        header('Location: /pvv/public/task/index');
        exit;
    }
}