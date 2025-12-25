<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Timetable</h2>
    <?php if ($role === 'admin'): ?>
        <a href="<?= $base_url ?>/timetables/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add Slot
        </a>
    <?php endif; ?>
</div>

<?php if ($role === 'admin'): ?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form action="" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="class_id" class="form-label">Select Class</label>
                    <select name="class_id" id="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Choose Class --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= ($selectedClassId == $class['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['name']) ?>
                                <?= !empty($class['department_name']) ? '(' . htmlspecialchars($class['department_name']) . ')' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if ($selectedClassId || $role === 'teacher'): ?>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 100px;">Time</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Organizing data for grid
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $slots = [];

                        // Group by Day -> Time
                        if (!empty($timetable)) {
                            foreach ($timetable as $t) {
                                $slots[$t['day_of_week']][] = $t;
                            }
                        }

                        // Define time slots (This is a simplified approach, a real one would be dynamic gaps)
                        // simpler approach: just list items in their day cells
                        ?>
                        <tr>
                            <td class="font-bold text-muted">Schedule</td>
                            <?php foreach ($days as $day): ?>
                                <td class="align-top p-0">
                                    <?php if (isset($slots[$day])): ?>
                                        <div class="d-flex flex-column gap-2 p-2">
                                            <?php foreach ($slots[$day] as $slot): ?>
                                                <div class="card border-start border-4 border-primary shadow-sm h-100">
                                                    <div class="card-body p-2 text-start">
                                                        <div class="text-xs text-muted mb-1">
                                                            <?= date('H:i', strtotime($slot['start_time'])) ?> -
                                                            <?= date('H:i', strtotime($slot['end_time'])) ?>
                                                        </div>
                                                        <h6 class="mb-1 text-sm font-bold text-primary">
                                                            <?= htmlspecialchars($slot['subject_name']) ?>
                                                        </h6>
                                                        <div class="text-xs mb-1">
                                                            <i class="fas fa-chalkboard-teacher me-1"></i>
                                                            <?= htmlspecialchars($slot['teacher_name'] ?? 'Teacher') ?>
                                                        </div>
                                                        <?php if (!empty($slot['room_number'])): ?>
                                                            <div class="text-xs text-muted">
                                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                                <?= htmlspecialchars($slot['room_number']) ?>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if ($role === 'teacher' && isset($slot['class_name'])): ?>
                                                            <div class="text-xs fw-bold mt-1 text-dark">
                                                                <?= htmlspecialchars($slot['class_name']) ?>
                                                                <?= !empty($slot['section']) ? '(' . htmlspecialchars($slot['section']) . ')' : '' ?>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if ($role === 'admin'): ?>
                                                            <div class="mt-2 text-end">
                                                                <a href="<?= $base_url ?>/timetables/delete?id=<?= $slot['id'] ?>"
                                                                    class="text-danger text-xs"
                                                                    onclick="return confirm('Remove this slot?')">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted text-xs py-4">-</div>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php elseif ($role === 'admin'): ?>
    <div class="alert alert-info border-0 shadow-sm">
        <i class="fas fa-info-circle me-2"></i> Please select a class to view its timetable.
    </div>
<?php elseif ($role === 'student'): ?>
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle me-2"></i> You are not assigned to any class yet. Please contact admin.
    </div>
<?php endif; ?>