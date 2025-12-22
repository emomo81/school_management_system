<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Enter Marks</h2>
    <a href="<?= $base_url ?>/exams" class="btn btn-secondary">Cancel</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/exams/save-marks" method="POST">
            <input type="hidden" name="exam_id" value="<?= $exam_id ?>">
            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">

            <table class="table">
                <thead>
                    <tr>
                        <th>Admission No</th>
                        <th>Student Name</th>
                        <th>Score (out of 100)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['admission_no']) ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td>
                                <input type="number" name="scores[<?= $student['student_id'] ?>]" class="form-control"
                                    min="0" max="100" value="<?= $marks[$student['student_id']] ?? '' ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success mt-3">Save Marks</button>
        </form>
    </div>
</div>