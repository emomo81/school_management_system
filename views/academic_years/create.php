<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Add Academic Year</h5>
            </div>
            <div class="card-body">
                <form action="<?= $base_url ?>/academic-years/store" method="POST">

                    <div class="mb-3">
                        <label for="name" class="form-label">Session Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. 2023-2024"
                            required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="<?= $base_url ?>/academic-years" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create Session</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>