<?php
$title = 'Staff Dashboard';
$pageScript = '';
require_once "../app/views/layouts/header.php";
?>

<?php
$pageTitle = 'Dashboard';
$activeTab = 'dashboard';
$userRole = $_SESSION['user_role'] ?? 'staff';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
    <h1 class="h1">Staff Dashboard</h1>
    <p class="sub">Your tasks, deadlines and quick actions.</p>

    <div class="grid grid-4">
        <div class="card stat"><div class="label">My Open Tasks</div><div class="value">8</div><div class="hint">2 high priority</div></div>
        <div class="card stat"><div class="label">Due This Week</div><div class="value">3</div><div class="hint">Keep steady</div></div>
        <div class="card stat"><div class="label">In Review</div><div class="value">2</div><div class="hint">Waiting client</div></div>
        <div class="card stat"><div class="label">Completed</div><div class="value">14</div><div class="hint">This month</div></div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="card-head">
            <div>
                <h3 class="card-title">Today’s Focus</h3>
                <p class="card-desc">Update status to keep workflow moving</p>
            </div>
            <button class="btn" onclick="location.href='/pvv/public/task/index'">Open Tasks</button>
            </div>

            <table class="table">
            <thead><tr><th>Task</th><th>Project</th><th>Priority</th><th>Status</th></tr></thead>
            <tbody>
                <tr><td>Keyword Research</td><td>SEO Optimization</td><td><span class="chip danger"><span class="dot"></span>High</span></td><td><span class="chip info"><span class="dot"></span>To Do</span></td></tr>
                <tr><td>Create Post Designs</td><td>Social Media Content</td><td><span class="chip warn"><span class="dot"></span>Medium</span></td><td><span class="chip good"><span class="dot"></span>In Progress</span></td></tr>
                <tr><td>Ad Copy Draft</td><td>Ads Campaign</td><td><span class="chip warn"><span class="dot"></span>Medium</span></td><td><span class="chip warn"><span class="dot"></span>Review</span></td></tr>
            </tbody>
            </table>
        </div>

        <div class="card soft">
            <div class="card-head">
            <div>
                <h3 class="card-title">Quick Actions</h3>
                <p class="card-desc">Fast shortcuts</p>
            </div>
            </div>

            <div class="grid" style="margin-top:0">
            <button class="btn" onclick="location.href='/pvv/public/task/index'">+ Create / Update Tasks</button>
            <button class="btn btn-outline" onclick="location.href='/pvv/public/approval/index'">Upload Deliverable</button>
            <button class="btn btn-outline" onclick="showToast('Reminder','Keep task statuses updated for real-time notifications.')">Reminder</button>
            <div class="callout info"><strong>Tip</strong>Moving To Do → Review triggers notification to client (later).</div>
            </div>
        </div>
    </div>

    <div class="footer">© Visionary Verse — Staff Dashboard</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>
<?php require_once "../app/views/layouts/footer.php"; ?>