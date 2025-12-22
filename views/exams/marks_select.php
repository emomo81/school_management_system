<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Select Class & Subject for Marks</h2>
    <a href="<?= $base_url ?>/exams" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/exams/enter-marks" method="POST">
            <input type="hidden" name="exam_id" value="<?= $_GET['id'] ?? $exam_id ?>">

            <div class="mb-3">
                <label>Class</label>
                <select name="class_id" class="form-select" required>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?> - <?= $c['section'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Subject</label>
                <select name="subject_id" class="form-select" required>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?> (<?= $s['code'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Proceed</button>
        </form>
    </div>
</div>