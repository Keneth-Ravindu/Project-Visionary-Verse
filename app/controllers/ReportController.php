<?php

class ReportController extends Controller
{
    public function index($projectId = null)
    {
        $this->requireRole('admin');

        $reportModel = $this->model('Report');

        $projects = $reportModel->getAllProjects();
        $summary = $reportModel->getOverallReportSummary();

        if (isset($_GET['projectId']) && $_GET['projectId'] !== '') {
            $projectId = (int)$_GET['projectId'];
        }

        if (!$projectId && !empty($projects)) {
            $projectId = $projects[0]['project_id'];
        }

        $reportData = $projectId ? $reportModel->getProjectReportData($projectId) : null;

        $notificationModel = $this->model('Notification');
        $currentUserId = $_SESSION['user_id'] ?? 1;
        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);


        $this->view('reports/index', [
            'projects' => $projects,
            'summary' => $summary,
            'reportData' => $reportData,
            'selectedProjectId' => $projectId,
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
            
        ]);
    }

    public function export($projectId = null)
    {
        $this->requireRole('admin');

        $reportModel = $this->model('Report');

        if (!$projectId) {
            die('Project ID is required.');
        }

        $reportData = $reportModel->getProjectReportData($projectId);

        if (!$reportData) {
            die('Project report not found.');
        }

        $this->view('reports/export', [
            'reportData' => $reportData
        ]);
    }
}