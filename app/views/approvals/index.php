<?php
$title = 'Approvals';
$pageScript = 'approvals.js';
require_once "../app/views/layouts/header.php";
?>

<?php
$pageTitle = 'Approvals';
$activeTab = 'approvals';
$userRole = $_SESSION['user_role'] ?? 'staff';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
  <div class="input-row" style="justify-content:space-between; flex-wrap:wrap">
    <div>
      <h1 class="h1">Approvals</h1>
      <p class="sub">Review deliverables, approve work, or request revisions.</p>
    </div>

    <div class="input-row" style="flex-wrap:wrap">
      <input class="input" id="approvalSearch" placeholder="Search deliverables..." style="width:260px" />
      <select class="input" id="approvalFilter" style="width:180px">
        <option value="all">All</option>
        <option value="Pending" selected>Pending</option>
        <option value="Approved">Approved</option>
        <option value="Changes Requested">Changes Requested</option>
      </select>
      <?php if (($_SESSION['user_role'] ?? '') !== 'client'): ?>
        <button class="btn" data-open="deliverableModal">+ Upload Deliverable</button>
      <?php endif; ?>
    </div>
  </div>

  <div class="card" style="margin-top:14px">
    <table class="table" id="approvalsTable">
      <thead>
        <tr>
          <th>Deliverable</th>
          <th>Project</th>
          <th>Uploaded By</th>
          <th>Status</th>
          <th>Uploaded</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($deliverables)): ?>
          <?php foreach ($deliverables as $d): ?>
            <tr data-name="<?= htmlspecialchars(strtolower($d['name'])) ?>" data-status="<?= htmlspecialchars($d['status']) ?>">
              <td><?= htmlspecialchars($d['name']) ?></td>
              <td><?= htmlspecialchars($d['project_name'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($d['uploader_name'] ?? 'N/A') ?></td>
              <td>
                <?php
                  $status = $d['status'];
                  $statusClass = 'danger';
                  if ($status === 'Approved') {
                      $statusClass = 'good';
                  } elseif ($status === 'Pending') {
                      $statusClass = 'warn';
                  }
                ?>
                <span class="chip <?= $statusClass ?>"><span class="dot"></span><?= htmlspecialchars($status) ?></span>
              </td>
              <td><?= htmlspecialchars($d['submitted_at']) ?></td>
              <td class="actions">
                <button class="btn btn-outline btnView" data-id="<?= $d['deliverable_id'] ?>">View</button>

                <?php if (($_SESSION['user_role'] ?? '') !== 'client'): ?>
                  <?php if ($d['status'] !== 'Approved'): ?>
                    <form method="POST" action="/pvv/public/approval/approve/<?= $d['deliverable_id'] ?>" style="display:inline;">
                      <button class="btn btn-ghost" type="submit">Approve</button>
                    </form>
                  <?php endif; ?>

                  <?php if ($d['status'] !== 'Changes Requested'): ?>
                    <form method="POST"
                          action="/pvv/public/approval/changes/<?= $d['deliverable_id'] ?>"
                          style="display:inline;"
                          onsubmit="return confirm('Mark this deliverable as Changes Requested?');">
                      <input type="hidden" name="feedback" value="Changes requested by client.">
                      <button class="btn btn-ghost" type="submit">Request Changes</button>
                    </form>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="sub">Review only</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">No deliverables found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="footer">© Visionary Verse — Approvals</div>
</div>

<?php if (($_SESSION['user_role'] ?? '') !== 'client'): ?>
<div class="modal" id="deliverableModal">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div style="font-weight:950">Upload Deliverable</div>
        <div class="sub">Store deliverables in database</div>
      </div>
      <button class="iconbtn" data-close="deliverableModal">✕</button>
    </div>
    <div class="modal-body">
      <form id="deliverableForm" method="POST" action="/pvv/public/approval/store" enctype="multipart/form-data">
        <div class="form-grid">
          <div>
            <label style="font-weight:950">Deliverable Name</label>
            <input class="input" id="mDelivName" name="name" required />
          </div>
          <div>
            <label style="font-weight:950">Project</label>
            <select class="input" id="mDelivProject" name="project_id" required>
              <option value="">Select Project</option>
              <?php foreach ($projects as $project): ?>
                <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label style="font-weight:950">Uploaded By</label>
            <select class="input" id="mDelivBy" name="uploaded_by" required>
              <option value="">Select User</option>
              <?php foreach ($users as $user): ?>
                <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label style="font-weight:950">Upload File</label>
            <input class="input" id="mDelivFile" name="deliverable_file" type="file" />
          </div>
        </div>

        <div class="form-actions">
          <button class="btn btn-outline" type="button" data-close="deliverableModal">Cancel</button>
          <button class="btn" type="submit">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="modal" id="viewDeliverableModal">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div style="font-weight:950" id="viewTitle">Deliverable</div>
        <div class="sub" id="viewSub">Details</div>
      </div>
      <button class="iconbtn" data-close="viewDeliverableModal">✕</button>
    </div>
    <div class="modal-body">
      <div class="callout info">
        <strong>Deliverable Info</strong>
        <p id="viewInfo">Deliverable metadata and approval context.</p>
      </div>
      <div class="form-actions">
        <button class="btn btn-outline" type="button" data-close="viewDeliverableModal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>

<?php require_once "../app/views/layouts/footer.php"; ?>