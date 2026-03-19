<?php

class Report extends Model
{
    public function getAllProjects()
    {
        $stmt = $this->db->prepare("
            SELECT project_id, name, status, due_date
            FROM projects
            ORDER BY name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectReportData($projectId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                p.project_id,
                p.name,
                p.status,
                p.service,
                p.due_date,
                c.name AS client_name,
                c.company AS client_company
            FROM projects p
            LEFT JOIN clients c ON p.client_id = c.client_id
            WHERE p.project_id = :project_id
        ");
        $stmt->execute([':project_id' => $projectId]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            return null;
        }

        $stmt = $this->db->prepare("
            SELECT
                COUNT(*) AS total_tasks,
                SUM(CASE WHEN status = 'To Do' THEN 1 ELSE 0 END) AS tasks_todo,
                SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS tasks_in_progress,
                SUM(CASE WHEN status = 'Review' THEN 1 ELSE 0 END) AS tasks_review,
                SUM(CASE WHEN status = 'Done' THEN 1 ELSE 0 END) AS tasks_done,
                SUM(CASE WHEN priority = 'High' THEN 1 ELSE 0 END) AS high_priority_tasks,
                SUM(CASE WHEN deadline < CURDATE() AND status != 'Done' THEN 1 ELSE 0 END) AS overdue_tasks
            FROM tasks
            WHERE project_id = :project_id
        ");
        $stmt->execute([':project_id' => $projectId]);
        $taskStats = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("
            SELECT
                COUNT(*) AS total_deliverables,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_deliverables,
                SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) AS approved_deliverables,
                SUM(CASE WHEN status = 'Changes Requested' THEN 1 ELSE 0 END) AS changes_requested
            FROM deliverables
            WHERE project_id = :project_id
        ");
        $stmt->execute([':project_id' => $projectId]);
        $deliverableStats = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("
            SELECT
                u.full_name,
                COUNT(t.task_id) AS assigned_tasks,
                SUM(CASE WHEN t.priority = 'High' THEN 1 ELSE 0 END) AS high_priority_count,
                SUM(CASE WHEN t.deadline < CURDATE() AND t.status <> 'Done' THEN 1 ELSE 0 END) AS overdue_count
            FROM tasks t
            LEFT JOIN users u ON t.assignee_id = u.user_id
            WHERE t.project_id = :project_id
            GROUP BY u.user_id, u.full_name
            ORDER BY assigned_tasks DESC, u.full_name ASC
        ");

        $stmt->execute([':project_id' => $projectId]);
        $teamWorkload = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalTasks = (int)($taskStats['total_tasks'] ?? 0);
        $doneTasks = (int)($taskStats['tasks_done'] ?? 0);
        $progress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        return [
            'project' => $project,
            'taskStats' => $taskStats,
            'deliverableStats' => $deliverableStats,
            'teamWorkload' => $teamWorkload,
            'progress' => $progress
        ];
    }

    public function getOverallReportSummary()
    {
        $stmt = $this->db->prepare("
            SELECT
                (SELECT COUNT(*) FROM clients WHERE status = 'active') AS active_clients,
                (SELECT COUNT(*) FROM projects) AS total_projects,
                (SELECT COUNT(*) FROM tasks) AS total_tasks,
                (SELECT COUNT(*) FROM tasks WHERE status = 'Done') AS completed_tasks,
                (SELECT COUNT(*) FROM deliverables WHERE status = 'Pending') AS pending_approvals
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}