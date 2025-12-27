<script>
    document.body.classList.add('auth-page');
</script>

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