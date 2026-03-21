<?php

class NotificationController extends Controller
{
    private function getCurrentUserId()
    {
        return $_SESSION['user_id'] ?? 1;
    }

    public function index()
    {
        $this->requireLogin();

        $notificationModel = $this->model('Notification');
        $userId = $this->getCurrentUserId();

        $notifications = $notificationModel->getNotificationsByUser($userId);
        $latestNotifications = $notificationModel->getUnreadNotifications($userId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($userId);

        $this->view('notifications/index', [
            'notifications' => $notifications,
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function read($id = null)
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: /pvv/public/notification/index');
            exit;
        }

        $notificationModel = $this->model('Notification');
        $userId = $this->getCurrentUserId();

        $notificationModel->markReadByUser($id, $userId);

        header('Location: /pvv/public/notification/index');
        exit;
    }

    public function readAll()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pvv/public/notification/index');
            exit;
        }

        $notificationModel = $this->model('Notification');
        $userId = $this->getCurrentUserId();

        $notificationModel->markAllReadByUser($userId);

        header('Location: /pvv/public/notification/index');
        exit;
    }
}