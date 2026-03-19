<?php
$title = 'Login - Visionary Verse';
require_once "../app/views/layouts/header.php";
?>

<div class="auth">
    <div class="auth-split">
        <div class="auth-left">
            <div class="brand">
                <img class="brand-logo-img" src="<?= base_url('assets/img/logo.png') ?>" alt="Visionary Verse logo" />
                <div class="brand-text">
                    <div class="brand-name">Visionary Verse</div>
                </div>
            </div>

            <h1 class="auth-left-title">Visionary Verse Web Application Management System</h1>
            <p class="auth-left-sub"> “Manage Smarter. Build the Future.”</p>

            <div class="auth-quote">
                <p>“The platform made managing our web projects much easier. Everything is organized and easy to track.”</p>
                <div class="auth-quote-user">
                    <div class="avatar">YJ</div>
                    <div>
                        <strong>Mahinda Rajapaksa</strong>
                        <small>Hadawathe Janadipathi</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-wrap">
                <h1 class="h1" style="font-size:1.9rem;">Welcome back</h1>
                <p class="sub">Please enter your details to sign in.</p>

                <?php if (!empty($error ?? '')): ?>
                    <div class="callout danger" style="margin:18px 0 14px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form id="loginForm" method="POST" action="<?= base_url('auth/login') ?>" style="margin-top:18px;">
                    <div>
                        <label style="font-weight:700">Email address <span style="color:#f43f5e">*</span></label>
                        <input
                            class="input"
                            id="email"
                            name="email"
                            type="email"
                            placeholder="name@agency.com"
                            required
                            style="margin-top:8px"
                        />
                    </div>

                    <div style="margin-top:16px">
                        <label style="font-weight:700">Password <span style="color:#f43f5e">*</span></label>
                        <div class="password-wrap" style="margin-top:8px;">
                            <input
                                class="input"
                                id="password"
                                name="password"
                                type="password"
                                placeholder="••••••••"
                                required
                            />
                            <span class="pass-eye">◉</span>
                        </div>
                    </div>

                    <div class="auth-aux">
                        <a href="#" onclick="return false;">Forgot password?</a>
                    </div>

                    <div class="input-row" style="margin-top:18px">
                        <button class="btn" type="submit" style="flex:1; height:44px;">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once "../app/views/layouts/footer.php"; ?>