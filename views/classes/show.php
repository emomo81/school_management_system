<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Program Details</h2>
    <div>
        <a href="<?= $base_url ?>/classes" class="btn btn-secondary me-2">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= htmlspecialchars($class['name']) ?></p>
                <p><strong>Department:</strong>
                    <?php if (!empty($class['department_name'])): ?>
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($class['department_name']) ?></span>
                    <?php else: ?>
                        <span class="text-muted">None</span>
                    <?php endif; ?>
                </p>
                <p><strong>Section:</strong> <?= htmlspecialchars($class['section']) ?></p>
                <p><strong>Created:</strong> <?= date('M d, Y', strtotime($class['created_at'])) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Enrolled Students</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td>
                                    <a href="<?= $base_url ?>/students/show?id=<?= $student['id'] ?>"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No students enrolled in this program.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>