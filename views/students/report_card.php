<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">Report Card</h3>
                        <p class="text-muted">
                            <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                            (<?= htmlspecialchars($student['admission_no']) ?>)</p>
                    </div>
                    <div class="text-end">
                        <h4 class="mb-0"><?= htmlspecialchars($student['class_name'] ?? 'N/A') ?></h4>
                        <span class="badge bg-primary"><?= htmlspecialchars($student['section'] ?? '') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($report as $examName => $subjects): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= htmlspecialchars($examName) ?></h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Module Name</th>
                                    <th class="text-center">Credits</th>
                                    <th class="text-center">Max Marks</th>
                                    <th class="text-center">CAT 1</th>
                                    <th class="text-center">CAT 2</th>
                                    <th class="text-center">Exam</th>
                                    <th class="text-center">Total Obtained</th>
                                    <th class="text-center">Scaled %</th>
                                    <th class="text-center">Grade</th>
                                    <th class="text-center">Decision</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalCredits = 0;
                                $grandTotalMarks = 0;
                                $grandTotalObtained = 0;
                                ?>
                                <?php foreach ($subjects as $mark):
                                    // Step 1: Total Obtained (Raw)
                                    $obtained = $mark['score']; // Saved as sum of components
                            
                                    // Step 2: Scaled Score / Percentage
                                    $maxMarks = $mark['max_marks'] ?? 100;
                                    $percentage = ($obtained / $maxMarks) * 100;
                                    $percentage = round($percentage, 1);

                                    // Step 5: Grade Classification
                                    $grade = 'F';
                                    $class = 'Fail';
                                    if ($percentage >= 80) {
                                        $grade = 'A';
                                        $class = 'First Class Hons (Great Distinction)';
                                    } elseif ($percentage >= 70) {
                                        $grade = 'B';
                                        $class = 'First Class Honours';
                                    } elseif ($percentage >= 60) {
                                        $grade = 'C';
                                        $class = 'Second Class Upper';
                                    } elseif ($percentage >= 50) {
                                        $grade = 'D';
                                        $class = 'Second Class Lower';
                                    }

                                    // Step 6: Decision
                                    $decision = ($percentage >= 50) ? 'P' : 'F';
                                    $decisionClass = ($decision === 'P') ? 'text-success fw-bold' : 'text-danger fw-bold';

                                    // Validation for totals
                                    $credits = $mark['credits'] ?? 0;
                                    $totalCredits += $credits;
                                    $grandTotalMarks += $maxMarks;
                                    $grandTotalObtained += $obtained;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($mark['subject_code']) ?></td>
                                        <td><?= htmlspecialchars($mark['subject_name']) ?></td>
                                        <td class="text-center"><?= $credits ?></td>
                                        <td class="text-center"><?= $maxMarks ?></td>
                                        <td class="text-center"><?= $mark['cat1'] ?></td>
                                        <td class="text-center"><?= $mark['cat2'] ?></td>
                                        <td class="text-center"><?= $mark['exam_marks'] ?></td>
                                        <td class="text-center fw-bold"><?= $obtained ?></td>
                                        <td class="text-center"><?= $percentage ?>%</td>
                                        <td class="text-center" title="<?= $class ?>"><?= $grade ?></td>
                                        <td class="text-center <?= $decisionClass ?>"><?= $decision ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="2" class="text-end">TOTALS:</td>
                                    <td class="text-center"><?= $totalCredits ?></td>
                                    <td class="text-center"><?= $grandTotalMarks ?></td>
                                    <td colspan="3"></td>
                                    <td class="text-center"><?= $grandTotalObtained ?></td>
                                    <td colspan="3">
                                        Final %:
                                        <?= ($grandTotalMarks > 0) ? round(($grandTotalObtained / $grandTotalMarks) * 100, 1) : 0 ?>%
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>