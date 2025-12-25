<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Subjects</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="<?= $base_url ?>/subjects/create" class="btn btn-primary">Add New Subject</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover table-datatable">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="3" class="text-center">No subjects found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= htmlspecialchars($subject['code']) ?></td>
                            <td><?= htmlspecialchars($subject['name']) ?></td>
                            <td>
                                <a href="<?= $base_url ?>/subjects/show?id=<?= $subject['id'] ?>"
                                    class="btn btn-sm btn-info text-white">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>