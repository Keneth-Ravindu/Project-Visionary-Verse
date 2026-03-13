<?php
$title = 'Admin Dashboard';
$pageScript = '';
require_once "../app/views/layouts/header.php";
?>

<?php
$pageTitle = 'Dashboard';
$activeTab = 'dashboard';
$userRole = $_SESSION['user_role'] ?? 'admin';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
    <h1 class="h1">Admin Dashboard</h1>
    <p class="sub">Overview of clients, projects, tasks, approvals, and decision support signals.</p>

    <div class="grid grid-4">
        <div class="card stat"><div class="label">Active Clients</div><div class="value">12</div><div class="hint">+2 this month</div></div>
        <div class="card stat"><div class="label">Ongoing Projects</div><div class="value">18</div><div class="hint">3 due this week</div></div>
        <div class="card stat"><div class="label">Open Tasks</div><div class="value">47</div><div class="hint">11 high priority</div></div>
        <div class="card stat"><div class="label">Pending Approvals</div><div class="value">5</div><div class="hint">2 waiting on client</div></div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="card-head">
            <div>
                <h3 class="card-title">Recent Projects</h3>
                <p class="card-desc">Quick status snapshot</p>
            </div>
            <button class="btn btn-outline" onclick="location.href='./projects.html'">View</button>
            </div>

            <table class="table">
            <thead><tr><th>Project</th><th>Client</th><th>Status</th><th>Due</th></tr></thead>
            <tbody>
                <tr><td>SEO Optimization</td><td>Nova Foods</td><td><span class="chip info"><span class="dot"></span>In Progress</span></td><td>2026-03-02</td></tr>
                <tr><td>Ads Campaign</td><td>Skyline Homes</td><td><span class="chip warn"><span class="dot"></span>Review</span></td><td>2026-03-01</td></tr>
                <tr><td>Social Media Content</td><td>Glow Cosmetics</td><td><span class="chip good"><span class="dot"></span>On Track</span></td><td>2026-03-10</td></tr>
            </tbody>
            </table>
        </div>

        <div class="card soft">
            <div class="card-head">
            <div>
                <h3 class="card-title">DSS Alerts</h3>
                <p class="card-desc">Delay risk + priority insights</p>
            </div>
            <button class="btn" onclick="location.href='/pvv/public/dss/index'">Open DSS</button>
            </div>

            <div class="grid" style="margin-top:0">
            <div class="callout danger"><strong>High Delay Risk</strong>Ads Campaign has 3 overdue tasks and deadline is near.</div>
            <div class="callout warn"><strong>Medium Delay Risk</strong>Website Redesign completion is below expected.</div>
            <div class="callout info"><strong>Client Priority Score</strong>Nova Foods: 86/100 (urgent + high value).</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div>
            <h3 class="card-title">Team Workload</h3>
            <p class="card-desc">Assigned tasks summary (dummy)</p>
            </div>
            <button class="btn btn-outline" onclick="showToast('Tip','Balance high priority tasks across the team.')">Tip</button>
        </div>

        <table class="table">
            <thead><tr><th>Member</th><th>Assigned</th><th>High Priority</th><th>Overdue</th></tr></thead>
            <tbody>
            <tr><td>Sam</td><td>12</td><td>4</td><td>1</td></tr>
            <tr><td>Nisha</td><td>9</td><td>2</td><td>0</td></tr>
            <tr><td>Ravi</td><td>14</td><td>5</td><td>2</td></tr>
            </tbody>
        </table>
    </div>

    <div class="footer">© Visionary Verse — Admin Dashboard</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>
<?php require_once "../app/views/layouts/footer.php"; ?>