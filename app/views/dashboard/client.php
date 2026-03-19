<?php
$title = 'Client Dashboard';
$pageScript = '';
require_once "../app/views/layouts/header.php";

require_once "../app/models/Project.php";
require_once "../app/models/Deliverable.php";
require_once "../app/models/Task.php";

$projectModel = new Project();
$deliverableModel = new Deliverable();
$taskModel = new Task();

$currentUserEmail = $_SESSION['user_email'] ?? '';

$projects = $projectModel->getProjectsByClientEmail($currentUserEmail);
$deliverables = $deliverableModel->getAllDeliverables();
$tasks = $taskModel->getAllTasks();

$projectIds = [];
foreach ($projects as $project) {
    $projectIds[] = (int)$project['project_id'];
}

$activeProjects = count($projects);

$pendingApprovals = 0;
$totalTasks = 0;
$doneTasks = 0;

$clientDeliverables = [];
foreach ($deliverables as $deliverable) {
    if (in_array((int)$deliverable['project_id'], $projectIds, true)) {
        $clientDeliverables[] = $deliverable;

        if (strtolower($deliverable['status'] ?? '') === 'pending') {
            $pendingApprovals++;
        }
    }
}

foreach ($tasks as $task) {
    if (in_array((int)$task['project_id'], $projectIds, true)) {
        $totalTasks++;
        if (strtolower($task['status'] ?? '') === 'done') {
            $doneTasks++;
        }
    }
}

$overallProgress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
$recentProjects = array_slice($projects, 0, 5);
$latestPendingApproval = null;

foreach ($clientDeliverables as $deliverable) {
    if (strtolower($deliverable['status'] ?? '') === 'pending') {
        $latestPendingApproval = $deliverable;
        break;
    }
}
?>

<?php
$pageTitle = 'Dashboard';
$activeTab = 'dashboard';
$userRole = $_SESSION['user_role'] ?? 'client';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
    <h1 class="h1">Dashboard</h1>
    <p class="sub">Welcome back, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'] ?? 'Client')[0]) ?>.</p>

    <div class="grid grid-4">
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Active Projects</div>
                <div class="stat-icon stat-icon-projects">📁</div>
            </div>
            <div class="value"><?= $activeProjects ?></div>
            <div class="hint">Linked to your account</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Pending Approvals</div>
                <div class="stat-icon stat-icon-approvals">📋</div>
            </div>
            <div class="value"><?= $pendingApprovals ?></div>
            <div class="hint">Awaiting your review</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Tasks Completed</div>
                <div class="stat-icon stat-icon-done">✔</div>
            </div>
            <div class="value"><?= $doneTasks ?></div>
            <div class="hint">Across your projects</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Overall Progress</div>
                <div class="stat-icon stat-icon-progress">📈</div>
            </div>
            <div class="value"><?= $overallProgress ?>%</div>
            <div class="hint">Based on completed tasks</div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Project Progress</h3>
                    <p class="card-desc">Live project overview</p>
                </div>
                <button class="btn btn-outline" onclick="location.href='/pvv/public/project/index'">View all</button>
            </div>

            <div class="grid" style="margin-top:0">
                <?php if (!empty($recentProjects)): ?>
                    <?php foreach ($recentProjects as $project): ?>
                        <?php
                        $projectTaskTotal = 0;
                        $projectTaskDone = 0;

                        foreach ($tasks as $task) {
                            if ((int)$task['project_id'] === (int)$project['project_id']) {
                                $projectTaskTotal++;
                                if (strtolower($task['status'] ?? '') === 'done') {
                                    $projectTaskDone++;
                                }
                            }
                        }

                        $progress = $projectTaskTotal > 0 ? round(($projectTaskDone / $projectTaskTotal) * 100) : 0;
                        ?>
                        <div class="callout">
                            <strong><?= htmlspecialchars($project['name']) ?></strong>
                            <div class="progress"><div style="width:<?= $progress ?>%"></div></div>
                            <p class="sub"><?= $progress ?>% complete</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="callout info">
                        <strong>No Projects Found</strong>
                        <p class="sub">No projects are currently linked to your account.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card soft">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Pending Approval</h3>
                    <p class="card-desc">Review current deliverables</p>
                </div>
                <button class="btn" onclick="location.href='/pvv/public/approval/index'">Open</button>
            </div>

            <?php if ($latestPendingApproval): ?>
                <div class="callout info">
                    <strong><?= htmlspecialchars($latestPendingApproval['name']) ?></strong>
                    Uploaded: <?= htmlspecialchars($latestPendingApproval['submitted_at']) ?><br>
                    Status: <?= htmlspecialchars($latestPendingApproval['status']) ?>
                    <div class="actions" style="margin-top:10px">
                        <button class="btn" onclick="location.href='/pvv/public/approval/index'">Review Now</button>
                    </div>
                </div>
            <?php else: ?>
                <div class="callout good">
                    <strong>No Pending Approvals</strong>
                    <p class="sub">You have reviewed all current deliverables.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">© Visionary Verse — Client Dashboard</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>
<?php require_once "../app/views/layouts/footer.php"; ?>