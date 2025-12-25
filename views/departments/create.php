<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0">Add Department</h4>
            </div>
            <div class="card-body p-4">
                <form action="<?= $base_url ?>/departments/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Department Name</label>
                        <input type="text" name="name" class="form-control" required
                            placeholder="e.g. Computer Science">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Department Code</label>
                        <input type="text" name="code" class="form-control" required placeholder="e.g. CS">
                        <div class="form-text">Unique code for calculations</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Create Department</button>
                        <a href="<?= $base_url ?>/departments" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>