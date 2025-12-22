<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Student Details</h2>
    <div>
        <a href="<?= $base_url ?>/students/report?id=<?= $student['id'] ?>" class="btn btn-secondary me-2">Report
            Card</a>
        <a href="<?= $base_url ?>/students" class="btn btn-primary">Back to List</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>Name:</strong> <?= htmlspecialchars($student['name']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Admission No:</strong> <?= htmlspecialchars($student['admission_no']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Email:</strong> <?= htmlspecialchars($student['email']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Class:</strong> <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?> -
                <?= htmlspecialchars($student['section'] ?? '') ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Date of Birth:</strong> <?= htmlspecialchars($student['dob']) ?>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Gender:</strong> <?= ucfirst(htmlspecialchars($student['gender'])) ?>
            </div>
            <div class="col-md-12 mb-3">
                <strong>Address:</strong> <?= nl2br(htmlspecialchars($student['address'])) ?>
            </div>
        </div>
    </div>
</div>