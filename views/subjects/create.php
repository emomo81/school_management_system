<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Subject</h2>
    <a href="<?= $base_url ?>/subjects" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/subjects/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Subject Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Subject Code (e.g. MATH101)</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">-- Select Department --</option>
                        <?php if (isset($departments)):
                            foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                            <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Credits</label>
                    <input type="number" name="credits" class="form-control" value="3" min="1" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Total Marks (100 or 150)</label>
                    <select name="total_marks" class="form-select">
                        <option value="100">100</option>
                        <option value="150">150</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Create Subject</button>
        </form>
    </div>
</div>