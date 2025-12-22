<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Classes</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="<?= $base_url ?>/classes/create" class="btn btn-primary">Add New Class</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Section</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classes)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No classes found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($classes as $class): ?>
                            <tr>
                                <td><?= htmlspecialchars($class['id']) ?></td>
                                <td><?= htmlspecialchars($class['name']) ?></td>
                                <td><?= htmlspecialchars($class['section']) ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info text-white">View Students</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>