<?php
$title = 'Admin Dashboard';
$pageScript = '';
require_once "../app/views/layouts/header.php";

require_once "../app/models/Client.php";
require_once "../app/models/Project.php";
require_once "../app/models/Task.php";
require_once "../app/models/Deliverable.php";
require_once "../app/models/DSS.php";

$clientModel = new Client();
$projectModel = new Project();
$taskModel = new Task();
$deliverableModel = new Deliverable();
$dssModel = new DSS();

$clients = $clientModel->getAllClients();
$projects = $projectModel->getAllProjects();
$tasks = $taskModel->getAllTasks();
$deliverables = $deliverableModel->getAllDeliverables();
$riskData = $dssModel->getProjectDelayRiskData();
$priorityData = $dssModel->getClientPriorityScores();

$activeClients = 0;
foreach ($clients as $client) {
    if (strtolower($client['status'] ?? '') === 'active') {
        $activeClients++;
    }
}

$ongoingProjects = 0;
foreach ($projects as $project) {
    if (strtolower($project['status'] ?? '') !== 'completed') {
        $ongoingProjects++;
    }
}

$openTasks = 0;
$highPriorityTasks = 0;
foreach ($tasks as $task) {
    if (strtolower($task['status'] ?? '') !== 'done') {
        $openTasks++;
    }
    if (strtolower($task['priority'] ?? '') === 'high' && strtolower($task['status'] ?? '') !== 'done') {
        $highPriorityTasks++;
    }
}

$pendingApprovals = 0;
foreach ($deliverables as $deliverable) {
    if (strtolower($deliverable['status'] ?? '') === 'pending') {
        $pendingApprovals++;
    }
}

$recentProjects = array_slice($projects, 0, 3);
$teamWorkload = array_slice($tasks, 0, 10);
?>

<?php
$pageTitle = 'Dashboard';
$activeTab = 'dashboard';
$userRole = $_SESSION['user_role'] ?? 'admin';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
    <h1 class="h1">Dashboard</h1>
    <p class="sub">Welcome back, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'] ?? 'Admin')[0]) ?>.</p>

    <div class="grid grid-4">
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Active Clients</div>
                <div class="stat-icon stat-icon-clients">👥</div>
            </div>
            <div class="value"><?= $activeClients ?></div>
            <div class="hint">Live from database</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Ongoing Projects</div>
                <div class="stat-icon stat-icon-projects">📁</div>
            </div>
            <div class="value"><?= $ongoingProjects ?></div>
            <div class="hint">Not yet completed</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Open Tasks</div>
                <div class="stat-icon stat-icon-tasks">☑</div>
            </div>
            <div class="value"><?= $openTasks ?></div>
            <div class="hint"><?= $highPriorityTasks ?> high priority</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Pending Approvals</div>
                <div class="stat-icon stat-icon-approvals">📋</div>
            </div>
            <div class="value"><?= $pendingApprovals ?></div>
            <div class="hint">Awaiting review</div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Recent Projects</h3>
                    <p class="card-desc">Quick status snapshot</p>
                </div>
                <button class="btn btn-outline" onclick="location.href='/pvv/public/project/index'">View</button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Due</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentProjects)): ?>
                        <?php foreach ($recentProjects as $project): ?>
                            <?php
                            $status = strtolower($project['status'] ?? '');
                            $statusClass = 'info';
                            if ($status === 'review') {
                                $statusClass = 'warn';
                            } elseif ($status === 'in progress' || $status === 'completed') {
                                $statusClass = 'good';
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($project['name']) ?></td>
                                <td><?= htmlspecialchars($project['client_company'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="chip <?= $statusClass ?>">
                                        <span class="dot"></span><?= htmlspecialchars($project['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($project['due_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No projects found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card soft">
            <div class="card-head">
                <div>
                    <h3 class="card-title">DSS Alerts</h3>
                    <p class="card-desc">Delay risk + priority insights</p>
                </div>
                <button class="btn" onclick="location.href='/pvv/public/dss/index'">Open DSS</button>
            </div>

            <div class="grid" style="margin-top:0">
                <?php if (!empty($riskData)): ?>
                    <?php foreach (array_slice($riskData, 0, 2) as $risk): ?>
                        <?php
                        $riskClass = 'info';
                        if (($risk['risk_level'] ?? '') === 'High') {
                            $riskClass = 'danger';
                        } elseif (($risk['risk_level'] ?? '') === 'Medium') {
                            $riskClass = 'warn';
                        }
                        ?>
                        <div class="callout <?= $riskClass ?>">
                            <strong><?= htmlspecialchars($risk['risk_level']) ?> Delay Risk</strong>
                            <?= htmlspecialchars($risk['project_name']) ?> has <?= (int)$risk['overdue_tasks'] ?> overdue tasks and <?= (int)$risk['days_to_deadline'] ?> days left.
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty($priorityData)): ?>
                    <div class="callout info">
                        <strong>Client Priority Score</strong>
                        <?= htmlspecialchars($priorityData[0]['client_name'] ?? 'N/A') ?>: <?= (int)($priorityData[0]['priority_score'] ?? 0) ?>/100
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Task Snapshot</h3>
                <p class="card-desc">Recent task activity</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Assigned To</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($teamWorkload)): ?>
                    <?php foreach ($teamWorkload as $task): ?>
                        <?php
                            $priority = strtolower($task['priority'] ?? '');
                            $priorityClass = '';
                            if ($priority === 'high') {
                                $priorityClass = 'danger';
                            } elseif ($priority === 'medium') {
                                $priorityClass = 'warn';
                            } elseif ($priority === 'low') {
                                $priorityClass = 'good';
                            }

                            $status = strtolower($task['status'] ?? '');
                            $statusClass = 'info';
                            if ($status === 'done' || $status === 'completed') {
                                $statusClass = 'good';
                            } elseif ($status === 'review' || $status === 'pending') {
                                $statusClass = 'warn';
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($task['name']) ?></td>
                            <td><?= htmlspecialchars($task['assignee_name'] ?? 'Unassigned') ?></td>
                            <td><span class="chip <?= $priorityClass ?>"><span class="dot"></span><?= htmlspecialchars($task['priority']) ?></span></td>
                            <td><span class="chip <?= $statusClass ?>"><span class="dot"></span><?= htmlspecialchars($task['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No tasks found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">© Visionary Verse — Admin Dashboard</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>
<?php require_once "../app/views/layouts/footer.php"; ?>