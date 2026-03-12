<?php
$title = 'Tasks';
$pageScript = 'tasks.js';
require_once "../app/views/layouts/header.php";
?>

<div class="topnav">
  <div class="topbar">
    <div class="brand">
      <button class="iconbtn" id="openDrawer">☰</button>
      <img class="brand-logo-img" src="/Project-Visionary-Verse/public/assets/img/logo.png" alt="Visionary Verse logo" />
      <div class="brand-text">
        <div class="brand-name">Visionary Verse</div>
        <div class="brand-sub">Tasks</div>
      </div>
    </div>

    <div class="right">
      <span class="role-pill">Admin / Staff</span>
      <div class="dropdown">
        <button class="iconbtn" id="notifBtn">🔔 <span class="badge">2</span></button>
        <div class="menu" id="notifMenu">
          <div class="item"><b>Task assigned</b><small>Keyword Research</small></div>
          <div class="item"><b>Status changed</b><small>To Do → Review</small></div>
        </div>
      </div>
      <button class="btn btn-outline" onclick="location.href='/Project-Visionary-Verse/public/auth/login'">Logout</button>
    </div>
  </div>

  <div class="tabs">
    <a class="tab" href="/Project-Visionary-Verse/public/dashboard/admin">Dashboard</a>
    <a class="tab" href="/Project-Visionary-Verse/public/client/index">Clients</a>
    <a class="tab" href="/Project-Visionary-Verse/public/project/index">Projects</a>
    <a class="tab active" href="/Project-Visionary-Verse/public/task/index">Tasks</a>
    <a class="tab" href="/Project-Visionary-Verse/public/approval/index">Approvals</a>
    <a class="tab" href="/Project-Visionary-Verse/public/report/index">Reports</a>
    <a class="tab" href="/Project-Visionary-Verse/public/dss/index">DSS</a>
    <a class="tab" href="/Project-Visionary-Verse/public/notification/index">Notifications</a>
    <a class="tab" href="/Project-Visionary-Verse/public/chatbot/index">Chatbot</a>
  </div>
</div>

<div class="drawer" id="drawer">
  <div class="drawer-panel">
    <div class="input-row" style="justify-content:space-between">
      <div class="brand">
        <img class="brand-logo-img" src="/Project-Visionary-Verse/public/assets/img/logo.png" alt="Visionary Verse logo" />
        <div>
          <div class="brand-name">Visionary Verse</div>
          <div class="brand-sub">Menu</div>
        </div>
      </div>
      <button class="iconbtn" id="closeDrawer">✕</button>
    </div>

    <a class="tab" href="/Project-Visionary-Verse/public/dashboard/admin">Dashboard</a>
    <a class="tab" href="/Project-Visionary-Verse/public/client/index">Clients</a>
    <a class="tab" href="/Project-Visionary-Verse/public/project/index">Projects</a>
    <a class="tab active" href="/Project-Visionary-Verse/public/task/index">Tasks</a>
    <a class="tab" href="/Project-Visionary-Verse/public/approval/index">Approvals</a>
    <a class="tab" href="/Project-Visionary-Verse/public/report/index">Reports</a>
    <a class="tab" href="/Project-Visionary-Verse/public/dss/index">DSS</a>
    <a class="tab" href="/Project-Visionary-Verse/public/notification/index">Notifications</a>
    <a class="tab" href="/Project-Visionary-Verse/public/chatbot/index">Chatbot</a>
  </div>
</div>

