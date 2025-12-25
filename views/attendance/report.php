<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold">Attendance Report</h2>
                <p class="text-muted text-sm">Monthly summary of student attendance.</p>
            </div>
            <a href="<?= $base_url ?>/attendance" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="card border-0 mb-4">
    <div class="card-body">
        <form action="<?= $base_url ?>/attendance/report" method="GET" class="row g-3">
            <div class="col-md-5">
                <label class="form-label text-sm font-semibold">Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">Choose Class...</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= ($classId == $class['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($class['name'] . ' ' . $class['section']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label text-sm font-semibold">Month</label>
                <input type="month" name="month" class="form-control" value="<?= htmlspecialchars($month) ?>" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Generate</button>
            </div>
        </form>
    </div>
</div>

<?php if ($classId): ?>
    <div class="card border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-datatable">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th class="text-center text-success">Present</th>
                            <th class="text-center text-danger">Absent</th>
                            <th class="text-center text-warning">Late</th>
                            <th class="text-center">Attendance %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData as $row):
                            $total = $row['present'] + $row['absent'] + $row['late'];
                            $percent = ($total > 0) ? round(($row['present'] / $total) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td class="font-semibold"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="text-center"><?= $row['present'] ?></td>
                                <td class="text-center"><?= $row['absent'] ?></td>
                                <td class="text-center"><?= $row['late'] ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; min-width: 60px;">
                                            <div class="progress-bar bg-<?= ($percent > 75) ? 'success' : (($percent > 50) ? 'warning' : 'danger') ?>"
                                                role="progressbar" style="width: <?= $percent ?>%"></div>
                                        </div>
                                        <span class="text-xs font-semibold"><?= $percent ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>