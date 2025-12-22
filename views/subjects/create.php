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

            <button type="submit" class="btn btn-success">Create Subject</button>
        </form>
    </div>
</div>