<div class="wrap page">
  <div class="input-row" style="justify-content:space-between; flex-wrap:wrap">
    <div>
      <h1 class="h1">Tasks</h1>
      <p class="sub">Create tasks under projects, assign members, set priority and deadlines.</p>
    </div>

    <div class="input-row" style="flex-wrap:wrap">
      <input class="input" id="taskSearch" placeholder="Search tasks..." style="width:260px" />
      <select class="input" id="priorityFilter" style="width:160px">
        <option value="all">All Priority</option>
        <option value="High">High</option>
        <option value="Medium">Medium</option>
        <option value="Low">Low</option>
      </select>
      <select class="input" id="statusFilter" style="width:170px">
        <option value="all">All Status</option>
        <option value="To Do">To Do</option>
        <option value="In Progress">In Progress</option>
        <option value="Review">Review</option>
        <option value="Done">Done</option>
      </select>
      <button class="btn" data-open="taskModal">+ Add Task</button>
    </div>
  </div>

  <div class="card" style="margin-top:14px">
    <table class="table" id="tasksTable">
      <thead>
        <tr>
          <th>Task</th>
          <th>Project</th>
          <th>Assigned To</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Deadline</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($tasks)): ?>
          <?php foreach ($tasks as $task): ?>
            <tr
              data-name="<?= htmlspecialchars(strtolower($task['name'])) ?>"
              data-priority="<?= htmlspecialchars($task['priority']) ?>"
              data-status="<?= htmlspecialchars($task['status']) ?>"
            >
              <td><?= htmlspecialchars($task['name']) ?></td>
              <td><?= htmlspecialchars($task['project_name'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($task['assignee_name'] ?? 'Unassigned') ?></td>
              <td>
                <?php
                  $priority = strtolower($task['priority']);
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
                  $status = strtolower($task['status']);
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
              <td><?= htmlspecialchars($task['deadline']) ?></td>
                <td class="actions">
                <button class="btn btn-outline btnEdit" data-id="<?= $task['task_id'] ?>">Edit</button>

                <form method="POST"
                action="/Project-Visionary-Verse/public/task/moveStatus/<?= $task['task_id'] ?>"
                style="display:inline;">
            <input type="hidden" name="current_status" value="<?= htmlspecialchars($task['status']) ?>">
            <button class="btn btn-ghost" type="submit">Move Status</button>
            </form>

                <form method="POST"
                        action="/Project-Visionary-Verse/public/task/delete/<?= $task['task_id'] ?>"
                        style="display:inline;"
                        onsubmit="return confirm('Are you sure you want to delete this task?');">
                    <button class="btn btn-outline" type="submit">Delete</button>
                </form>
                </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">No tasks found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="callout info" style="margin-top:14px">
    <strong></strong>
    <p></p>
  </div>

  <div class="footer">© Visionary Verse — Tasks</div>
</div>

<div class="modal" id="taskModal">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div style="font-weight:950" id="taskModalTitle">Task Details</div>
        <div class="sub">Add or edit a task</div>
      </div>
      <button class="iconbtn" data-close="taskModal">✕</button>
    </div>
    <div class="modal-body">
      <form id="taskForm" method="POST" action="/Project-Visionary-Verse/public/task/store">
        <input type="hidden" id="mTaskId" value="">
        <div class="form-grid">
          <div>
            <label style="font-weight:950">Task Name</label>
            <input class="input" id="mTaskName" name="name" required />
          </div>
          <div>
            <label style="font-weight:950">Project</label>
            <select class="input" id="mTaskProject" name="project_id" required>
              <option value="">Select Project</option>
              <?php foreach ($projects as $project): ?>
                <option value="<?= $project['project_id'] ?>">
                  <?= htmlspecialchars($project['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label style="font-weight:950">Assigned To</label>
            <select class="input" id="mTaskAssignee" name="assignee_id" required>
              <option value="">Select Assignee</option>
              <?php foreach ($users as $user): ?>
                <option value="<?= $user['user_id'] ?>">
                  <?= htmlspecialchars($user['full_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label style="font-weight:950">Priority</label>
            <select class="input" id="mTaskPriority" name="priority">
              <option value="High">High</option>
              <option value="Medium">Medium</option>
              <option value="Low">Low</option>
            </select>
          </div>
          <div>
            <label style="font-weight:950">Status</label>
            <select class="input" id="mTaskStatus" name="status">
              <option value="To Do">To Do</option>
              <option value="In Progress">In Progress</option>
              <option value="Review">Review</option>
              <option value="Done">Done</option>
            </select>
          </div>
          <div>
            <label style="font-weight:950">Deadline</label>
            <input class="input" id="mTaskDeadline" name="deadline" type="date" required />
          </div>
          <div style="grid-column:1/-1">
            <label style="font-weight:950">Description</label>
            <textarea class="input" id="mTaskDescription" name="description" rows="4"></textarea>
          </div>
        </div>

        <div class="form-actions">
          <button class="btn btn-outline" type="button" data-close="taskModal">Cancel</button>
          <button class="btn" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>

<?php require_once "../app/views/layouts/footer.php"; ?>