<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Class</h2>
    <a href="<?= $base_url ?>/classes" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/classes/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Class Name (e.g. Grade 1)</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Section (e.g. A)</label>
                    <input type="text" name="section" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Create Class</button>
        </form>
    </div>
</div>