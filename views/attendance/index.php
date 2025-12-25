<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="font-semibold">Attendance Management</h2>
        <p class="text-muted text-sm">Select a class and date to mark or view attendance.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-header">
                <span class="font-semibold">Mark Attendance</span>
            </div>
            <div class="card-body">
                <form action="<?= $base_url ?>/attendance/take" method="GET">
                    <div class="mb-3">
                        <label class="form-label text-sm font-semibold">Select Class</label>
                        <select name="class_id" class="form-select" required>
                            <option value="">Choose Class...</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>">
                                    <?= htmlspecialchars($class['name'] . ' ' . $class['section']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-sm font-semibold">Date</label>
                        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-check-circle me-2"></i> Mark Attendance
                    </button>
                    <a href="<?= $base_url ?>/attendance/report" class="btn btn-outline-primary w-100">
                        <i class="fas fa-file-invoice me-2"></i> Attendance Report
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 bg-primary bg-opacity-10 text-primary h-100">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="fas fa-clipboard-check fa-4x mb-4 opacity-50"></i>
                <h5>Regular Monitoring</h5>
                <p class="text-xs px-4">Maintaining consistent attendance records helps in tracking student engagement
                    and identifying early warning signs for academic performance.</p>
            </div>
        </div>
    </div>
</div>