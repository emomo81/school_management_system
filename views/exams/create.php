<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Create Exam</h2>
    <a href="<?= $base_url ?>/exams" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/exams/store" method="POST">
            <div class="mb-3">
                <label>Exam Name (e.g. Mid-Term 2024)</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save Exam</button>
        </form>
    </div>
</div>