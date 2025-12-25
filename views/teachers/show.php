<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Teacher Details</h2>
    <a href="<?= $base_url ?>/teachers" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="text-muted d-block">Full Name</label>
                <p class="h5"><?= htmlspecialchars($teacher['name']) ?></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted d-block">Email Address</label>
                <p class="h5"><?= htmlspecialchars($teacher['email']) ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="text-muted d-block">Phone Number</label>
                <p class="h5"><?= htmlspecialchars($teacher['phone'] ?: 'N/A') ?></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted d-block">Qualification</label>
                <p class="h5"><?= htmlspecialchars($teacher['qualification'] ?: 'N/A') ?></p>
            </div>
        </div>
    </div>
</div>