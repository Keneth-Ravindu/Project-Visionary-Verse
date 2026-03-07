<?php

class Client extends Model
{
    public function getAllClients()
    {
        $stmt = $this->db->prepare("
            SELECT 
                clients.client_id,
                clients.name,
                clients.email,
                clients.company,
                clients.phone,
                clients.status,
                users.full_name AS account_name
            FROM clients
            LEFT JOIN users ON clients.user_id = users.user_id
            ORDER BY clients.client_id DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createClient($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO clients (user_id, name, email, company, phone, status)
            VALUES (:user_id, :name, :email, :company, :phone, :status)
        ");

        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':company' => $data['company'],
            ':phone' => $data['phone'],
            ':status' => $data['status']
        ]);
    }

    public function getClientById($id)
    {
        $stmt = $this->db->prepare("
            SELECT client_id, user_id, name, email, company, phone, status
            FROM clients
            WHERE client_id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateClient($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE clients
            SET name = :name,
                email = :email,
                company = :company,
                phone = :phone,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE client_id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':company' => $data['company'],
            ':phone' => $data['phone'],
            ':status' => $data['status']
        ]);
    }

    public function updateClientStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE clients
            SET status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE client_id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }
    public function deleteClient($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM clients
            WHERE client_id = :id
        ");

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}