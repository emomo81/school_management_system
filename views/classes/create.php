<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Program</h2>
    <a href="<?= $base_url ?>/classes" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/classes/store" method="POST">
            <div class="mb-3">
                <label class="form-label">Program Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Software Engineering">
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <div class="input-group">
                    <select name="department_id" class="form-select">
                        <option value="">-- Select Existing Department --</option>
                        <?php if (isset($departments)):
                            foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                            <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="form-text mt-2">OR Create New Department:</div>
                <input type="text" name="new_department" class="form-control" placeholder="Enter New Department Name">
            </div>

            <div class="mb-3">
                <label class="form-label">Time (Section)</label>
                <select name="section" class="form-select" required>
                    <option value="Day">Day</option>
                    <option value="Night">Night</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Create Program</button>
        </form>
    </div>
</div>