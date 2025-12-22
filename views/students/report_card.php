<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Report Card: <?= htmlspecialchars($student['name']) ?></h2>
    <a href="#" class="btn btn-secondary" onclick="window.print()">Print Report</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Admission No:</strong> <?= htmlspecialchars($student['admission_no']) ?>
            </div>
            <div class="col-md-4">
                <strong>Class:</strong> <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?> -
                <?= htmlspecialchars($student['section'] ?? '') ?>
            </div>
            <div class="col-md-4">
                <strong>Date:</strong> <?= date('Y-m-d') ?>
            </div>
        </div>
    </div>
</div>

<?php if (empty($report)): ?>
    <div class="alert alert-info">No marks recorded yet.</div>
<?php else: ?>
    <?php foreach ($report as $examName => $subjects): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><?= htmlspecialchars($examName) ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Code</th>
                            <th>Score</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalScore = 0;
                        $totalMax = 0;
                        foreach ($subjects as $s):
                            $totalScore += $s['score'];
                            $totalMax += $s['total'];
                            // Calculate Grade
                            $score = $s['score'];
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
                                <td><?= htmlspecialchars($s['subject_name']) ?></td>
                                <td><?= htmlspecialchars($s['subject_code']) ?></td>
                                <td><?= $score ?> / <?= $s['total'] ?></td>
                                <td><span class="badge bg-<?= $grade === 'F' ? 'danger' : 'success' ?>"><?= $grade ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-secondary">
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong><?= $totalScore ?> / <?= $totalMax ?></strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>