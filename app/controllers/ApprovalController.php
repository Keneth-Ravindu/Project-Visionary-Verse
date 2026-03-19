<?php

class ApprovalController extends Controller
{
    public function index()
    {
        $this->requireLogin();

        $deliverableModel = $this->model('Deliverable');
        $notificationModel = $this->model('Notification');

        $deliverables = $deliverableModel->getAllDeliverables();
        $projects = $deliverableModel->getAllProjects();
        $users = $deliverableModel->getAllUsers();

        $currentUserId = $_SESSION['user_id'] ?? 1;
        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('approvals/index', [
            'deliverables' => $deliverables,
            'projects' => $projects,
            'users' => $users,
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function store()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pvv/public/approval/index');
            exit;
        }

        $deliverableModel = $this->model('Deliverable');

        $name = trim($_POST['name'] ?? '');
        $project_id = trim($_POST['project_id'] ?? '');
        $uploaded_by = trim($_POST['uploaded_by'] ?? '');

        if ($name === '' || $project_id === '' || $uploaded_by === '') {
            die('Deliverable name, project, and uploaded by are required.');
        }

        $filePath = null;
        $fileName = null;

        if (isset($_FILES['deliverable_file']) && $_FILES['deliverable_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/deliverables/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $originalName = basename($_FILES['deliverable_file']['name']);
            $tmpName = $_FILES['deliverable_file']['tmp_name'];
            $fileSize = $_FILES['deliverable_file']['size'];

            $allowedExtensions = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx', 'ppt', 'pptx'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions)) {
                die('Invalid file type. Allowed: pdf, png, jpg, jpeg, doc, docx, ppt, pptx');
            }

            if ($fileSize > 5 * 1024 * 1024) {
                die('File is too large. Maximum size is 5MB.');
            }

            $safeFileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\.-]/', '_', $originalName);
            $destination = $uploadDir . $safeFileName;

            if (!move_uploaded_file($tmpName, $destination)) {
                die('Failed to upload file.');
            }

            $filePath = '/pvv/public/uploads/deliverables/' . $safeFileName;
            $fileName = $originalName;
        }

        $data = [
            'name' => $name,
            'project_id' => $project_id,
            'uploaded_by' => $uploaded_by,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'status' => 'Pending',
            'feedback' => null
        ];

        $deliverableModel->createDeliverable($data);

        $notificationModel = $this->model('Notification');

        $adminUserId = $_SESSION['user_id'] ?? 1;

        $message = 'Deliverable "' . $name . '" has been uploaded for approval';

        $notificationModel->createNotification(
            $adminUserId,
            'approval',
            $message
        );

        header('Location: /pvv/public/approval/index');
        exit;
    }

    public function show($id = null)
    {
        $this->requireLogin();

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
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/approval/index');
            exit;
        }

        $deliverableModel = $this->model('Deliverable');
        $deliverable = $deliverableModel->getDeliverableById($id);

        $deliverableModel->updateDeliverableStatus($id, 'Approved', null);

        $notificationModel = $this->model('Notification');

        $message = 'Your deliverable has been approved';

        $notificationModel->createNotification(
            $deliverable['uploaded_by'],
            'approval',
            $message
        );

        header('Location: /pvv/public/approval/index');
        exit;
    }

    public function changes($id = null)
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/approval/index');
            exit;
        }

        $feedback = trim($_POST['feedback'] ?? 'Changes requested by client.');

        $deliverableModel = $this->model('Deliverable');
        $deliverable = $deliverableModel->getDeliverableById($id);

        $deliverableModel->updateDeliverableStatus($id, 'Changes Requested', $feedback);

        $notificationModel = $this->model('Notification');

        $message = 'Changes requested for deliverable "' . $deliverable['name'] . '"';

        $notificationModel->createNotification(
            $deliverable['uploaded_by'],
            'approval',
            $message
        );

        header('Location: /pvv/public/approval/index');
        exit;
    }
}