<?php

class Notification extends Model
{
    public function createNotification($user_id, $type, $message)
    {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, message, is_read, created_at)
            VALUES (:user_id, :type, :message, 0, NOW())
        ");

        return $stmt->execute([
            ':user_id' => $user_id,
            ':type' => $type,
            ':message' => $message
        ]);
    }

    public function getNotificationsByUser($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT notification_id, user_id, type, message, is_read, created_at
            FROM notifications
            WHERE user_id = :user_id
            ORDER BY notification_id DESC
        ");

        $stmt->execute([
            ':user_id' => $user_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreadNotifications($user_id, $limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT notification_id, user_id, type, message, is_read, created_at
            FROM notifications
            WHERE user_id = :user_id
              AND is_read = 0
            ORDER BY notification_id DESC
            LIMIT :limit_val
        ");

        $stmt->bindValue(':user_id', (int)$user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit_val', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreadCountByUser($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM notifications
            WHERE user_id = :user_id
              AND is_read = 0
        ");

        $stmt->execute([
            ':user_id' => $user_id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function markReadByUser($notification_id, $user_id)
    {
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1
            WHERE notification_id = :notification_id
              AND user_id = :user_id
        ");

        return $stmt->execute([
            ':notification_id' => $notification_id,
            ':user_id' => $user_id
        ]);
    }

    public function markAllReadByUser($user_id)
    {
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1
            WHERE user_id = :user_id
              AND is_read = 0
        ");

        return $stmt->execute([
            ':user_id' => $user_id
        ]);
    }

    public function notificationExistsToday($user_id, $type, $message)
    {
        $stmt = $this->db->prepare("
            SELECT notification_id
            FROM notifications
            WHERE user_id = :user_id
              AND type = :type
              AND message = :message
              AND DATE(created_at) = CURDATE()
            LIMIT 1
        ");

        $stmt->execute([
            ':user_id' => $user_id,
            ':type' => $type,
            ':message' => $message
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}