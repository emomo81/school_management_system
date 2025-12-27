<script>
    document.body.classList.add('auth-page');
</script>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-5">
            <div class="brand-logo">
                <i class="fas fa-lock"></i>
            </div>
            <h2 class="font-bold mb-1">Forgot Password?</h2>
            <p class="text-muted text-sm">Enter your email and we'll send you a reset link</p>
        </div>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['flash_error'];
            unset($_SESSION['flash_error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['flash_success'];
            unset($_SESSION['flash_success']); ?></div>
        <?php endif; ?>

        <form action="<?= $base_url ?>/forgot-password/send" method="POST">
            <div class="mb-4">
                <label class="form-label text-xs font-semibold text-uppercase tracking-wider text-muted">Email
                    Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@school.com" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                Send Reset Link
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="<?= $base_url ?>/login" class="text-sm font-semibold text-primary text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </div>
</div>