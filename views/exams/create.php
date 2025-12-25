<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Create Exam</h2>
    <a href="<?= $base_url ?>/exams" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/exams/store" method="POST">

            <div class="mb-3">
                <label>Department</label>
                <select name="department_id" class="form-select">
                    <option value="">-- Global / All --</option>
                    <?php if (isset($departments)):
                        foreach ($departments as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                        <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Program (Class)</label>
                <select name="program_id" class="form-select">
                    <option value="">-- Global / All --</option>
                    <?php if (isset($programs)):
                        foreach ($programs as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['name']) ?>
                                <?= !empty($p['section']) ? ' (' . htmlspecialchars($p['section']) . ')' : '' ?>
                            </option>
                        <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Term / Year (e.g. Year 1)</label>
                <input type="text" name="term" class="form-control" placeholder="e.g. Year 1 Term 1">
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save Exam</button>
        </form>
    </div>
</div>