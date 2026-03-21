<?php
$title = 'Staff Dashboard';
$pageScript = '';
require_once "../app/views/layouts/header.php";

require_once "../app/models/Task.php";
require_once "../app/models/Deliverable.php";

$taskModel = new Task();
$deliverableModel = new Deliverable();

$currentUserId = $_SESSION['user_id'] ?? 0;

$allTasks = $taskModel->getAllTasks();
$allDeliverables = $deliverableModel->getAllDeliverables();

$myTasks = [];
foreach ($allTasks as $task) {
    if ((int)($task['assignee_id'] ?? 0) === (int)$currentUserId) {
        $myTasks[] = $task;
    }
}

$myOpenTasks = 0;
$dueThisWeek = 0;
$inReview = 0;
$completed = 0;
$highPriority = 0;

$today = date('Y-m-d');
$weekAhead = date('Y-m-d', strtotime('+7 days'));

foreach ($myTasks as $task) {
    $status = strtolower($task['status'] ?? '');
    $priority = strtolower($task['priority'] ?? '');
    $deadline = $task['deadline'] ?? '';

    if ($status !== 'done') {
        $myOpenTasks++;
    }
    if ($priority === 'high' && $status !== 'done') {
        $highPriority++;
    }
    if ($deadline >= $today && $deadline <= $weekAhead && $status !== 'done') {
        $dueThisWeek++;
    }
    if ($status === 'review') {
        $inReview++;
    }
    if ($status === 'done') {
        $completed++;
    }
}

$focusTasks = array_slice($myTasks, 0, 5);

$myUploads = [];
foreach ($allDeliverables as $deliverable) {
    if ((int)($deliverable['uploaded_by'] ?? 0) === (int)$currentUserId) {
        $myUploads[] = $deliverable;
    }
}
?>

<?php
$pageTitle = 'Dashboard';
$activeTab = 'dashboard';
$userRole = $_SESSION['user_role'] ?? 'staff';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
    <h1 class="h1">Dashboard</h1>
    <p class="sub">Welcome back, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'] ?? 'Staff')[0]) ?>.</p>

    <div class="grid grid-4">
        <div class="card stat">
            <div class="stat-head">
                <div class="label">My Open Tasks</div>
                <div class="stat-icon stat-icon-tasks">☑</div>
            </div>
            <div class="value"><?= $myOpenTasks ?></div>
            <div class="hint"><?= $highPriority ?> high priority</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Due This Week</div>
                <div class="stat-icon stat-icon-progress">📅</div>
            </div>
            <div class="value"><?= $dueThisWeek ?></div>
            <div class="hint">Upcoming deadlines</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">In Review</div>
                <div class="stat-icon stat-icon-review">📝</div>
            </div>
            <div class="value"><?= $inReview ?></div>
            <div class="hint">Waiting client/admin</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="label">Completed</div>
                <div class="stat-icon stat-icon-done">✔</div>
            </div>
            <div class="value"><?= $completed ?></div>
            <div class="hint">Finished tasks</div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Today’s Focus</h3>
                    <p class="card-desc">Update status to keep workflow moving</p>
                </div>
                <button class="btn" onclick="location.href='/pvv/public/task/index'">Open Tasks</button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Project</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($focusTasks)): ?>
                        <?php foreach ($focusTasks as $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['name']) ?></td>
                                <td><?= htmlspecialchars($task['project_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $priority = strtolower($task['priority'] ?? '');
                                    $priorityClass = '';
                                    if ($priority === 'high') {
                                        $priorityClass = 'danger';
                                    } elseif ($priority === 'medium') {
                                        $priorityClass = 'warn';
                                    }
                                    ?>
                                    <span class="chip <?= $priorityClass ?>"><span class="dot"></span><?= htmlspecialchars($task['priority']) ?></span>
                                </td>
                                <td>
                                    <?php
                                    $status = strtolower($task['status'] ?? '');
                                    $statusClass = 'info';
                                    if ($status === 'done') {
                                        $statusClass = 'good';
                                    } elseif ($status === 'review') {
                                        $statusClass = 'warn';
                                    } elseif ($status === 'in progress') {
                                        $statusClass = 'good';
                                    }
                                    ?>
                                    <span class="chip <?= $statusClass ?>"><span class="dot"></span><?= htmlspecialchars($task['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No assigned tasks found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card soft">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Quick Actions</h3>
                    <p class="card-desc">Fast shortcuts</p>
                </div>
            </div>

            <div class="grid" style="margin-top:0">
                <button class="btn" onclick="location.href='/pvv/public/task/index'">+ Create / Update Tasks</button>
                <button class="btn btn-outline" onclick="location.href='/pvv/public/approval/index'">Upload Deliverable</button>
                <button class="btn btn-outline" onclick="showToast('Reminder','Keep task statuses updated to maintain workflow visibility.')">Reminder</button>

                <div class="callout info">
                    <strong>My Uploads</strong>
                    <?php if (!empty($myUploads)): ?>
                        Latest upload: <?= htmlspecialchars($myUploads[0]['name']) ?> (<?= htmlspecialchars($myUploads[0]['status']) ?>)
                    <?php else: ?>
                        No deliverables uploaded yet.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">© Visionary Verse — Staff Dashboard</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>
<?php require_once "../app/views/layouts/footer.php"; ?>