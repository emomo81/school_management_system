<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Class: <?= htmlspecialchars($class['name'] . ' - ' . $class['section']) ?></h2>
    <a href="<?= $base_url ?>/classes" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Students Enrolled</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-datatable">
                <thead>
                    <tr>
                        <th>Admission No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No students enrolled in this class.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['admission_no']) ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td>
                                    <a href="<?= $base_url ?>/students/show?id=<?= $student['id'] ?>"
                                        class="btn btn-sm btn-info text-white">View Profile</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>