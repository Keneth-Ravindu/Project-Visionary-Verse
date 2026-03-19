<?php

class ChatbotService extends Model
{
    public function getReply($message, $userId, $userRole, $userEmail = '')
    {
        $text = $this->normalizeMessage($message);

        if ($text === '') {
            return $this->textResponse("Please type a question.");
        }

        $intent = $this->detectIntent($text);

        switch ($intent) {
            case 'greeting':
                return $this->greetingResponse($userRole);

            case 'my_tasks':
                return $this->getMyTasksReply($userId, $userRole);

            case 'my_overdue_tasks':
                return $this->getMyOverdueTasksReply($userId, $userRole);

            case 'overdue_tasks':
                return $this->getOverdueTasksReply($userId, $userRole);

            case 'tasks_due_today':
                return $this->getTasksDueTodayReply($userId, $userRole);

            case 'pending_approvals':
                return $this->getPendingApprovalsReply($userId, $userRole, $userEmail);

            case 'approvals_summary':
                return $this->getApprovalsSummaryReply($userRole, $userEmail);

            case 'project_risk':
                return $this->getDelayedProjectsReply($userRole);

            case 'high_priority_client':
                return $this->getHighPriorityClientsReply($userRole);

            case 'project_progress':
                return $this->getProjectProgressReply($message, $userRole, $userEmail);

            case 'project_status':
                return $this->routeProjectStatusIntent($message, $userRole, $userEmail);

            case 'clients':
                return $this->getClientsReply($userRole);

            case 'admin_summary':
                return $this->getAdminSummaryReply($userRole);

            default:
                return $this->getHelpReply($userRole);
        }
    }

