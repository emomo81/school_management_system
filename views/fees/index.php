<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold">Fees & Finance</h2>
                <p class="text-muted text-sm">
                    <?= $role === 'student' ? 'Your tuition and other fees.' : 'Manage student invoices and payments.' ?>
                </p>
            </div>
            <?php if ($role === 'admin'): ?>
                <a href="<?= $base_url ?>/fees/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create Invoice
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-datatable">
                <thead>
                    <tr>
                        <?php if ($role !== 'student'): ?>
                            <th>Student</th>
                        <?php endif; ?>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <?php if ($role === 'admin'): ?>
                            <th class="text-end">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($fees)):
                        $cols = 4; // Title, Amount, Due Date, Status
                        if ($role !== 'student')
                            $cols++; // Student column
                        if ($role === 'admin')
                            $cols++; // Actions column
                        ?>
                        <tr>
                            <td colspan="<?= $cols ?>" class="text-center py-4 text-muted">No fee records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($fees as $fee): ?>
                            <tr>
                                <?php if ($role !== 'student'): ?>
                                    <td>
                                        <div class="font-semibold"><?= htmlspecialchars($fee['student_name']) ?></div>
                                        <div class="text-xs text-muted"><?= htmlspecialchars($fee['admission_no']) ?></div>
                                    </td>
                                <?php endif; ?>
                                <td><?= htmlspecialchars($fee['title']) ?></td>
                                <td class="font-semibold">$<?= number_format($fee['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($fee['due_date']) ?></td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-<?= $fee['status'] === 'paid' ? 'success' : 'warning' ?> bg-opacity-10 text-<?= $fee['status'] === 'paid' ? 'success' : 'warning' ?> px-3">
                                        <?= ucfirst($fee['status']) ?>
                                    </span>
                                </td>
                                <?php if ($role === 'admin'): ?>
                                    <td class="text-end">
                                        <?php if ($fee['status'] === 'pending'): ?>
                                            <a href="<?= $base_url ?>/fees/pay?id=<?= $fee['id'] ?>"
                                                class="btn btn-sm btn-outline-success" onclick="return confirm('Mark this as paid?')">
                                                Mark as Paid
                                            </a>
                                        <?php else: ?>
                                            <span class="text-success text-sm"><i class="fas fa-check-double me-1"></i> Finalized</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>