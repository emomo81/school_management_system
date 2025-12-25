<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Subject Details</h2>
    <a href="<?= $base_url ?>/subjects" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="text-muted d-block">Subject Name</label>
                <p class="h5"><?= htmlspecialchars($subject['name']) ?></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted d-block">Subject Code</label>
                <p class="h5"><?= htmlspecialchars($subject['code']) ?></p>
            </div>
        </div>
    </div>
</div>