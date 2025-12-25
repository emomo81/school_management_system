<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Teachers</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="<?= $base_url ?>/teachers/create" class="btn btn-primary">Add New Teacher</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($teachers)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No teachers found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td><?= htmlspecialchars($teacher['name']) ?></td>
                                <td><?= htmlspecialchars($teacher['email']) ?></td>
                                <td><?= htmlspecialchars($teacher['phone']) ?></td>
                                <td><?= htmlspecialchars($teacher['qualification']) ?></td>
                                <td>
                                    <a href="<?= $base_url ?>/teachers/show?id=<?= $teacher['id'] ?>"
                                        class="btn btn-sm btn-info text-white">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>