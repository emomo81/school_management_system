<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Departments</h2>
    <a href="<?= $base_url ?>/departments/create" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add Department
    </a>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash_success'];
    unset($_SESSION['flash_success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['flash_error'];
    unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $dept): ?>
                        <tr>
                            <td class="ps-4">#<?= $dept['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($dept['name']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($dept['code']) ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?= $base_url ?>/departments/edit?id=<?= $dept['id'] ?>"
                                    class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= $base_url ?>/departments/delete?id=<?= $dept['id'] ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure? Verify no subjects are linked first.')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($departments)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No departments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>