<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Programs</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="<?= $base_url ?>/classes/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add Program
        </a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-datatable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Department</th>
                        <th>Section/Stream</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td class="ps-4 fw-bold">
                                <a href="<?= $base_url ?>/classes/show?id=<?= $class['id'] ?>"
                                    class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($class['name']) ?>
                                </a>
                            </td>
                            <td>
                                <?php if (!empty($class['department_name'])): ?>
                                    <span
                                        class="badge bg-info text-dark"><?= htmlspecialchars($class['department_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($class['section']) ?></td>
                            <td class="text-end pe-4">
                                <a href="<?= $base_url ?>/classes/show?id=<?= $class['id'] ?>"
                                    class="btn btn-sm btn-outline-secondary">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>