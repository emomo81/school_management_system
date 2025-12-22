<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>
        <p class="text-muted">Role: <?= ucfirst($role) ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <h5 class="card-title">Students</h5>
                <p class="card-text display-4">0</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <h5 class="card-title">Teachers</h5>
                <p class="card-text display-4">0</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Classes</h5>
                <p class="card-text display-4">0</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <h5 class="card-title">Events</h5>
                <p class="card-text display-4">0</p>
            </div>
        </div>
    </div>
</div>

<?php if ($role === 'student'): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3>View Your Results</h3>
                    <p>Check your latest exam scores and performance.</p>
                    <a href="<?= $base_url ?>/students/report" class="btn btn-primary btn-lg">View Report Card</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>