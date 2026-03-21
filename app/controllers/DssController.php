<?php

class DssController extends Controller
{
    public function index()
    {
        $this->requireRole('admin');

        $dssModel = $this->model('DSS');
        $notificationModel = $this->model('Notification');

        $riskData = $dssModel->getProjectDelayRiskData();
        $priorityData = $dssModel->getClientPriorityScores();

        $adminUserId = $_SESSION['user_id'] ?? 1;

        foreach ($riskData as $project) {
            if (($project['risk_level'] ?? '') === 'High') {
                $message = 'High risk detected for project "' . $project['project_name'] . '"';

                $exists = $notificationModel->notificationExistsToday(
                    $adminUserId,
                    'dss',
                    $message
                );

                if (!$exists) {
                    $notificationModel->createNotification(
                        $adminUserId,
                        'dss',
                        $message
                    );
                }
            }
        }

        $latestNotifications = $notificationModel->getUnreadNotifications($adminUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($adminUserId);

        $this->view('dss/index', [
            'riskData' => $riskData,
            'priorityData' => $priorityData,
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }
}