<?php

class Project extends Model
{
    public function getAllProjects()
    {
        $stmt = $this->db->prepare("
            SELECT 
                projects.project_id,
                projects.name,
                projects.client_id,
                projects.service,
                projects.status,
                projects.due_date,
                projects.description,
                clients.name AS client_name,
                clients.company AS client_company
            FROM projects
            LEFT JOIN clients ON projects.client_id = clients.client_id
            ORDER BY projects.project_id DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllClients()
    {
        $stmt = $this->db->prepare("
            SELECT client_id, name, company
            FROM clients
            ORDER BY name ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProject($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO projects (name, client_id, service, status, due_date, description)
            VALUES (:name, :client_id, :service, :status, :due_date, :description)
        ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':client_id' => $data['client_id'],
            ':service' => $data['service'],
            ':status' => $data['status'],
            ':due_date' => $data['due_date'],
            ':description' => $data['description']
        ]);
    }

    public function getProjectById($id)
    {
        $stmt = $this->db->prepare("
            SELECT project_id, name, client_id, service, status, due_date, description
            FROM projects
            WHERE project_id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProject($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE projects
            SET name = :name,
                client_id = :client_id,
                service = :service,
                status = :status,
                due_date = :due_date,
                description = :description,
                updated_at = CURRENT_TIMESTAMP
            WHERE project_id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':client_id' => $data['client_id'],
            ':service' => $data['service'],
            ':status' => $data['status'],
            ':due_date' => $data['due_date'],
            ':description' => $data['description']
        ]);
    }

    public function deleteProject($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM projects
            WHERE project_id = :id
        ");

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function updateProjectStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE projects
            SET status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE project_id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    public function getProjectsByClientEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT 
                projects.project_id,
                projects.name,
                projects.client_id,
                projects.service,
                projects.status,
                projects.due_date,
                projects.description,
                clients.name AS client_name,
                clients.company AS client_company
            FROM projects
            LEFT JOIN clients ON projects.client_id = clients.client_id
            WHERE clients.email = :email
            ORDER BY projects.project_id DESC
        ");

        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}