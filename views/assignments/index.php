<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold">Subject Assignments</h2>
                <p class="text-muted text-sm">Manage which teachers are assigned to which subjects and classes.</p>
            </div>
            <a href="<?= $base_url ?>/assignments/create" class="btn btn-primary">
                <i class="fas fa-link me-2"></i> New Assignment
            </a>
        </div>
    </div>
</div>

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-datatable">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Assigned Teacher</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No assignments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assignments as $a): ?>
                            <tr>
                                <td class="font-semibold text-primary">
                                    <?= htmlspecialchars($a['class_name'] . ' ' . $a['section']) ?>
                                </td>
                                <td><?= htmlspecialchars($a['subject_name']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;">
                                            <i class="fas fa-user-tie text-muted text-xs"></i>
                                        </div>
                                        <span><?= htmlspecialchars($a['teacher_name']) ?></span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="<?= $base_url ?>/assignments/delete?id=<?= $a['id'] ?>"
                                        class="btn btn-sm btn-outline-danger border-0"
                                        onclick="return confirm('Remove this assignment?')">
                                        <i class="fas fa-unlink"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>