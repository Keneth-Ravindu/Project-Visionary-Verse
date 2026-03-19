<?php
$title = 'Notifications';
$pageScript = 'notifications.js';
require_once "../app/views/layouts/header.php";

$unreadCount = 0;
foreach ($notifications as $n) {
    if ((int)$n['is_read'] === 0) $unreadCount++;
}
?>

<?php
$pageTitle = 'Notifications';
$activeTab = 'notifications';
$userRole = $_SESSION['user_role'] ?? 'admin';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
  <div class="input-row" style="justify-content:space-between; flex-wrap:wrap">
    <div>
      <h1 class="h1">Notifications</h1>
      <p class="sub">Stay updated on task changes, approvals, and DSS alerts.</p>
    </div>

    <div class="input-row" style="flex-wrap:wrap">
      <select class="input" id="notifFilter" style="width:220px">
        <option value="all">All Types</option>
        <option value="task">Task</option>
        <option value="approval">Approval</option>
        <option value="deliverable">Deliverable</option>
        <option value="dss">DSS</option>
      </select>

      <form method="POST" action="/pvv/public/notification/readAll" style="display:inline;">
        <button class="btn btn-outline" id="markAll" type="submit">Mark All Read</button>
      </form>
    </div>
  </div>

  <div class="card" style="margin-top:14px">
    <table class="table" id="notifTable">
      <thead>
        <tr>
          <th>Type</th>
          <th>Message</th>
          <th>Time</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($notifications)): ?>
          <?php foreach ($notifications as $n): ?>
            <?php
              $type = strtolower($n['type']);
              $chipClass = 'info';
              if ($type === 'approval' || $type === 'deliverable') $chipClass = 'warn';
              if ($type === 'dss') $chipClass = 'danger';
              $isRead = (int)$n['is_read'] === 1;
            ?>
            <tr data-type="<?= htmlspecialchars($type) ?>" data-read="<?= $isRead ? 'yes' : 'no' ?>">
              <td><span class="chip <?= $chipClass ?>"><span class="dot"></span><?= htmlspecialchars(ucfirst($n['type'])) ?></span></td>
              <td><?= htmlspecialchars($n['message']) ?></td>
              <td><?= htmlspecialchars($n['created_at']) ?></td>
              <td>
                <?php if ($isRead): ?>
                  <span class="chip good"><span class="dot"></span>Read</span>
                <?php else: ?>
                  <span class="chip warn"><span class="dot"></span>Unread</span>
                <?php endif; ?>
              </td>
              <td class="actions">
                <?php if (!$isRead): ?>
                  <form method="POST" action="/pvv/public/notification/read/<?= $n['notification_id'] ?>" style="display:inline;">
                    <button class="btn btn-outline" type="submit">Mark Read</button>
                  </form>
                <?php else: ?>
                  <span class="sub">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">No notifications found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="callout info" style="margin-top:14px">
    <strong>Notification Center</strong>
    <p>Filter by type and clear unread items to keep your queue focused.</p>
  </div>

  <div class="footer">© Visionary Verse — Notifications</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>

<?php require_once "../app/views/layouts/footer.php"; ?>