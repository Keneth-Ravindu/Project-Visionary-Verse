<?php

class ChatbotController extends Controller
{
    public function index()
    {
        $this->requireLogin();

        $notificationModel = $this->model('Notification');
        $currentUserId = $_SESSION['user_id'] ?? 1;

        $latestNotifications = $notificationModel->getUnreadNotifications($currentUserId, 5);
        $unreadCount = $notificationModel->getUnreadCountByUser($currentUserId);

        $this->view('chatbot/index', [
            'latestNotifications' => $latestNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function ask()
    {
        $this->requireLogin();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ]);
            exit;
        }

        require_once "../app/services/ChatbotService.php";

        $message = trim($_POST['message'] ?? '');
        $userId = $_SESSION['user_id'] ?? 1;
        $userRole = strtolower(trim($_SESSION['user_role'] ?? 'admin'));
        $userEmail = $_SESSION['user_email'] ?? '';

        try {
            $service = new ChatbotService();
            $result = $service->getReply($message, $userId, $userRole, $userEmail);

            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong while processing your request.'
            ]);
        }

        exit;
    }
}