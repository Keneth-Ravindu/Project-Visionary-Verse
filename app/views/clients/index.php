<?php
$title = 'Clients';
$pageScript = 'clients.js';
require_once "../app/views/layouts/header.php";
?>

<div class="topnav">
    <div class="topbar">
        <div class="brand">
            <button class="iconbtn" id="openDrawer">☰</button>
            <img class="brand-logo-img" src="/Project-Visionary-Verse/public/assets/img/logo.png" alt="Visionary Verse logo" />
            <div class="brand-text">
                <div class="brand-name">Visionary Verse</div>
                <div class="brand-sub">Admin • Client Management</div>
            </div>
        </div>

        <div class="right">
            <span class="role-pill">Admin</span>
            <div class="dropdown">
                <button class="iconbtn" id="notifBtn">🔔 <span class="badge">3</span></button>
                <div class="menu" id="notifMenu">
                <div class="item"><b>Client requested changes</b><small>SEO Report v1</small></div>
                <div class="item"><b>Task moved to Review</b><small>Ads Campaign</small></div>
                <div class="item"><b>High delay risk</b><small>Website Redesign</small></div>
                </div>
            </div>
            <button class="btn btn-outline" onclick="location.href='/Project-Visionary-Verse/public/auth/login'">Logout</button>
        </div>
    </div>

    <div class="tabs">
        <a class="tab" href="/Project-Visionary-Verse/public/dashboard/admin">Dashboard</a>
        <a class="tab active" href="/Project-Visionary-Verse/public/client/index">Clients</a>
        <a class="tab" href="/Project-Visionary-Verse/public/project/index">Projects</a>
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
                <div class="brand-sub">Admin</div>
                </div>
            </div>
            <button class="iconbtn" id="closeDrawer">✕</button>
        </div>
        <a class="tab" href="/Project-Visionary-Verse/public/dashboard/admin">Dashboard</a>
        <a class="tab active" href="/Project-Visionary-Verse/public/client/index">Clients</a>
        <a class="tab" href="/Project-Visionary-Verse/public/project/index">Projects</a>
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
            <h1 class="h1">Clients</h1>
            <p class="sub">Create, edit, and manage client status.</p>
        </div>
        <div class="input-row" style="flex-wrap:wrap">
            <input class="input" id="clientSearch" placeholder="Search clients..." style="width:260px" />
            <select class="input" id="clientStatus" style="width:180px">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button class="btn" data-open="clientModal">+ Add Client</button>
        </div>
    </div>

    <div class="card" style="margin-top:14px">
        <table class="table" id="clientsTable">
            <thead>
                <tr>
                <th>Client</th>
                <th>Email</th>
                <th>Company</th>
                <th>Status</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $client): ?>
                    <tr data-name="<?= htmlspecialchars(strtolower($client['name'])) ?>" data-status="<?= htmlspecialchars(strtolower($client['status'])) ?>">
                    <td><?= htmlspecialchars($client['name']) ?></td>
                    <td><?= htmlspecialchars($client['email']) ?></td>
                    <td><?= htmlspecialchars($client['company']) ?></td>
                    <td>
                        <?php if (strtolower($client['status']) === 'active'): ?>
                        <span class="chip good"><span class="dot"></span>Active</span>
                        <?php else: ?>
                        <span class="chip warn"><span class="dot"></span>Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <button class="btn btn-outline btnEdit" data-id="<?= $client['client_id'] ?>">Edit</button>
                        <?php if (strtolower($client['status']) === 'active'): ?>
                        <form method="POST" action="/Project-Visionary-Verse/public/client/toggle/<?= $client['client_id'] ?>" style="display:inline;">
                            <input type="hidden" name="status" value="inactive">
                            <button class="btn btn-ghost" type="submit">Deactivate</button>
                            </form>
                        <?php else: ?>
                        <form method="POST" action="/Project-Visionary-Verse/public/client/toggle/<?= $client['client_id'] ?>" style="display:inline;">
                            <input type="hidden" name="status" value="active">
                            <button class="btn btn-ghost" type="submit">Activate</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" action="/Project-Visionary-Verse/public/client/delete/<?= $client['client_id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this client?');">
                        <button class="btn btn-outline" type="submit">Delete</button>
                        </form>
                    </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5">No clients found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">© Visionary Verse — Clients</div>
</div>

<div class="modal" id="clientModal">
    <div class="modal-card">
        <div class="modal-head">
            <div>
                <div style="font-weight:950" id="clientModalTitle">Client Details</div>
                <div class="sub">Add or edit a client</div>
            </div>
            <button class="iconbtn" data-close="clientModal">✕</button>
        </div>
        <div class="modal-body">
            <form id="clientForm" method="POST" action="/Project-Visionary-Verse/public/client/store">
                <input type="hidden" id="mClientId" value="">
                <div class="form-grid">
                <div>
                    <label style="font-weight:950">Client Name</label>
                    <input class="input" id="mClientName" name="name" required />
                </div>
                <div>
                    <label style="font-weight:950">Company</label>
                    <input class="input" id="mClientCompany" name="company" required />
                </div>
                <div>
                    <label style="font-weight:950">Email</label>
                    <input class="input" id="mClientEmail" name="email" type="email" required />
                </div>
                    <div>
                    <label style="font-weight:950">Phone</label>
                    <input class="input" id="mClientPhone" name="phone" />
                    </div>

                <div>
                    <label style="font-weight:950">Status</label>
                    <select class="input" id="mClientStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    </select>
                </div>
                </div>

                <div class="form-actions">
                <button class="btn btn-outline" type="button" data-close="clientModal">Cancel</button>
                <button class="btn" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>

<?php require_once "../app/views/layouts/footer.php"; ?>