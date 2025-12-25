<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Exams</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="<?= $base_url ?>/exams/create" class="btn btn-primary">Create New Exam</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover table-datatable">
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Department</th>
                    <th>Program</th>
                    <th>Term / Year</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($exams)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No exams found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($exams as $exam): ?>
                        <tr>
                            <td><?= htmlspecialchars($exam['name']) ?></td>
                            <td><?= htmlspecialchars($exam['department_name'] ?? 'Global') ?></td>
                            <td>
                                <?= htmlspecialchars($exam['program_name'] ?? 'Global') ?>
                                <?= !empty($exam['section']) ? ' (' . htmlspecialchars($exam['section']) . ')' : '' ?>
                            </td>
                            <td><?= htmlspecialchars($exam['term'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($exam['date']) ?></td>
                            <td>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <a href="<?= $base_url ?>/exams/marks?id=<?= $exam['id'] ?>"
                                        class="btn btn-sm btn-success">Enter Marks</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>