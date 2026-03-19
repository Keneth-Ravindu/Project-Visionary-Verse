<?php
$title = 'Reports';
$pageScript = 'reports.js';
require_once "../app/views/layouts/header.php";

$project = $reportData['project'] ?? null;
$taskStats = $reportData['taskStats'] ?? [];
$deliverableStats = $reportData['deliverableStats'] ?? [];
$teamWorkload = $reportData['teamWorkload'] ?? [];
$progress = $reportData['progress'] ?? 0;

$totalTasks = (int)($taskStats['total_tasks'] ?? 0);
$doneTasks = (int)($taskStats['tasks_done'] ?? 0);
$remainingTasks = max(0, $totalTasks - $doneTasks);
?>

<?php
$pageTitle = 'Reports';
$activeTab = 'reports';
$userRole = $_SESSION['user_role'] ?? 'admin';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
  <div class="input-row" style="justify-content:space-between; flex-wrap:wrap">
    <div>
      <h1 class="h1">Advanced Reports</h1>
      <p class="sub">Analyze project delivery, approvals, and team workload performance.</p>
    </div>

        <div class="input-row" style="flex-wrap:wrap">
        <form method="GET" action="/pvv/public/report/index" class="input-row" style="flex-wrap:wrap" id="reportForm">
            <select class="input" id="reportProject" name="projectId" style="width:260px">
            <?php foreach ($projects as $p): ?>
                <option value="<?= $p['project_id'] ?>" <?= ((string)$selectedProjectId === (string)$p['project_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['name']) ?>
                </option>
            <?php endforeach; ?>
            </select>
            <button class="btn" type="submit" id="generateReport">Generate Report</button>
        </form>

        <a class="btn btn-outline" id="downloadPdfBtn" href="/pvv/public/report/export/<?= (int)$selectedProjectId ?>" target="_blank">Download PDF</a>
        </div>
  </div>

  <div class="grid grid-4">
    <div class="card stat">
      <div class="label">Active Clients</div>
      <div class="value"><?= (int)$summary['active_clients'] ?></div>
      <div class="hint">Current active records</div>
    </div>
    <div class="card stat">
      <div class="label">Total Projects</div>
      <div class="value"><?= (int)$summary['total_projects'] ?></div>
      <div class="hint">Across the system</div>
    </div>
    <div class="card stat">
      <div class="label">Completed Tasks</div>
      <div class="value"><?= (int)$summary['completed_tasks'] ?></div>
      <div class="hint">Finished task count</div>
    </div>
    <div class="card stat">
      <div class="label">Pending Approvals</div>
      <div class="value"><?= (int)$summary['pending_approvals'] ?></div>
      <div class="hint">Waiting on review</div>
    </div>
  </div>

  <?php if ($project): ?>
    <div class="grid grid-2">
      <div class="card">
        <div class="card-head">
          <div>
            <h3 class="card-title">Project Progress</h3>
            <p class="card-desc"><?= htmlspecialchars($project['name']) ?> — <?= htmlspecialchars($project['client_company'] ?? 'N/A') ?></p>
          </div>
          <span class="chip info"><span class="dot"></span>Live</span>
        </div>

        <div class="callout">
          <strong>Overall completion</strong>
          <div class="progress"><div id="progressBar" style="width:<?= $progress ?>%"></div></div>
          <p class="sub" id="progressText"><?= $progress ?>% complete</p>
        </div>

        <div class="grid grid-2">
          <div class="callout info"><strong>Tasks done</strong><p id="doneCount"><?= $doneTasks ?></p></div>
          <div class="callout warn"><strong>Tasks remaining</strong><p id="remainCount"><?= $remainingTasks ?></p></div>
        </div>

        <div class="callout info" style="margin-top:12px">
          <strong>Project Details</strong>
          <p>
            Client: <?= htmlspecialchars($project['client_name'] ?? 'N/A') ?><br>
            Service: <?= htmlspecialchars($project['service'] ?? 'N/A') ?><br>
            Status: <?= htmlspecialchars($project['status'] ?? 'N/A') ?><br>
            Due Date: <?= htmlspecialchars($project['due_date'] ?? 'N/A') ?>
          </p>
        </div>
      </div>

      <div class="card soft">
        <div class="card-head">
          <div>
            <h3 class="card-title">Task Completion Overview</h3>
            <p class="card-desc">Live by status</p>
          </div>
        </div>

        <table class="table">
          <thead><tr><th>Status</th><th>Count</th></tr></thead>
          <tbody id="statusTable">
            <tr><td><span class="chip info"><span class="dot"></span>To Do</span></td><td><?= (int)($taskStats['tasks_todo'] ?? 0) ?></td></tr>
            <tr><td><span class="chip good"><span class="dot"></span>In Progress</span></td><td><?= (int)($taskStats['tasks_in_progress'] ?? 0) ?></td></tr>
            <tr><td><span class="chip warn"><span class="dot"></span>Review</span></td><td><?= (int)($taskStats['tasks_review'] ?? 0) ?></td></tr>
            <tr><td><span class="chip good"><span class="dot"></span>Done</span></td><td><?= (int)($taskStats['tasks_done'] ?? 0) ?></td></tr>
          </tbody>
        </table>

        <div class="callout info" style="margin-top:12px">
          <strong>Risk Indicators</strong>
          <p>
            Overdue Tasks: <?= (int)($taskStats['overdue_tasks'] ?? 0) ?><br>
            High Priority Tasks: <?= (int)($taskStats['high_priority_tasks'] ?? 0) ?><br>
            Total Tasks: <?= (int)($taskStats['total_tasks'] ?? 0) ?>
          </p>
        </div>
      </div>
    </div>

    <div class="grid grid-2">
      <div class="card">
        <div class="card-head">
          <div>
            <h3 class="card-title">Approval Turnaround</h3>
            <p class="card-desc">Deliverable review summary</p>
          </div>
        </div>

        <table class="table">
          <thead><tr><th>Metric</th><th>Count</th></tr></thead>
          <tbody>
            <tr><td>Pending Deliverables</td><td><?= (int)($deliverableStats['pending_deliverables'] ?? 0) ?></td></tr>
            <tr><td>Approved Deliverables</td><td><?= (int)($deliverableStats['approved_deliverables'] ?? 0) ?></td></tr>
            <tr><td>Changes Requested</td><td><?= (int)($deliverableStats['changes_requested'] ?? 0) ?></td></tr>
            <tr><td>Total Deliverables</td><td><?= (int)($deliverableStats['total_deliverables'] ?? 0) ?></td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <div class="card-head">
          <div>
            <h3 class="card-title">Team Workload</h3>
            <p class="card-desc">Assigned task summary</p>
          </div>
        </div>

        <table class="table">
          <thead><tr><th>Member</th><th>Assigned</th><th>High Priority</th><th>Overdue</th></tr></thead>
          <tbody>
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
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

  <div class="footer">© Visionary Verse — Reports</div>
</div>

<?php require_once "../app/views/layouts/footer.php"; ?>