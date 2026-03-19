<?php

class NotificationApiController extends Controller
{
    public function latest()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            exit;
        }

        $notificationModel = $this->model('Notification');
        $userId = $_SESSION['user_id'];

        $notifications = $notificationModel->getUnreadNotifications($userId, 5);
        $count = $notificationModel->getUnreadCountByUser($userId);

        header('Content-Type: application/json');

        echo json_encode([
            'count' => $count,
            'notifications' => $notifications
        ]);
    }
}