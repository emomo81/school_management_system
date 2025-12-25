<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0">Edit Department</h4>
            </div>
            <div class="card-body p-4">
                <form action="<?= $base_url ?>/departments/update" method="POST">
                    <input type="hidden" name="id" value="<?= $department['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Department Name</label>
                        <input type="text" name="name" class="form-control" required
                            value="<?= htmlspecialchars($department['name']) ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Department Code</label>
                        <input type="text" name="code" class="form-control" required
                            value="<?= htmlspecialchars($department['code']) ?>">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Department</button>
                        <a href="<?= $base_url ?>/departments" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>