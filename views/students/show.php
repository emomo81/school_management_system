<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Student Details</h2>
    <div>
        <a href="<?= $base_url ?>/students/report?id=<?= $student['id'] ?>" class="btn btn-secondary me-2">Report
            Card</a>
        <a href="<?= $base_url ?>/students" class="btn btn-primary">Back to List</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>Name:</strong> <?= htmlspecialchars($student['name']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Admission No:</strong> <?= htmlspecialchars($student['admission_no']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Email:</strong> <?= htmlspecialchars($student['email']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Class:</strong> <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?> -
                <?= htmlspecialchars($student['section'] ?? '') ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Date of Birth:</strong> <?= htmlspecialchars($student['dob']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Gender:</strong> <?= ucfirst(htmlspecialchars($student['gender'])) ?>
            </div>
            <div class="col-md-12 mb-3">
                <strong>Address:</strong> <?= nl2br(htmlspecialchars($student['address'])) ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Academic Performance (Marks)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-datatable">
                <thead>
                    <tr>
                        <th>Exam</th>
                        <th>Subject</th>
                        <th>Score</th>
                        <th>Grade</th>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($marks)): ?>
                        <tr>
                            <td colspan="<?= $_SESSION['user']['role'] === 'admin' ? 5 : 4 ?>" class="text-center">No marks
                                recorded.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($marks as $mark):
                            $score = $mark['score'];
                            $grade = 'F';
                            if ($score >= 90)
                                $grade = 'A+';
                            elseif ($score >= 80)
                                $grade = 'A';
                            elseif ($score >= 70)
                                $grade = 'B';
                            elseif ($score >= 60)
                                $grade = 'C';
                            elseif ($score >= 50)
                                $grade = 'D';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($mark['exam_name']) ?></td>
                                <td><?= htmlspecialchars($mark['subject_name']) ?>
                                    (<?= htmlspecialchars($mark['subject_code']) ?>)</td>
                                <td><?= $score ?> / 100</td>
                                <td><span class="badge bg-<?= $grade === 'F' ? 'danger' : 'success' ?>"><?= $grade ?></span>
                                </td>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <td>
                                        <form action="<?= $base_url ?>/exams/enter-marks" method="POST" style="display:inline;">
                                            <input type="hidden" name="exam_id" value="<?= $mark['exam_id'] ?>">
                                            <input type="hidden" name="class_id" value="<?= $student['class_id'] ?>">
                                            <input type="hidden" name="subject_id" value="<?= $mark['subject_id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-warning">Edit</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>