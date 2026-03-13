<?php
$title = 'Login - Visionary Verse';
require_once "../app/views/layouts/header.php";
?>

<div class="auth">
    <div class="auth-card">
        <div class="auth-head">
            <div class="brand">
                <img class="brand-logo-img" src="/pvv/public/assets/img/logo.png" alt="Visionary Verse logo" />
                <div class="brand-text">
                    <div class="brand-name">Visionary Verse</div>
                    <div class="brand-sub">Agency Management System</div>
                </div>
            </div>
            <p class="sub">Login to continue.</p>
        </div>

        <div class="auth-body">
            <?php if (!empty($error ?? '')): ?>
                <div class="callout danger" style="margin-bottom:14px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="/pvv/public/auth/login">
                <div>
                    <label style="font-weight:950">Email</label>
                    <input
                        class="input"
                        id="email"
                        name="email"
                        type="email"
                        placeholder="you@example.com"
                        required
                    />
                </div>

                <div style="margin-top:10px">
                    <label style="font-weight:950">Password</label>
                    <input
                        class="input"
                        id="password"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        required
                    />
                </div>

                <div class="input-row" style="margin-top:14px">
                    <button class="btn" type="submit" style="flex:1">Login</button>
                </div>

                <div class="footer">Secure login with role-based redirect.</div>
            </form>
        </div>
    </div>
</div>

<?php require_once "../app/views/layouts/footer.php"; ?>