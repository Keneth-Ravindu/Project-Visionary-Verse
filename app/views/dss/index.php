<?php
$title = 'DSS';
$pageScript = 'dss.js';
require_once "../app/views/layouts/header.php";
?>

<?php
$pageTitle = 'DSS';
$activeTab = 'dss';
$userRole = $_SESSION['user_role'] ?? 'admin';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
  <h1 class="h1">Decision Support System (DSS)</h1>
  <p class="sub">Project Delay Risk Indicator + Client Priority Score using live system data.</p>

  <div class="grid grid-2">
    <div class="card">
      <div class="card-head">
        <div>
          <h3 class="card-title">Project Delay Risk Indicator</h3>
          <p class="card-desc">Based on overdue tasks and deadline proximity</p>
        </div>
        <button class="btn btn-outline" id="recalcRisk" onclick="location.reload()">Recalculate</button>
      </div>

      <table class="table" id="riskTable">
        <thead>
          <tr>
            <th>Project</th>
            <th>Overdue Tasks</th>
            <th>Days to Deadline</th>
            <th>Risk</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($riskData)): ?>
            <?php foreach ($riskData as $row): ?>
              <?php
                $riskClass = 'good';
                if ($row['risk_level'] === 'High') {
                    $riskClass = 'danger';
                } elseif ($row['risk_level'] === 'Medium') {
                    $riskClass = 'warn';
                }
              ?>
              <tr
                data-project="<?= htmlspecialchars($row['project_name']) ?>"
                data-overdue="<?= (int)$row['overdue_tasks'] ?>"
                data-days="<?= (int)$row['days_to_deadline'] ?>"
              >
                <td><?= htmlspecialchars($row['project_name']) ?></td>
                <td><?= (int)$row['overdue_tasks'] ?></td>
                <td><?= (int)$row['days_to_deadline'] ?></td>
                <td>
                  <span class="chip <?= $riskClass ?>">
                    <span class="dot"></span><?= htmlspecialchars($row['risk_level']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4">No project risk data found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <div class="callout info" style="margin-top:12px">
        <strong>Logic used</strong>
        <p>High risk if overdue tasks are 3+ or due date is within 2 days. Medium risk if overdue tasks are 1+ or due date is within 7 days. Otherwise Low risk.</p>
      </div>
    </div>

    <div class="card soft">
      <div class="card-head">
        <div>
          <h3 class="card-title">Client Priority Score</h3>
          <p class="card-desc">Based on urgency + value + active projects</p>
        </div>
        <button class="btn" id="recalcPriority" onclick="location.reload()">Update Scores</button>
      </div>

      <table class="table" id="priorityTable">
        <thead>
          <tr>
            <th>Client</th>
            <th>Urgency</th>
            <th>Value</th>
            <th>Active Projects</th>
            <th>Score</th>
            <th>Priority Level</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($priorityData)): ?>
            <?php foreach ($priorityData as $row): ?>
              <?php
                $levelClass = 'good';
                if ($row['priority_level'] === 'High') {
                    $levelClass = 'danger';
                } elseif ($row['priority_level'] === 'Medium') {
                    $levelClass = 'warn';
                }
              ?>
              <tr
                data-client="<?= htmlspecialchars($row['client_name']) ?>"
                data-urgency="<?= (int)$row['urgency'] ?>"
                data-value="<?= (int)$row['value_score'] ?>"
                data-active="<?= (int)$row['active_projects'] ?>"
              >
                <td><?= htmlspecialchars($row['client_name']) ?></td>
                <td><?= (int)$row['urgency'] ?></td>
                <td><?= (int)$row['value_score'] ?></td>
                <td><?= (int)$row['active_projects'] ?></td>
                <td>
                  <span class="chip info">
                    <span class="dot"></span><?= (int)$row['priority_score'] ?>
                  </span>
                </td>
                <td>
                  <span class="chip <?= $levelClass ?>">
                    <span class="dot"></span><?= htmlspecialchars($row['priority_level']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6">No client priority data found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <div class="callout warn" style="margin-top:12px">
        <strong>Logic used</strong>
        <p>Priority score = (urgency × 5) + (value × 4) + (active projects × 3). Higher scores indicate more important clients.</p>
      </div>
    </div>
  </div>

  <div class="footer">© Visionary Verse — DSS</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>

<?php require_once "../app/views/layouts/footer.php"; ?>