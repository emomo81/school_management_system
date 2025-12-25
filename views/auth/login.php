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

    .form-control {
        background: rgba(248, 250, 252, 0.8);
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        background: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .btn-login {
        padding: 0.875rem;
        border-radius: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        margin-top: 1rem;
    }

    .demo-box {
        background: rgba(79, 70, 229, 0.05);
        border-radius: 1rem;
        padding: 1rem;
        margin-top: 2rem;
        border: 1px dashed rgba(79, 70, 229, 0.2);
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-5">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2 class="font-bold mb-1">Welcome Back</h2>
            <p class="text-muted text-sm">Please enter your details to sign in</p>
        </div>

        <form action="<?= $base_url ?>/login" method="POST">
            <div class="mb-3">
                <label class="form-label text-xs font-semibold text-uppercase tracking-wider text-muted">Email
                    Address</label>
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="name@school.com" required
                        autofocus>
                </div>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label
                        class="form-label text-xs font-semibold text-uppercase tracking-wider text-muted">Password</label>
                    <a href="<?= $base_url ?>/forgot-password"
                        class="text-xs font-semibold text-primary text-decoration-none">Forgot?</a>
                </div>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                Sign In <i class="fas fa-arrow-right ms-2 text-xs"></i>
            </button>
        </form>

        <div class="mt-4">
            <div class="position-relative mb-4">
                <hr class="text-muted opacity-25">
                <span
                    class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted text-xs font-semibold text-uppercase">Or
                    continue with</span>
            </div>
            <a href="<?= $base_url ?>/auth/google"
                class="btn btn-outline-light w-100 border p-2 d-flex align-items-center justify-content-center gap-2 rounded-lg text-dark text-sm font-semibold hover-bg-light transition-all">
                <img src="https://www.google.com/favicon.ico" alt="Google" width="18" height="18">
                Sign in with Google
            </a>
        </div>

    </div>

    <div class="text-center mt-4">
        <p class="text-muted text-xs">© <?= date('Y') ?> SchoolSys Management. All rights reserved.</p>
    </div>
</div>