<?php

class Deliverable extends Model
{
    public function getAllDeliverables()
    {
        $stmt = $this->db->prepare("
            SELECT 
                deliverables.deliverable_id,
                deliverables.name,
                deliverables.project_id,
                deliverables.uploaded_by,
                deliverables.file_path,
                deliverables.file_name,
                deliverables.status,
                deliverables.feedback,
                deliverables.submitted_at,
                deliverables.reviewed_at,
                projects.name AS project_name,
                users.full_name AS uploader_name
            FROM deliverables
            LEFT JOIN projects ON deliverables.project_id = projects.project_id
            LEFT JOIN users ON deliverables.uploaded_by = users.user_id
            ORDER BY deliverables.deliverable_id DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProjects()
    {
        $stmt = $this->db->prepare("
            SELECT project_id, name
            FROM projects
            ORDER BY name
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare("
            SELECT user_id, full_name
            FROM users
            ORDER BY full_name
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDeliverable($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO deliverables (name, project_id, uploaded_by, file_path, file_name, status, feedback, submitted_at)
            VALUES (:name, :project_id, :uploaded_by, :file_path, :file_name, :status, :feedback, NOW())
        ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':project_id' => $data['project_id'],
            ':uploaded_by' => $data['uploaded_by'],
            ':file_path' => $data['file_path'],
            ':file_name' => $data['file_name'],
            ':status' => $data['status'],
            ':feedback' => $data['feedback']
        ]);
    }

    public function getDeliverableById($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                deliverables.deliverable_id,
                deliverables.name,
                deliverables.project_id,
                deliverables.uploaded_by,
                deliverables.file_path,
                deliverables.file_name,
                deliverables.status,
                deliverables.feedback,
                deliverables.submitted_at,
                deliverables.reviewed_at,
                projects.name AS project_name,
                users.full_name AS uploader_name
            FROM deliverables
            LEFT JOIN projects ON deliverables.project_id = projects.project_id
            LEFT JOIN users ON deliverables.uploaded_by = users.user_id
            WHERE deliverables.deliverable_id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateDeliverableStatus($id, $status, $feedback = null)
    {
        $stmt = $this->db->prepare("
            UPDATE deliverables
            SET status = :status,
                feedback = :feedback,
                reviewed_at = NOW()
            WHERE deliverable_id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
            ':feedback' => $feedback
        ]);
    }
}