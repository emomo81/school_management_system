<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="font-semibold">New Subject Assignment</h2>
        <p class="text-muted text-sm">Link a teacher to a specific subject within a class.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-body">
                <form action="<?= $base_url ?>/assignments/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-sm font-semibold">Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">Choose Teacher...</option>
                            <?php foreach ($teachers as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-sm font-semibold">Class</label>
                        <select name="class_id" class="form-select" required>
                            <option value="">Choose Class...</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name'] . ' ' . $c['section']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-sm font-semibold">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Choose Subject...</option>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?>
                                    (<?= htmlspecialchars($s['code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Assign Subject</button>
                        <a href="<?= $base_url ?>/assignments" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>