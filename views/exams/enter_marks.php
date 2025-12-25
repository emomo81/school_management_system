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
                    <?php
                    // Calculate max marks based on subject total
                    // Ratio: CAT1 30%, CAT2 30%, Exam 40%
                    $ratio = $total_marks / 100;
                    $maxCat1 = 30 * $ratio;
                    $maxCat2 = 30 * $ratio;
                    $maxExam = 40 * $ratio;
                    ?>
                    <tr>
                        <th>Admission No</th>
                        <th>Student Name</th>
                        <th>CAT 1 (Max: <?= $maxCat1 ?>)</th>
                        <th>CAT 2 (Max: <?= $maxCat2 ?>)</th>
                        <th>Exam (Max: <?= $maxExam ?>)</th>
                        <th>Total (Max: <?= $total_marks ?>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <?php
                        $m = $marks[$student['student_id']] ?? [];
                        $cat1 = $m['cat1'] ?? '';
                        $cat2 = $m['cat2'] ?? '';
                        $exam = $m['exam_marks'] ?? '';
                        $total = $m['score'] ?? '';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($student['admission_no']) ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td>
                                <input type="number" name="marks[<?= $student['student_id'] ?>][cat1]" class="form-control"
                                    min="0" max="<?= $maxCat1 ?>" placeholder="Max <?= $maxCat1 ?>" value="<?= $cat1 ?>">
                            </td>
                            <td>
                                <input type="number" name="marks[<?= $student['student_id'] ?>][cat2]" class="form-control"
                                    min="0" max="<?= $maxCat2 ?>" placeholder="Max <?= $maxCat2 ?>" value="<?= $cat2 ?>">
                            </td>
                            <td>
                                <input type="number" name="marks[<?= $student['student_id'] ?>][exam]" class="form-control"
                                    min="0" max="<?= $maxExam ?>" placeholder="Max <?= $maxExam ?>" value="<?= $exam ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" readonly value="<?= $total ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success mt-3">Save Marks</button>
        </form>
    </div>
</div>