<?php
$title = 'Login - Visionary Verse';
$pageScript = 'auth.js';
require_once "../app/views/layouts/header.php";
?>
<div class="auth">
    <div class="auth-card">
        <div class="auth-head">
            <div class="brand">
            <img class="brand-logo-img" src="Project-Visionary-Verse/public/assets/img/logo.png" alt="Visionary Verse logo" />
            <div class="brand-text">
                <div class="brand-name">Visionary Verse</div>
                <div class="brand-sub">Agency Management System</div>
            </div>
            </div>
            <p class="sub">Login to continue.</p>
        </div>

        <div class="auth-body">
            <form id="loginForm">
            <div>
                <label style="font-weight:950">Email</label>
                <input class="input" id="email" type="email" placeholder="you@example.com" required />
            </div>

            <div style="margin-top:10px">
                <label style="font-weight:950">Password</label>
                <input class="input" id="password" type="password" placeholder="••••••••" required />
            </div>

            <div style="margin-top:10px">
                <label style="font-weight:950">Role (UI Demo)</label>
                <select class="input" id="role">
                <option value="admin">Admin</option>
                <option value="staff">Team Member</option>
                <option value="client">Client</option>
                </select>
            </div>

            <div class="input-row" style="margin-top:14px">
                <button class="btn" type="submit" style="flex:1">Login</button>
                <button class="btn btn-outline" type="button" id="fillDemo">Fill Demo</button>
            </div>

            <div class="footer">UI only. Later connect to PHP sessions.</div>
            </form>
        </div>
    </div>
</div>
<?php require_once "../app/views/layouts/footer.php"; ?>