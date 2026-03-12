<?php

class ApprovalController extends Controller
{
    public function index()
    {
        $deliverableModel = $this->model('Deliverable');

        $deliverables = $deliverableModel->getAllDeliverables();
        $projects = $deliverableModel->getAllProjects();
        $users = $deliverableModel->getAllUsers();

        $this->view('approvals/index', [
            'deliverables' => $deliverables,
            'projects' => $projects,
            'users' => $users
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Project-Visionary-Verse/public/approval/index');
            exit;
        }

        $deliverableModel = $this->model('Deliverable');

        $name = trim($_POST['name'] ?? '');
        $project_id = trim($_POST['project_id'] ?? '');
        $uploaded_by = trim($_POST['uploaded_by'] ?? '');
        $file_name = trim($_POST['file_name'] ?? '');
        
        if ($name === '' || $project_id === '' || $uploaded_by === '') {
            die('Deliverable name, project, and uploaded by are required.');
        }

        $data = [
            'name' => $name,
            'project_id' => $project_id,
            'uploaded_by' => $uploaded_by,
            'file_path' => null,
            'file_name' => $file_name === '' ? null : $file_name,
            'status' => 'Pending',
            'feedback' => null
        ];

        $deliverableModel->createDeliverable($data);

        header('Location: /Project-Visionary-Verse/public/approval/index');
        exit;
    }

    public function show($id = null)
    {
        if (!$id) {
            die('Deliverable ID is required.');
        }

        $deliverableModel = $this->model('Deliverable');
        $deliverable = $deliverableModel->getDeliverableById($id);

        if (!$deliverable) {
            die('Deliverable not found.');
        }

        header('Content-Type: application/json');
        echo json_encode($deliverable);
        exit;
    }

    public function approve($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/approval/index');
            exit;
        }

        $deliverableModel = $this->model('Deliverable');
        $deliverableModel->updateDeliverableStatus($id, 'Approved', null);

        header('Location: /Project-Visionary-Verse/public/approval/index');
        exit;
    }

    public function changes($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /Project-Visionary-Verse/public/approval/index');
            exit;
        }

        $feedback = trim($_POST['feedback'] ?? 'Changes requested by client.');

        $deliverableModel = $this->model('Deliverable');
        $deliverableModel->updateDeliverableStatus($id, 'Changes Requested', $feedback);

        header('Location: /Project-Visionary-Verse/public/approval/index');
        exit;
    }
}