<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Student</h2>
    <a href="<?= $base_url ?>/students" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $base_url ?>/students/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Admission No</label>
                    <input type="text" name="admission_no" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required
                        placeholder="Enter login password">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Program (Class)</label>
                    <select name="class_id" class="form-select">
                        <option value="">-- Select Program --</option>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>">
                                    <?= htmlspecialchars($class['name']) ?>
                                    <?= !empty($class['department_name']) ? ' (' . htmlspecialchars($class['department_name']) . ')' : '' ?>
                                    <?= !empty($class['section']) ? ' - ' . htmlspecialchars($class['section']) : '' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Academic Year</label>
                    <select name="academic_year_id" class="form-select" required>
                        <option value="">-- Select Academic Year --</option>
                        <?php if (!empty($years)): ?>
                            <?php foreach ($years as $year): ?>
                                <option value="<?= $year['id'] ?>" <?= ($year['is_active']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($year['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Create Student</button>
        </form>
    </div>
</div>