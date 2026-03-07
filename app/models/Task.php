<?php

class Task extends Model
{
    public function getAllTasks()
    {
        $stmt = $this->db->prepare("
            SELECT 
                tasks.task_id,
                tasks.name,
                tasks.project_id,
                tasks.assignee_id,
                tasks.priority,
                tasks.status,
                tasks.deadline,
                tasks.description,
                projects.name AS project_name,
                users.full_name AS assignee_name
            FROM tasks
            LEFT JOIN projects ON tasks.project_id = projects.project_id
            LEFT JOIN users ON tasks.assignee_id = users.user_id
            ORDER BY tasks.task_id DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProjects()
    {
        $stmt = $this->db->prepare("
            SELECT project_id, name
            FROM projects
            ORDER BY name ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare("
            SELECT user_id, full_name
            FROM users
            ORDER BY full_name ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (name, project_id, assignee_id, priority, status, deadline, description)
            VALUES (:name, :project_id, :assignee_id, :priority, :status, :deadline, :description)
        ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':project_id' => $data['project_id'],
            ':assignee_id' => $data['assignee_id'],
            ':priority' => $data['priority'],
            ':status' => $data['status'],
            ':deadline' => $data['deadline'],
            ':description' => $data['description']
        ]);
    }

    public function getTaskById($id)
    {
        $stmt = $this->db->prepare("
            SELECT task_id, name, project_id, assignee_id, priority, status, deadline, description
            FROM tasks
            WHERE task_id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTask($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET name = :name,
                project_id = :project_id,
                assignee_id = :assignee_id,
                priority = :priority,
                status = :status,
                deadline = :deadline,
                description = :description,
                updated_at = CURRENT_TIMESTAMP
            WHERE task_id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':project_id' => $data['project_id'],
            ':assignee_id' => $data['assignee_id'],
            ':priority' => $data['priority'],
            ':status' => $data['status'],
            ':deadline' => $data['deadline'],
            ':description' => $data['description']
        ]);
    }

    public function deleteTask($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM tasks
            WHERE task_id = :id
        ");

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function updateTaskStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE task_id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

}