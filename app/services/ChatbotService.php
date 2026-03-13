<?php

class ChatbotService extends Model
{
    public function getReply($message, $userId, $userRole, $userEmail = '')
    {
        $text = strtolower(trim($message));

        if ($text === '') {
            return "Please type a question.";
        }

        if (strpos($text, 'my overdue tasks') !== false) {
            return $this->getMyOverdueTasksReply($userId, $userRole);
        }

        if (strpos($text, 'my tasks') !== false || strpos($text, 'show my tasks') !== false) {
            return $this->getMyTasksReply($userId, $userRole);
        }

        if (strpos($text, 'overdue tasks') !== false) {
            return $this->getOverdueTasksReply($userId, $userRole);
        }

        if (strpos($text, 'pending approvals') !== false || strpos($text, 'approvals') !== false) {
            return $this->getPendingApprovalsReply($userId, $userRole, $userEmail);
        }

        if (
            strpos($text, 'delayed project') !== false ||
            strpos($text, 'project risk') !== false ||
            strpos($text, 'at risk') !== false
        ) {
            return $this->getDelayedProjectsReply($userRole);
        }

        if (
            strpos($text, 'high priority client') !== false ||
            strpos($text, 'priority client') !== false
        ) {
            return $this->getHighPriorityClientsReply($userRole);
        }

        if (strpos($text, 'project status ') !== false) {
            $projectName = trim(str_ireplace('project status', '', $message));
            return $this->getSpecificProjectStatusReply($projectName, $userRole, $userEmail);
        }

        if (strpos($text, 'project status') !== false || strpos($text, 'show projects') !== false) {
            return $this->getProjectStatusReply($userRole, $userEmail);
        }

        if (strpos($text, 'show clients') !== false || strpos($text, 'clients') !== false) {
            return $this->getClientsReply($userRole);
        }

        return "I can help with my tasks, my overdue tasks, overdue tasks, pending approvals, project risk, high priority clients, project status, and clients.";
    }

