<?php

class TaskController extends Controller
{
    public function index()
    {
        $taskModel = $this->model('Task');

        $tasks = $taskModel->getAllTasks();
        $projects = $taskModel->getAllProjects();
        $users = $taskModel->getAllUsers();

        $this->view('tasks/index', [
            'tasks' => $tasks,
            'projects' => $projects,
            'users' => $users
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Project-Visionary-Verse/public/task/index');
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

        header('Location: /Project-Visionary-Verse/public/task/index');
        exit;
    }

    public function edit($id = null)
    {
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/task/index');
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

        header('Location: /Project-Visionary-Verse/public/task/index');
        exit;
    }

    public function delete($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/task/index');
            exit;
        }

        $taskModel = $this->model('Task');
        $taskModel->deleteTask($id);

        header('Location: /Project-Visionary-Verse/public/task/index');
        exit;
    }

    public function moveStatus($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/task/index');
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
        $taskModel->updateTaskStatus($id, $nextStatus);

        header('Location: /Project-Visionary-Verse/public/task/index');
        exit;
    }    

}