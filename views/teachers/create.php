<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Teacher</h2>
    <a href="<?= $base_url ?>/teachers" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/teachers/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Qualification</label>
                <input type="text" name="qualification" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Create Teacher</button>
        </form>
    </div>
</div>