    private function getMyTasksReply($userId, $userRole)
    {
        if ($userRole === 'client') {
            return "Clients do not have assigned internal tasks in the current system.";
        }

        if ($userRole === 'staff') {
            $stmt = $this->db->prepare("
                SELECT name, status, deadline
                FROM tasks
                WHERE assignee_id = :user_id
                ORDER BY deadline ASC
                LIMIT 5
            ");
            $stmt->execute([':user_id' => $userId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT name, status, deadline
                FROM tasks
                ORDER BY deadline ASC
                LIMIT 5
            ");
            $stmt->execute();
        }

        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$tasks) {
            return "No tasks found.";
        }

        $lines = [];
        foreach ($tasks as $task) {
            $lines[] = $task['name'] . ' (' . $task['status'] . ', due ' . $task['deadline'] . ')';
        }

        return "Here are the latest tasks: " . implode("; ", $lines) . ".";
    }

    private function getMyOverdueTasksReply($userId, $userRole)
    {
        if ($userRole === 'client') {
            return "Clients do not have internal assigned tasks.";
        }

        if ($userRole === 'staff') {
            $stmt = $this->db->prepare("
                SELECT name, deadline
                FROM tasks
                WHERE assignee_id = :user_id
                  AND deadline < CURDATE()
                  AND status <> 'Done'
                ORDER BY deadline ASC
                LIMIT 5
            ");
            $stmt->execute([':user_id' => $userId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT name, deadline
                FROM tasks
                WHERE deadline < CURDATE()
                  AND status <> 'Done'
                ORDER BY deadline ASC
                LIMIT 5
            ");
            $stmt->execute();
        }

        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$tasks) {
            return "No overdue tasks found.";
        }

        $lines = [];
        foreach ($tasks as $task) {
            $lines[] = $task['name'] . ' (due ' . $task['deadline'] . ')';
        }

        return "Overdue tasks: " . implode("; ", $lines) . ".";
    }

    private function getOverdueTasksReply($userId, $userRole)
    {
        if ($userRole === 'staff') {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) AS total
                FROM tasks
                WHERE assignee_id = :user_id
                  AND deadline < CURDATE()
                  AND status <> 'Done'
            ");
            $stmt->execute([':user_id' => $userId]);
        } elseif ($userRole === 'client') {
            return "Clients do not have access to internal overdue task counts.";
        } else {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) AS total
                FROM tasks
                WHERE deadline < CURDATE()
                  AND status <> 'Done'
            ");
            $stmt->execute();
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = (int)($row['total'] ?? 0);

        return "There are currently {$count} overdue tasks.";
    }

    private function getPendingApprovalsReply($userId, $userRole, $userEmail)
    {
        if ($userRole === 'client') {
            $stmt = $this->db->prepare("
                SELECT d.name, d.status, d.submitted_at
                FROM deliverables d
                INNER JOIN projects p ON d.project_id = p.project_id
                INNER JOIN clients c ON p.client_id = c.client_id
                WHERE c.email = :email
                  AND d.status = 'Pending'
                ORDER BY d.submitted_at DESC
                LIMIT 5
            ");
            $stmt->execute([':email' => $userEmail]);
        } else {
            $stmt = $this->db->prepare("
                SELECT name, status, submitted_at
                FROM deliverables
                WHERE status = 'Pending'
                ORDER BY submitted_at DESC
                LIMIT 5
            ");
            $stmt->execute();
        }

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$items) {
            return "There are no pending approvals right now.";
        }

        $lines = [];
        foreach ($items as $item) {
            $lines[] = $item['name'] . ' (submitted ' . $item['submitted_at'] . ')';
        }

        return "Pending approvals: " . implode("; ", $lines) . ".";
    }

    private function getDelayedProjectsReply($userRole)
    {
        if ($userRole !== 'admin') {
            return "Detailed DSS risk insights are currently available to admin users only.";
        }

        $stmt = $this->db->prepare("
            SELECT
                p.name,
                SUM(CASE WHEN t.deadline < CURDATE() AND t.status <> 'Done' THEN 1 ELSE 0 END) AS overdue_tasks,
                DATEDIFF(p.due_date, CURDATE()) AS days_to_deadline
            FROM projects p
            LEFT JOIN tasks t ON p.project_id = t.project_id
            GROUP BY p.project_id, p.name, p.due_date
            HAVING overdue_tasks >= 3 OR days_to_deadline <= 2
            ORDER BY overdue_tasks DESC, days_to_deadline ASC
            LIMIT 5
        ");
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$projects) {
            return "No high-risk projects found right now.";
        }

        $lines = [];
        foreach ($projects as $project) {
            $lines[] = $project['name'] . ' (' . (int)$project['overdue_tasks'] . ' overdue tasks, ' . (int)$project['days_to_deadline'] . ' days left)';
        }

        return "High-risk projects: " . implode("; ", $lines) . ".";
    }

    private function getHighPriorityClientsReply($userRole)
    {
        if ($userRole !== 'admin') {
            return "Client priority insights are currently available to admin users only.";
        }

        $stmt = $this->db->prepare("
            SELECT
                c.name AS client_name,
                COUNT(DISTINCT CASE WHEN p.status <> 'Completed' THEN p.project_id ELSE NULL END) AS active_projects,
                SUM(CASE WHEN t.deadline < CURDATE() AND t.status <> 'Done' THEN 1 ELSE 0 END) AS overdue_tasks
            FROM clients c
            LEFT JOIN projects p ON c.client_id = p.client_id
            LEFT JOIN tasks t ON p.project_id = t.project_id
            GROUP BY c.client_id, c.name
            ORDER BY overdue_tasks DESC, active_projects DESC
            LIMIT 5
        ");
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$clients) {
            return "No client priority data found.";
        }

        $lines = [];
        foreach ($clients as $client) {
            $lines[] = $client['client_name'] . ' (' . (int)$client['active_projects'] . ' active projects, ' . (int)$client['overdue_tasks'] . ' overdue tasks)';
        }

        return "Top priority clients: " . implode("; ", $lines) . ".";
    }

    private function getProjectStatusReply($userRole, $userEmail)
    {
        if ($userRole === 'client') {
            $stmt = $this->db->prepare("
                SELECT p.name, p.status, p.due_date
                FROM projects p
                INNER JOIN clients c ON p.client_id = c.client_id
                WHERE c.email = :email
                ORDER BY p.due_date ASC
                LIMIT 5
            ");
            $stmt->execute([':email' => $userEmail]);
        } else {
            $stmt = $this->db->prepare("
                SELECT name, status, due_date
                FROM projects
                ORDER BY due_date ASC
                LIMIT 5
            ");
            $stmt->execute();
        }

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$projects) {
            return "No projects found.";
        }

        $lines = [];
        foreach ($projects as $project) {
            $lines[] = $project['name'] . ' (' . $project['status'] . ', due ' . $project['due_date'] . ')';
        }

        return "Current project statuses: " . implode("; ", $lines) . ".";
    }

    private function getSpecificProjectStatusReply($projectName, $userRole, $userEmail)
    {
        if ($projectName === '') {
            return "Please include a project name, for example: project status SEO Optimization";
        }

        if ($userRole === 'client') {
            $stmt = $this->db->prepare("
                SELECT p.name, p.status, p.due_date, p.service
                FROM projects p
                INNER JOIN clients c ON p.client_id = c.client_id
                WHERE c.email = :email
                  AND p.name LIKE :name
                LIMIT 1
            ");
            $stmt->execute([
                ':email' => $userEmail,
                ':name' => '%' . $projectName . '%'
            ]);
        } else {
            $stmt = $this->db->prepare("
                SELECT name, status, due_date, service
                FROM projects
                WHERE name LIKE :name
                LIMIT 1
            ");
            $stmt->execute([
                ':name' => '%' . $projectName . '%'
            ]);
        }

        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            return "I could not find a project matching \"" . $projectName . "\".";
        }

        return $project['name'] . ' is currently ' . $project['status'] . ', service type ' . $project['service'] . ', due on ' . $project['due_date'] . '.';
    }

    private function getClientsReply($userRole)
    {
        if ($userRole !== 'admin') {
            return "Client listing is currently available to admin users only.";
        }

        $stmt = $this->db->prepare("
            SELECT name, company, status
            FROM clients
            ORDER BY name ASC
            LIMIT 5
        ");
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$clients) {
            return "No clients found.";
        }

        $lines = [];
        foreach ($clients as $client) {
            $lines[] = $client['name'] . ' - ' . $client['company'] . ' (' . $client['status'] . ')';
        }

        return "Clients: " . implode("; ", $lines) . ".";
    }
}