<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Student</h2>
    <a href="<?= $base_url ?>/students" class="btn btn-secondary">Back</a>
</div>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['flash_error'];
    unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/students/update" method="POST">
            <input type="hidden" name="id" value="<?= $student['id'] ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" required
                        value="<?= htmlspecialchars(explode(' ', $student['name'])[0]) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" required
                        value="<?= htmlspecialchars(explode(' ', $student['name'], 2)[1] ?? '') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required
                        value="<?= htmlspecialchars($student['email']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Admission No</label>
                    <input type="text" name="admission_no" class="form-control" required
                        value="<?= htmlspecialchars($student['admission_no']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="New Password">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-control" required
                        value="<?= htmlspecialchars($student['dob']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="male" <?= $student['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $student['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $student['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Class (Optional)</label>
                    <select name="class_id" class="form-select">
                        <option value="">-- Select Class --</option>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= $student['class_id'] == $class['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['name'] . ' - ' . $class['section']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control"
                    rows="2"><?= htmlspecialchars($student['address']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning">Update Student</button>
        </form>
    </div>
</div>