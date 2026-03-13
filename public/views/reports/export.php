<?php
$project = $reportData['project'] ?? [];
$taskStats = $reportData['taskStats'] ?? [];
$deliverableStats = $reportData['deliverableStats'] ?? [];
$teamWorkload = $reportData['teamWorkload'] ?? [];
$progress = $reportData['progress'] ?? 0;

$totalTasks = (int)($taskStats['total_tasks'] ?? 0);
$doneTasks = (int)($taskStats['tasks_done'] ?? 0);
$remainingTasks = max(0, $totalTasks - $doneTasks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report - <?= htmlspecialchars($project['name'] ?? 'Report') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; color: #111; }
        h1, h2, h3 { margin-bottom: 8px; }
        .meta, .box { margin-bottom: 20px; }
        .box { border: 1px solid #ccc; padding: 16px; border-radius: 8px; }
        .progress-wrap { width: 100%; background: #eee; height: 16px; border-radius: 8px; overflow: hidden; }
        .progress-bar { height: 16px; background: #444; width: <?= $progress ?>%; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .top-actions { margin-bottom: 20px; }
        @media print {
            .top-actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="top-actions">
        <button onclick="window.print()">Save / Download PDF</button>
    </div>

    <h1>Visionary Verse Advanced Project Report</h1>
    <div class="meta">
        <strong>Project:</strong> <?= htmlspecialchars($project['name'] ?? 'N/A') ?><br>
        <strong>Client:</strong> <?= htmlspecialchars($project['client_name'] ?? 'N/A') ?><br>
        <strong>Company:</strong> <?= htmlspecialchars($project['client_company'] ?? 'N/A') ?><br>
        <strong>Service:</strong> <?= htmlspecialchars($project['service'] ?? 'N/A') ?><br>
        <strong>Status:</strong> <?= htmlspecialchars($project['status'] ?? 'N/A') ?><br>
        <strong>Due Date:</strong> <?= htmlspecialchars($project['due_date'] ?? 'N/A') ?><br>
        <strong>Generated On:</strong> <?= date('Y-m-d H:i:s') ?>
    </div>

    <div class="box">
        <h2>Overall Progress</h2>
        <div class="progress-wrap">
            <div class="progress-bar"></div>
        </div>
        <p><?= $progress ?>% complete</p>
        <p>Tasks done: <?= $doneTasks ?> | Tasks remaining: <?= $remainingTasks ?></p>
    </div>

    <div class="box">
        <h2>Task Completion Overview</h2>
        <table>
            <tr><th>Status</th><th>Count</th></tr>
            <tr><td>To Do</td><td><?= (int)($taskStats['tasks_todo'] ?? 0) ?></td></tr>
            <tr><td>In Progress</td><td><?= (int)($taskStats['tasks_in_progress'] ?? 0) ?></td></tr>
            <tr><td>Review</td><td><?= (int)($taskStats['tasks_review'] ?? 0) ?></td></tr>
            <tr><td>Done</td><td><?= (int)($taskStats['tasks_done'] ?? 0) ?></td></tr>
        </table>
    </div>

    <div class="box">
        <h2>Approval Summary</h2>
        <table>
            <tr><th>Metric</th><th>Count</th></tr>
            <tr><td>Pending Deliverables</td><td><?= (int)($deliverableStats['pending_deliverables'] ?? 0) ?></td></tr>
            <tr><td>Approved Deliverables</td><td><?= (int)($deliverableStats['approved_deliverables'] ?? 0) ?></td></tr>
            <tr><td>Changes Requested</td><td><?= (int)($deliverableStats['changes_requested'] ?? 0) ?></td></tr>
            <tr><td>Total Deliverables</td><td><?= (int)($deliverableStats['total_deliverables'] ?? 0) ?></td></tr>
        </table>
    </div>

    <div class="box">
        <h2>Team Workload</h2>
        <table>
            <tr><th>Member</th><th>Assigned</th><th>High Priority</th><th>Overdue</th></tr>
            <?php if (!empty($teamWorkload)): ?>
                <?php foreach ($teamWorkload as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member['full_name'] ?? 'Unassigned') ?></td>
                        <td><?= (int)$member['assigned_tasks'] ?></td>
                        <td><?= (int)$member['high_priority_count'] ?></td>
                        <td><?= (int)$member['overdue_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No workload data found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>