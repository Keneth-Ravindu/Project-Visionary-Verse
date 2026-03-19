<?php
$pageTitle = $pageTitle ?? 'Dashboard';
$activeTab = $activeTab ?? '';
$userRole = strtolower($userRole ?? 'admin');
$roleLabel = $roleLabel ?? ucfirst($userRole);
?>

<div class="topnav">
  <div class="topbar">
    <div class="topbar-left">
      <div class="brand">
        <button class="iconbtn" id="openDrawer">☰</button>
        <img class="brand-logo-img" src="<?= base_url('assets/img/logo.png') ?>" alt="Visionary Verse logo" />
        <div class="brand-text">
          <div class="brand-name">Visionary Verse</div>
        </div>
      </div>

      <div class="topbar-page">
        <div class="topbar-page-title"><?= htmlspecialchars($pageTitle) ?></div>
        <div class="topbar-page-sub">Track ongoing and upcoming activities</div>
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
              <a href="<?= base_url('notification/index') ?>">View all notifications</a>
            </div>
          <?php else: ?>
            <div class="item">
              <b>No new notifications</b>
              <small>You're all caught up.</small>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <a class="btn btn-outline" href="<?= base_url('auth/logout') ?>">Logout</a>
    </div>
  </div>

  <div class="tabs">
    <?php if ($userRole === 'admin'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard/admin') ?>">Dashboard</a>
      <a class="tab <?= $activeTab === 'clients' ? 'active' : '' ?>" href="<?= base_url('client/index') ?>">Clients</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="<?= base_url('project/index') ?>">Projects</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="<?= base_url('task/index') ?>">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="<?= base_url('approval/index') ?>">Approvals</a>
      <a class="tab <?= $activeTab === 'reports' ? 'active' : '' ?>" href="<?= base_url('report/index') ?>">Reports</a>
      <a class="tab <?= $activeTab === 'dss' ? 'active' : '' ?>" href="<?= base_url('dss/index') ?>">DSS</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="<?= base_url('notification/index') ?>">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="<?= base_url('chatbot/index') ?>">Chatbot</a>

    <?php elseif ($userRole === 'staff'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard/staff') ?>">Dashboard</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="<?= base_url('task/index') ?>">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="<?= base_url('approval/index') ?>">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="<?= base_url('notification/index') ?>">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="<?= base_url('chatbot/index') ?>">Chatbot</a>

    <?php elseif ($userRole === 'client'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard/client') ?>">Dashboard</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="<?= base_url('project/index') ?>">Projects</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="<?= base_url('approval/index') ?>">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="<?= base_url('notification/index') ?>">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="<?= base_url('chatbot/index') ?>">Chatbot</a>
    <?php endif; ?>
  </div>
</div>

<div class="drawer" id="drawer">
  <div class="drawer-panel">
    <div class="input-row" style="justify-content:space-between">
      <div class="brand">
        <img class="brand-logo-img" src="<?= base_url('assets/img/logo.png') ?>" alt="Visionary Verse logo" />
        <div>
          <div class="brand-name">Visionary Verse</div>
          <div class="brand-sub">Menu</div>
        </div>
      </div>
      <button class="iconbtn" id="closeDrawer">✕</button>
    </div>

    <?php if ($userRole === 'admin'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard/admin') ?>">Dashboard</a>
      <a class="tab <?= $activeTab === 'clients' ? 'active' : '' ?>" href="<?= base_url('client/index') ?>">Clients</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="<?= base_url('project/index') ?>">Projects</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="<?= base_url('task/index') ?>">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="<?= base_url('approval/index') ?>">Approvals</a>
      <a class="tab <?= $activeTab === 'reports' ? 'active' : '' ?>" href="<?= base_url('report/index') ?>">Reports</a>
      <a class="tab <?= $activeTab === 'dss' ? 'active' : '' ?>" href="<?= base_url('dss/index') ?>">DSS</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="<?= base_url('notification/index') ?>">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="<?= base_url('chatbot/index') ?>">Chatbot</a>

    <?php elseif ($userRole === 'staff'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard/staff') ?>">Dashboard</a>
      <a class="tab <?= $activeTab === 'tasks' ? 'active' : '' ?>" href="<?= base_url('task/index') ?>">Tasks</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="<?= base_url('approval/index') ?>">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="<?= base_url('notification/index') ?>">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="<?= base_url('chatbot/index') ?>">Chatbot</a>

    <?php elseif ($userRole === 'client'): ?>
      <a class="tab <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard/client') ?>">Dashboard</a>
      <a class="tab <?= $activeTab === 'projects' ? 'active' : '' ?>" href="<?= base_url('project/index') ?>">Projects</a>
      <a class="tab <?= $activeTab === 'approvals' ? 'active' : '' ?>" href="<?= base_url('approval/index') ?>">Approvals</a>
      <a class="tab <?= $activeTab === 'notifications' ? 'active' : '' ?>" href="<?= base_url('notification/index') ?>">Notifications</a>
      <a class="tab <?= $activeTab === 'chatbot' ? 'active' : '' ?>" href="<?= base_url('chatbot/index') ?>">Chatbot</a>
    <?php endif; ?>
  </div>
</div>