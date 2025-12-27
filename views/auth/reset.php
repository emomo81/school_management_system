<script>
    document.body.classList.add('auth-page');
</script>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-5">
            <div class="brand-logo">
                <i class="fas fa-key"></i>
            </div>
            <h2 class="font-bold mb-1">Reset Password</h2>
            <p class="text-muted text-sm">Enter your new password below</p>
        </div>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['flash_error'];
            unset($_SESSION['flash_error']); ?></div>
        <?php endif; ?>

        <form action="<?= $base_url ?>/reset-password/update" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="mb-3">
                <label class="form-label text-xs font-semibold text-uppercase tracking-wider text-muted">New
                    Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label text-xs font-semibold text-uppercase tracking-wider text-muted">Confirm
                    Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                Update Password
            </button>
        </form>
    </div>
</div>