    private function normalizeMessage($message)
    {
        $text = strtolower(trim($message));
        $text = preg_replace('/[^\w\s]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function detectIntent($text)
    {
        if (preg_match('/\b(hi|hello|hey)\b/', $text)) {
            return 'greeting';
        }

        if (preg_match('/\b(my overdue tasks|show my overdue tasks)\b/', $text)) {
            return 'my_overdue_tasks';
        }

        if (preg_match('/\b(my tasks|show my tasks|list my tasks|assigned tasks)\b/', $text)) {
            return 'my_tasks';
        }

        if (preg_match('/\b(overdue tasks|late tasks)\b/', $text)) {
            return 'overdue_tasks';
        }

        if (preg_match('/\b(due today|tasks due today|what is due today|today tasks)\b/', $text)) {
            return 'tasks_due_today';
        }

        if (preg_match('/\b(pending approvals|approvals pending|my approvals)\b/', $text)) {
            return 'pending_approvals';
        }

        if (preg_match('/\b(approvals summary|approval summary|deliverable summary)\b/', $text)) {
            return 'approvals_summary';
        }

        if (preg_match('/\b(project risk|delayed project|delayed projects|at risk)\b/', $text)) {
            return 'project_risk';
        }

        if (preg_match('/\b(high priority client|priority client|top priority client)\b/', $text)) {
            return 'high_priority_client';
        }

        if (preg_match('/\b(project progress|progress of project|show progress)\b/', $text)) {
            return 'project_progress';
        }

        if (preg_match('/\b(project status|show projects|show project status|status of project)\b/', $text)) {
            return 'project_status';
        }

        if (preg_match('/\b(show clients|clients list|clients)\b/', $text)) {
            return 'clients';
        }

        if (preg_match('/\b(system summary|admin summary|dashboard summary|overall summary)\b/', $text)) {
            return 'admin_summary';
        }

        return 'unknown';
    }

    private function textResponse($reply, $suggestions = [])
    {
        return [
            'reply' => $reply,
            'type' => 'text',
            'items' => [],
            'suggestions' => $suggestions
        ];
    }

    private function listResponse($reply, $items = [], $suggestions = [])
    {
        return [
            'reply' => $reply,
            'type' => 'list',
            'items' => $items,
            'suggestions' => $suggestions
        ];
    }

    private function greetingResponse($userRole)
    {
        return $this->textResponse(
            "Hello! I can help with tasks, project status, project progress, approvals, risks, and summaries.",
            $this->getSuggestionsByRole($userRole)
        );
    }

    private function getMyTasksReply($userId, $userRole)
    {
        if ($userRole === 'client') {
            return $this->textResponse("Clients do not have assigned internal tasks in the current system.");
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
            return $this->textResponse("No tasks found.");
        }

        $items = [];
        foreach ($tasks as $task) {
            $items[] = $task['name'] . ' | ' . $task['status'] . ' | Due: ' . $task['deadline'];
        }

        return $this->listResponse("Here are the latest tasks:", $items);
    }

    private function getMyOverdueTasksReply($userId, $userRole)
    {
        if ($userRole === 'client') {
            return $this->textResponse("Clients do not have internal assigned tasks.");
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
            return $this->textResponse("No overdue tasks found.");
        }

        $items = [];
        foreach ($tasks as $task) {
            $items[] = $task['name'] . ' | Due: ' . $task['deadline'];
        }

        return $this->listResponse("These tasks are overdue:", $items);
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
            return $this->textResponse("Clients do not have access to internal overdue task counts.");
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

        return $this->textResponse("There are currently {$count} overdue tasks.");
    }

    private function getTasksDueTodayReply($userId, $userRole)
    {
        if ($userRole === 'client') {
            return $this->textResponse("Clients do not have access to internal task deadlines.");
        }

        if ($userRole === 'staff') {
            $stmt = $this->db->prepare("
                SELECT name, status
                FROM tasks
                WHERE assignee_id = :user_id
                  AND deadline = CURDATE()
                ORDER BY name ASC
                LIMIT 10
            ");
            $stmt->execute([':user_id' => $userId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT name, status
                FROM tasks
                WHERE deadline = CURDATE()
                ORDER BY name ASC
                LIMIT 10
            ");
            $stmt->execute();
        }

        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$tasks) {
            return $this->textResponse("There are no tasks due today.");
        }

        $items = [];
        foreach ($tasks as $task) {
            $items[] = $task['name'] . ' | ' . $task['status'];
        }

        return $this->listResponse("Here are the tasks due today:", $items);
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
            return $this->textResponse("There are no pending approvals right now.");
        }

        $lines = [];
        foreach ($items as $item) {
            $lines[] = $item['name'] . ' | Submitted: ' . $item['submitted_at'];
        }

        return $this->listResponse("Pending approvals:", $lines);
    }

    private function getApprovalsSummaryReply($userRole, $userEmail)
    {
        if ($userRole === 'client') {
            $stmt = $this->db->prepare("
                SELECT d.status, COUNT(*) AS total
                FROM deliverables d
                INNER JOIN projects p ON d.project_id = p.project_id
                INNER JOIN clients c ON p.client_id = c.client_id
                WHERE c.email = :email
                GROUP BY d.status
                ORDER BY total DESC
            ");
            $stmt->execute([':email' => $userEmail]);
        } else {
            $stmt = $this->db->prepare("
                SELECT status, COUNT(*) AS total
                FROM deliverables
                GROUP BY status
                ORDER BY total DESC
            ");
            $stmt->execute();
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return $this->textResponse("No approval summary data found.");
        }

        $items = [];
        foreach ($rows as $row) {
            $items[] = $row['status'] . ': ' . $row['total'];
        }

        return $this->listResponse("Here is the approvals summary:", $items);
    }

    private function getDelayedProjectsReply($userRole)
    {
        if ($userRole !== 'admin') {
            return $this->textResponse("Detailed DSS risk insights are currently available to admin users only.");
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
            return $this->textResponse("No high-risk projects found right now.");
        }

        $items = [];
        foreach ($projects as $project) {
            $items[] = $project['name'] . ' | Overdue tasks: ' . (int)$project['overdue_tasks'] . ' | Days left: ' . (int)$project['days_to_deadline'];
        }

        return $this->listResponse("High-risk projects:", $items);
    }

    private function getHighPriorityClientsReply($userRole)
    {
        if ($userRole !== 'admin') {
            return $this->textResponse("Client priority insights are currently available to admin users only.");
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
            return $this->textResponse("No client priority data found.");
        }

        $items = [];
        foreach ($clients as $client) {
            $items[] = $client['client_name'] . ' | Active projects: ' . (int)$client['active_projects'] . ' | Overdue tasks: ' . (int)$client['overdue_tasks'];
        }

        return $this->listResponse("Top priority clients:", $items);
    }

    private function routeProjectStatusIntent($message, $userRole, $userEmail)
    {
        $projectName = $this->extractProjectName($message, 'project status');

        if ($projectName !== '') {
            return $this->getSpecificProjectStatusReply($projectName, $userRole, $userEmail);
        }

        return $this->getProjectStatusReply($userRole, $userEmail);
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
            return $this->textResponse("No projects found.");
        }

        $items = [];
        foreach ($projects as $project) {
            $items[] = $project['name'] . ' | ' . $project['status'] . ' | Due: ' . $project['due_date'];
        }

        return $this->listResponse("Current project statuses:", $items);
    }

    private function getSpecificProjectStatusReply($projectName, $userRole, $userEmail)
    {
        if ($projectName === '') {
            return $this->textResponse('Please include a project name, for example: project status SEO Optimization');
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
            return $this->textResponse('I could not find a project matching "' . $projectName . '".');
        }

        return $this->textResponse(
            $project['name'] . ' is currently ' . $project['status'] . ', service type ' . $project['service'] . ', due on ' . $project['due_date'] . '.'
        );
    }

    private function getProjectProgressReply($message, $userRole, $userEmail)
    {
        $projectName = $this->extractProjectName($message, 'project progress');

        if ($userRole === 'client') {
            $sql = "
                SELECT 
                    p.project_id,
                    p.name,
                    COUNT(t.task_id) AS total_tasks,
                    SUM(CASE WHEN t.status = 'Done' THEN 1 ELSE 0 END) AS completed_tasks
                FROM projects p
                INNER JOIN clients c ON p.client_id = c.client_id
                LEFT JOIN tasks t ON p.project_id = t.project_id
                WHERE c.email = :email
            ";
            $params = [':email' => $userEmail];
        } else {
            $sql = "
                SELECT 
                    p.project_id,
                    p.name,
                    COUNT(t.task_id) AS total_tasks,
                    SUM(CASE WHEN t.status = 'Done' THEN 1 ELSE 0 END) AS completed_tasks
                FROM projects p
                LEFT JOIN tasks t ON p.project_id = t.project_id
                WHERE 1=1
            ";
            $params = [];
        }

        if ($projectName !== '') {
            $sql .= " AND p.name LIKE :project_name";
            $params[':project_name'] = '%' . $projectName . '%';
        }

        $sql .= " GROUP BY p.project_id, p.name ORDER BY p.name ASC LIMIT 5";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$projects) {
            return $this->textResponse("No project progress data found.");
        }

        $items = [];
        foreach ($projects as $project) {
            $total = (int)$project['total_tasks'];
            $completed = (int)$project['completed_tasks'];
            $progress = $total > 0 ? round(($completed / $total) * 100) : 0;

            $items[] = $project['name'] . ' | Progress: ' . $progress . '% (' . $completed . '/' . $total . ' tasks)';
        }

        return $this->listResponse("Project progress summary:", $items);
    }

    private function getAdminSummaryReply($userRole)
    {
        if ($userRole !== 'admin') {
            return $this->textResponse("This summary is currently available to admin users only.");
        }

        $projects = (int)$this->scalar("SELECT COUNT(*) FROM projects");
        $overdueTasks = (int)$this->scalar("SELECT COUNT(*) FROM tasks WHERE deadline < CURDATE() AND status <> 'Done'");
        $pendingApprovals = (int)$this->scalar("SELECT COUNT(*) FROM deliverables WHERE status = 'Pending'");
        $clients = (int)$this->scalar("SELECT COUNT(*) FROM clients");

        $items = [
            'Total projects: ' . $projects,
            'Total clients: ' . $clients,
            'Overdue tasks: ' . $overdueTasks,
            'Pending approvals: ' . $pendingApprovals
        ];

        return $this->listResponse("System summary:", $items);
    }

    private function getClientsReply($userRole)
    {
        if ($userRole !== 'admin') {
            return $this->textResponse("Client listing is currently available to admin users only.");
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
            return $this->textResponse("No clients found.");
        }

        $items = [];
        foreach ($clients as $client) {
            $items[] = $client['name'] . ' - ' . $client['company'] . ' (' . $client['status'] . ')';
        }

        return $this->listResponse("Clients:", $items);
    }

    private function getHelpReply($userRole)
    {
        return $this->textResponse(
            "I can help with tasks, overdue work, project risk, project progress, approvals, project status, and summaries.",
            $this->getSuggestionsByRole($userRole)
        );
    }

    private function getSuggestionsByRole($role)
    {
        if ($role === 'admin') {
            return [
                'Overall system summary',
                'Show project status',
                'Project progress',
                'Project risk',
                'High priority client'
            ];
        }

        if ($role === 'staff') {
            return [
                'Show my tasks',
                'Show my overdue tasks',
                'What is due today?',
                'Project progress',
                'Project status'
            ];
        }

        return [
            'Project status',
            'Project progress',
            'Pending approvals',
            'Approvals summary'
        ];
    }

    private function extractProjectName($message, $prefix)
    {
        $message = trim($message);
        $lower = strtolower($message);
        $prefixLower = strtolower($prefix);

        if (strpos($lower, $prefixLower) === 0) {
            return trim(substr($message, strlen($prefix)));
        }

        return '';
    }

    private function scalar($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}