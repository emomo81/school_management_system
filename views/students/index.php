<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Students</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="<?= $base_url ?>/students/create" class="btn btn-primary">Add New Student</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Admission No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['admission_no']) ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['class_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td>
                                    <a href="<?= $base_url ?>/students/show?id=<?= $student['id'] ?>"
                                        class="btn btn-sm btn-info text-white">View</a>
                                    <a href="<?= $base_url ?>/students/report?id=<?= $student['id'] ?>"
                                        class="btn btn-sm btn-secondary">Report Card</a>
                                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>