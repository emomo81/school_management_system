<style>
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        width: 100%;
        max-width: 420px;
        padding: 2rem;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 2rem;
        padding: 3rem 2.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
    }

    .brand-logo {
        width: 64px;
        height: 64px;
        background: var(--primary-color);
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .btn-login {
        padding: 0.875rem;
        border-radius: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        margin-top: 1rem;
    }
</style>

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