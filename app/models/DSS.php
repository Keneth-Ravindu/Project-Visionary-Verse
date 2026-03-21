<?php

class DSS extends Model
{
    public function getProjectDelayRiskData()
    {
        $stmt = $this->db->prepare("
            SELECT
                p.project_id,
                p.name AS project_name,
                p.due_date,
                SUM(CASE WHEN t.deadline < CURDATE() AND t.status <> 'Done' THEN 1 ELSE 0 END) AS overdue_tasks,
                COUNT(t.task_id) AS total_tasks,
                DATEDIFF(p.due_date, CURDATE()) AS days_to_deadline
            FROM projects p
            LEFT JOIN tasks t ON p.project_id = t.project_id
            GROUP BY p.project_id, p.name, p.due_date
            ORDER BY p.name ASC
        ");

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $overdue = (int)($row['overdue_tasks'] ?? 0);
            $days = (int)($row['days_to_deadline'] ?? 0);

            if ($overdue >= 3 || $days <= 2) {
                $row['risk_level'] = 'High';
            } elseif ($overdue >= 1 || $days <= 7) {
                $row['risk_level'] = 'Medium';
            } else {
                $row['risk_level'] = 'Low';
            }
        }

        return $rows;
    }

    public function getClientPriorityScores()
    {
        $stmt = $this->db->prepare("
            SELECT
                c.client_id,
                c.name AS client_name,
                c.company,
                COUNT(DISTINCT CASE 
                    WHEN p.status <> 'Completed' THEN p.project_id 
                    ELSE NULL 
                END) AS active_projects,
                SUM(CASE 
                    WHEN t.priority = 'High' AND t.status <> 'Done' THEN 1 
                    ELSE 0 
                END) AS urgent_tasks,
                SUM(CASE 
                    WHEN t.deadline < CURDATE() AND t.status <> 'Done' THEN 1 
                    ELSE 0 
                END) AS overdue_tasks,
                SUM(CASE 
                    WHEN d.status = 'Pending' THEN 1 
                    ELSE 0 
                END) AS pending_deliverables
            FROM clients c
            LEFT JOIN projects p ON c.client_id = p.client_id
            LEFT JOIN tasks t ON p.project_id = t.project_id
            LEFT JOIN deliverables d ON p.project_id = d.project_id
            GROUP BY c.client_id, c.name, c.company
            ORDER BY c.name ASC
        ");

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $activeProjects = (int)$row['active_projects'];
            $urgentTasks = (int)$row['urgent_tasks'];
            $overdueTasks = (int)$row['overdue_tasks'];
            $pendingDeliverables = (int)$row['pending_deliverables'];

            // Urgency: overdue work matters most, then urgent tasks, then pending deliverables
            $urgency = min(10, ($overdueTasks * 3) + ($urgentTasks * 2) + $pendingDeliverables);

            // Value: based on active project load, minimum 3 and maximum 10
            $value = min(10, max(3, ($activeProjects * 3) + 2));

            // Final weighted score
            $score = ($urgency * 5) + ($value * 4) + ($activeProjects * 3);

            $row['urgency'] = $urgency;
            $row['value_score'] = $value;
            $row['priority_score'] = $score;

            if ($score >= 75) {
                $row['priority_level'] = 'High';
            } elseif ($score >= 45) {
                $row['priority_level'] = 'Medium';
            } else {
                $row['priority_level'] = 'Low';
            }
        }

        usort($rows, function ($a, $b) {
            return $b['priority_score'] <=> $a['priority_score'];
        });

        return $rows;
    }
}