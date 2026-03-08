<?php
$title = 'Projects';
$pageScript = 'projects.js';
require_once "../app/views/layouts/header.php";
?>

<div class="topnav">
    <div class="topbar">
        <div class="brand">
            <button class="iconbtn" id="openDrawer">☰</button>
            <img class="brand-logo-img" src="/Project-Visionary-Verse/public/assets/img/logo.png" alt="Visionary Verse logo" />
            <div class="brand-text">
                <div class="brand-name">Visionary Verse</div>
                <div class="brand-sub">Projects</div>
            </div>
        </div>

        <div class="right">
            <span class="role-pill">Admin / Staff / Client</span>
            <div class="dropdown">
                <button class="iconbtn" id="notifBtn">🔔 <span class="badge">3</span></button>
                <div class="menu" id="notifMenu">
                <div class="item"><b>Task moved to Review</b><small>Ads Campaign</small></div>
                <div class="item"><b>Changes requested</b><small>SEO Report v1</small></div>
                <div class="item"><b>DSS alert</b><small>High Delay Risk</small></div>
                </div>
            </div>
            <button class="btn btn-outline" onclick="location.href='/Project-Visionary-Verse/public/auth/login'">Logout</button>
        </div>
    </div>

    <div class="tabs">
        <a class="tab" href="/Project-Visionary-Verse/public/dashboard/admin">Dashboard</a>
        <a class="tab" href="/Project-Visionary-Verse/public/client/index">Clients</a>
        <a class="tab active" href="/Project-Visionary-Verse/public/project/index">Projects</a>
        <a class="tab" href="/Project-Visionary-Verse/public/task/index">Tasks</a>
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
        <a class="tab active" href="/Project-Visionary-Verse/public/project/index">Projects</a>
        <a class="tab" href="/Project-Visionary-Verse/public/task/index">Tasks</a>
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
            <h1 class="h1">Projects</h1>
            <p class="sub">Create projects per client, track service type and status.</p>
        </div>

        <div class="input-row" style="flex-wrap:wrap">
            <input class="input" id="projectSearch" placeholder="Search projects..." style="width:260px" />
            <select class="input" id="serviceFilter" style="width:190px">
                <option value="all">All Services</option>
                <option value="SEO">SEO</option>
                <option value="Ads">Ads</option>
                <option value="Social Media">Social Media</option>
                <option value="Web Design">Web Design</option>
            </select>
            <select class="input" id="statusFilter" style="width:190px">
                <option value="all">All Status</option>
                <option value="To Do">To Do</option>
                <option value="In Progress">In Progress</option>
                <option value="Review">Review</option>
                <option value="Completed">Completed</option>
            </select>
            <button class="btn" data-open="projectModal">+ Add Project</button>
        </div>
    </div>

    <div class="card" style="margin-top:14px">
        <table class="table" id="projectsTable">
            <thead>
                <tr>
                <th>Project</th>
                <th>Client</th>
                <th>Service</th>
                <th>Status</th>
                <th>Due</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                    <tr
                    data-name="<?= htmlspecialchars(strtolower($project['name'])) ?>"
                    data-service="<?= htmlspecialchars(strtolower($project['service'])) ?>"
                    data-status="<?= htmlspecialchars(strtolower($project['status'])) ?>"
                    >
                        <td><?= htmlspecialchars($project['name']) ?></td>
                        <td><?= htmlspecialchars($project['client_name'] ?? 'N/A') ?></td>
                        <td>
                            <span class="chip info"><span class="dot"></span><?= htmlspecialchars($project['service']) ?></span>
                        </td>
                        <td>
                            <?php
                            $status = strtolower($project['status']);
                            $statusClass = '';
                            if ($status === 'review') {
                                $statusClass = 'warn';
                            } elseif ($status === 'in progress' || $status === 'completed') {
                                $statusClass = 'good';
                            }
                            ?>
                            <span class="chip <?= $statusClass ?>"><span class="dot"></span><?= htmlspecialchars($project['status']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($project['due_date']) ?></td>
                            <td class="actions">
                            <button class="btn btn-outline btnEdit" data-id="<?= $project['project_id'] ?>">Edit</button>

                            <form method="POST"
                                action="/Project-Visionary-Verse/public/project/toggleStatus/<?= $project['project_id'] ?>"
                                style="display:inline;">
                            <input type="hidden" name="current_status" value="<?= htmlspecialchars($project['status']) ?>">
                            <button class="btn btn-ghost" type="submit">Change Status</button>
                            </form>

                            <form method="POST"
                                    action="/Project-Visionary-Verse/public/project/delete/<?= $project['project_id'] ?>"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this project?');">
                                <button class="btn btn-outline" type="submit">Delete</button>
                            </form>
                            </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6">No projects found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">© Visionary Verse — Projects</div>
</div>

<div class="modal" id="projectModal">
    <div class="modal-card">
        <div class="modal-head">
            <div>
                <div style="font-weight:950" id="projectModalTitle">Project Details</div>
                <div class="sub">Add or edit a project</div>
            </div>
            <button class="iconbtn" data-close="projectModal">✕</button>
        </div>
        <div class="modal-body">
            <form id="projectForm" method="POST" action="/Project-Visionary-Verse/public/project/store">
                <input type="hidden" id="mProjectId" value="">
                <div class="form-grid">
                    <div>
                        <label style="font-weight:950">Project Name</label>
                        <input class="input" id="mProjectName" name="name" required />
                    </div>
                    <div>
                        <label style="font-weight:950">Client</label>
                        <select class="input" id="mProjectClient" name="client_id" required>
                        <option value="">Select Client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['client_id'] ?>">
                            <?= htmlspecialchars($client['name']) ?> - <?= htmlspecialchars($client['company']) ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950">Service Type</label>
                        <select class="input" id="mProjectService" name="service">
                        <option value="SEO">SEO</option>
                        <option value="Ads">Ads</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Web Design">Web Design</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950">Status</label>
                        <select class="input" id="mProjectStatus" name="status">
                        <option value="To Do">To Do</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Review">Review</option>
                        <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950">Due Date</label>
                        <input class="input" id="mProjectDue" name="due_date" type="date" required />
                    </div>
                    <div style="grid-column:1/-1">
                        <label style="font-weight:950">Description</label>
                        <textarea class="input" id="mProjectDescription" name="description" rows="4"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn btn-outline" type="button" data-close="projectModal">Cancel</button>
                    <button class="btn" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>

<?php require_once "../app/views/layouts/footer.php"; ?>