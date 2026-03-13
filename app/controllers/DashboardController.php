<?php

class DashboardController extends Controller
{
    public function admin()
    {
        $this->requireRole('admin');

        $notificationModel = $this->model('Notification');
        $currentUserId = $_SESSION['user_id'] ?? 1;

        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('dashboard/admin', [
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function staff()
    {
        $this->requireRole('staff');

        $notificationModel = $this->model('Notification');
        $currentUserId = $_SESSION['user_id'] ?? 1;

        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('dashboard/staff', [
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function client()
    {
        $this->requireRole('client');

        $notificationModel = $this->model('Notification');
        $currentUserId = $_SESSION['user_id'] ?? 1;

        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('dashboard/client', [
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }
}
