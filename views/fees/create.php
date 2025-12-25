<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="font-semibold">Create Fee Invoice</h2>
        <p class="text-muted text-sm">Issue a new charge to a student.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-body">
                <form action="<?= $base_url ?>/fees/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-sm font-semibold">Student</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">Select Student...</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?>
                                    (<?= htmlspecialchars($student['admission_no']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-sm font-semibold">Fee Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Tuition Fee - Q3"
                            required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-sm font-semibold">Amount ($)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-sm font-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-sm font-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control"
                            value="<?= date('Y-m-d', strtotime('+30 days')) ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Create Invoice</button>
                        <a href="<?= $base_url ?>/fees" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>