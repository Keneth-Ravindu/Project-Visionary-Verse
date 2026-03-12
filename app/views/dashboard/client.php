<?php
$title = 'Admin Dashboard';
$pageScript = 'app.js';
require_once "../app/views/layouts/header.php";
?>

<div class="topnav">
    <div class="topbar">
        <div class="brand">
            <button class="iconbtn" id="openDrawer">☰</button>
            <img class="brand-logo-img" src="Project-Visionary-Verse/public/assets/img/logo.png" alt="Visionary Verse logo" />
            <div class="brand-text">
            <div class="brand-name">Visionary Verse</div>
            <div class="brand-sub">Client Portal</div>
            </div>
        </div>

        <div class="right">
            <span class="role-pill">Client</span>
            <div class="dropdown">
            <button class="iconbtn" id="notifBtn">🔔 <span class="badge">1</span></button>
            <div class="menu" id="notifMenu">
                <div class="item"><b>New deliverable uploaded</b><small>SEO Report v1</small></div>
            </div>
            </div>
            <button class="btn btn-outline" onclick="location.href='./auth-login.html'">Logout</button>
        </div>
    </div>

    <div class="tabs">
        <a class="tab active" href="./dashboard-client.html">Dashboard</a>
        <a class="tab" href="./projects.html">My Projects</a>
        <a class="tab" href="./approvals.html">Approvals</a>
        <a class="tab" href="./reports.html">Reports</a>
        <a class="tab" href="./notifications.html">Notifications</a>
        <a class="tab" href="./chatbot.html">Chatbot</a>
    </div>
</div>

<div class="drawer" id="drawer">
    <div class="drawer-panel">
        <div class="input-row" style="justify-content:space-between">
            <div class="brand">
            <img class="brand-logo-img" src="Project-Visionary-Verse/public/assets/img/logo.png" alt="Visionary Verse logo" />
            <div><div class="brand-name">Visionary Verse</div><div class="brand-sub">Client</div></div>
            </div>
            <button class="iconbtn" id="closeDrawer">✕</button>
        </div>
        <a class="tab active" href="./dashboard-client.html">Dashboard</a>
        <a class="tab" href="./projects.html">My Projects</a>
        <a class="tab" href="./approvals.html">Approvals</a>
        <a class="tab" href="./reports.html">Reports</a>
        <a class="tab" href="./notifications.html">Notifications</a>
        <a class="tab" href="./chatbot.html">Chatbot</a>
    </div>
</div>

<div class="wrap page">
    <h1 class="h1">Client Dashboard</h1>
    <p class="sub">Track progress and approve deliverables quickly.</p>

    <div class="grid grid-4">
        <div class="card stat"><div class="label">Active Projects</div><div class="value">2</div><div class="hint">Running</div></div>
        <div class="card stat"><div class="label">Pending Approvals</div><div class="value">1</div><div class="hint">Needs your review</div></div>
        <div class="card stat"><div class="label">Tasks Completed</div><div class="value">32</div><div class="hint">Across projects</div></div>
        <div class="card stat"><div class="label">Overall Progress</div><div class="value">74%</div><div class="hint">This month</div></div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="card-head">
            <div>
                <h3 class="card-title">Project Progress</h3>
                <p class="card-desc">Live progress view (dummy)</p>
            </div>
            <button class="btn btn-outline" onclick="location.href='./projects.html'">View all</button>
            </div>

            <div class="grid" style="margin-top:0">
            <div class="callout">
                <strong>SEO Optimization</strong>
                <div class="progress"><div style="width:78%"></div></div>
                <p class="sub">78% complete</p>
            </div>
            <div class="callout">
                <strong>Social Media Content</strong>
                <div class="progress"><div style="width:65%"></div></div>
                <p class="sub">65% complete</p>
            </div>
            </div>
        </div>

        <div class="card soft">
            <div class="card-head">
            <div>
                <h3 class="card-title">Pending Approval</h3>
                <p class="card-desc">Review deliverable and respond</p>
            </div>
            <button class="btn" onclick="location.href='./approvals.html'">Open</button>
            </div>

            <div class="callout info">
            <strong>SEO Report v1</strong>
            Uploaded: 2026-02-26 • Project: SEO Optimization
            <div class="actions" style="margin-top:10px">
                <button class="btn" onclick="showToast('Approved','UI only. Later stored in DB.')">Approve</button>
                <button class="btn btn-outline" onclick="showToast('Requested Changes','UI only. Later stored in DB.')">Request Changes</button>
            </div>
            </div>
        </div>
    </div>

    <div class="footer">© Visionary Verse — Client Dashboard</div>
</div>

<div class="toast" id="toast"><strong></strong><p></p></div>
<?php require_once "../app/views/layouts/footer.php"; ?>