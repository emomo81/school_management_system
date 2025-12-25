<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="font-semibold">Import Students</h2>
        <p class="text-muted text-sm">Upload a CSV file to register multiple students at once.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-body">
                <form action="<?= $base_url ?>/students/import-process" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label text-sm font-semibold">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        <div class="form-text text-xs mt-2">
                            <strong>CSV Format:</strong><br>
                            First Name, Last Name, Email, Admission No, DOB (YYYY-MM-DD), Gender (male/female), Address,
                            Class ID, Password
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-upload me-2"></i> Start Import
                            </a>
                            <a href="<?= $base_url ?>/students" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="font-semibold mb-3"><i class="fas fa-info-circle me-2"></i> Instructions</h6>
                <ul class="text-sm text-secondary ps-3">
                    <li class="mb-2">Ensure the email addresses are unique and valid.</li>
                    <li class="mb-2">The <strong>Class ID</strong> can be found in the Classes list section.</li>
                    <li class="mb-2">Dates must follow the <code>YYYY-MM-DD</code> format (e.g., 2010-05-15).</li>
                    <li>The first row of the CSV (header) will be skipped automatically.</li>
                </ul>
            </div>
        </div>
    </div>
</div>