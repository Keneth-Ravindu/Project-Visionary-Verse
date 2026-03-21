<?php
$title = 'Tasks';
$pageScript = 'tasks.js';
require_once "../app/views/layouts/header.php";
?>

<?php
$pageTitle = 'Tasks';
$activeTab = 'tasks';
$userRole = $_SESSION['user_role'] ?? 'staff';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
  <div class="input-row" style="justify-content:space-between; flex-wrap:wrap">
    <div>
      <h1 class="h1">Tasks</h1>
      <p class="sub">Track assignments, priorities, statuses, and deadlines in one place.</p>
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
                action="/pvv/public/task/moveStatus/<?= $task['task_id'] ?>"
                style="display:inline;">
            <input type="hidden" name="current_status" value="<?= htmlspecialchars($task['status']) ?>">
            <button class="btn btn-ghost" type="submit">Move Status</button>
            </form>

                <form method="POST"
                        action="/pvv/public/task/delete/<?= $task['task_id'] ?>"
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
    <strong>Status Workflow</strong>
    <p>Use Move Status to keep project execution and approvals updated.</p>
  </div>

  <div class="footer">© Visionary Verse — Tasks</div>
</div>

<div class="modal" id="taskModal">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div style="font-weight:950" id="taskModalTitle">Task Details</div>
        <div class="sub">Create or update task details</div>
      </div>
      <button class="iconbtn" data-close="taskModal">✕</button>
    </div>
    <div class="modal-body">
      <form id="taskForm" method="POST" action="/pvv/public/task/store">
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