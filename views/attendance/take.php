<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold">Mark Attendance</h2>
                <p class="text-muted text-sm">Class: <span
                        class="text-primary font-semibold"><?= htmlspecialchars($className) ?></span> | Date: <span
                        class="text-primary font-semibold"><?= htmlspecialchars($date) ?></span></p>
            </div>
            <a href="<?= $base_url ?>/attendance" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="card border-0">
    <div class="card-body">
        <form action="<?= $base_url ?>/attendance/store" method="POST">
            <input type="hidden" name="class_id" value="<?= $class_id ?>">
            <input type="hidden" name="date" value="<?= $date ?>">

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th class="text-center">Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No students found in this class.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td class="font-semibold"><?= htmlspecialchars($student['name']) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Attendance status">
                                            <input type="radio" class="btn-check"
                                                name="attendance[<?= $student['student_id'] ?>][status]"
                                                id="p<?= $student['student_id'] ?>" value="present"
                                                <?= ($student['status'] == 'present' || !$student['status']) ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-success border-0 px-3"
                                                for="p<?= $student['student_id'] ?>">P</label>

                                            <input type="radio" class="btn-check"
                                                name="attendance[<?= $student['student_id'] ?>][status]"
                                                id="a<?= $student['student_id'] ?>" value="absent"
                                                <?= $student['status'] == 'absent' ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-danger border-0 px-3"
                                                for="a<?= $student['student_id'] ?>">A</label>

                                            <input type="radio" class="btn-check"
                                                name="attendance[<?= $student['student_id'] ?>][status]"
                                                id="l<?= $student['student_id'] ?>" value="late" <?= $student['status'] == 'late' ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-warning border-0 px-3"
                                                for="l<?= $student['student_id'] ?>">L</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="attendance[<?= $student['student_id'] ?>][remarks]"
                                            class="form-control form-control-sm border-0 bg-light"
                                            placeholder="Optional remarks..."
                                            value="<?= htmlspecialchars($student['remarks'] ?? '') ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fas fa-save me-2"></i> Save Attendance
                </button>
            </div>
        </form>
    </div>
</div>