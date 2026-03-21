<?php

class User extends Model
{
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT user_id, email, password, role, full_name
            FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}