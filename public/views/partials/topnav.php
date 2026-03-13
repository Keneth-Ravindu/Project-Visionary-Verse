<?php
$pageTitle = $pageTitle ?? 'Dashboard';
$activeTab = $activeTab ?? '';
$userRole = strtolower($userRole ?? 'admin');
$roleLabel = $roleLabel ?? ucfirst($userRole);
?>

<div class="topnav">
  <div class="topbar">
    <div class="brand">
      <button class="iconbtn" id="openDrawer">☰</button>
      <img class="brand-logo-img" src="/pvv/public/assets/img/logo.png" alt="Visionary Verse logo" />
      <div class="brand-text">
        <div class="brand-name">Visionary Verse</div>
        <div class="brand-sub"><?= htmlspecialchars($pageTitle) ?></div>
      </div>
    </div>

    <div class="right">
      <span class="role-pill"><?= htmlspecialchars($roleLabel) ?></span>

      <div class="dropdown">
        <button class="iconbtn" id="notifBtn">
          🔔
          <?php if (($unreadCount ?? 0) > 0): ?>
            <span class="badge"><?= (int)$unreadCount ?></span>
          <?php endif; ?>
        </button>

        <div class="menu" id="notifMenu">
          <?php if (!empty($latestNotifications)): ?>
            <?php foreach ($latestNotifications as $notification): ?>
              <div class="item">
                <b><?= htmlspecialchars(ucfirst($notification['type'])) ?></b>
                <small><?= htmlspecialchars($notification['message']) ?></small>
              </div>
            <?php endforeach; ?>

            <div class="item" style="text-align:center;">
              <a href="/pvv/public/notification/index">View all notifications</a>
            </div>
          <?php else: ?>
            <div class="item">
              <b>No new notifications</b>
              <small>You're all caught up.</small>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <a class="btn btn-outline" href="/pvv/public/auth/logout">Logout</a>
    </div>
  </div>

  <div class="tabs">
    <?php if ($userRole === 'admin'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="/pvv/public/dashboard/admin">Dashboard</a>
      <a class="tab <?= $activeTab === 'clients' ? 'active' : '' ?>" href="/pvv/public/client/index">Clients</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="/pvv/public/project/index">Projects</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="/pvv/public/task/index">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="/pvv/public/approval/index">Approvals</a>
      <a class="tab <?= $activeTab === 'reports' ? 'active' : '' ?>" href="/pvv/public/report/index">Reports</a>
      <a class="tab <?= $activeTab === 'dss' ? 'active' : '' ?>" href="/pvv/public/dss/index">DSS</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="/pvv/public/notification/index">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="/pvv/public/chatbot/index">Chatbot</a>

    <?php elseif ($userRole === 'staff'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="/pvv/public/dashboard/staff">Dashboard</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="/pvv/public/task/index">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="/pvv/public/approval/index">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="/pvv/public/notification/index">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="/pvv/public/chatbot/index">Chatbot</a>

    <?php elseif ($userRole === 'client'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="/pvv/public/dashboard/client">Dashboard</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="/pvv/public/project/index">Projects</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="/pvv/public/approval/index">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="/pvv/public/notification/index">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="/pvv/public/chatbot/index">Chatbot</a>
    <?php endif; ?>
  </div>
</div>

<div class="drawer" id="drawer">
  <div class="drawer-panel">
    <div class="input-row" style="justify-content:space-between">
      <div class="brand">
        <img class="brand-logo-img" src="/pvv/public/assets/img/logo.png" alt="Visionary Verse logo" />
        <div>
          <div class="brand-name">Visionary Verse</div>
          <div class="brand-sub">Menu</div>
        </div>
      </div>
      <button class="iconbtn" id="closeDrawer">✕</button>
    </div>

    <?php if ($userRole === 'admin'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="/pvv/public/dashboard/admin">Dashboard</a>
      <a class="tab <?= $activeTab === 'clients' ? 'active' : '' ?>" href="/pvv/public/client/index">Clients</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="/pvv/public/project/index">Projects</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="/pvv/public/task/index">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="/pvv/public/approval/index">Approvals</a>
      <a class="tab <?= $activeTab === 'reports' ? 'active' : '' ?>" href="/pvv/public/report/index">Reports</a>
      <a class="tab <?= $activeTab === 'dss' ? 'active' : '' ?>" href="/pvv/public/dss/index">DSS</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="/pvv/public/notification/index">Notifications</a>

    <?php elseif ($userRole === 'staff'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="/pvv/public/dashboard/staff">Dashboard</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="/pvv/public/task/index">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="/pvv/public/approval/index">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="/pvv/public/notification/index">Notifications</a>

    <?php elseif ($userRole === 'client'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="/pvv/public/dashboard/client">Dashboard</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="/pvv/public/project/index">Projects</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="/pvv/public/approval/index">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="/pvv/public/notification/index">Notifications</a>
    <?php endif; ?>
  </div>
</div>