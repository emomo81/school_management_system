<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Academic Years</h2>
    <a href="<?= $base_url ?>/academic-years/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Year
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($years as $year): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($year['name']) ?></strong>
                            </td>
                            <td><?= date('M d, Y', strtotime($year['start_date'])) ?></td>
                            <td><?= date('M d, Y', strtotime($year['end_date'])) ?></td>
                            <td>
                                <?php if ($year['is_active']): ?>
                                    <span class="badge bg-success">Active Session</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$year['is_active']): ?>
                                    <a href="<?= $base_url ?>/academic-years/make-active?id=<?= $year['id'] ?>"
                                        class="btn btn-sm btn-outline-success" title="Set as Active Session">
                                        <i class="fas fa-check-circle"></i> Activate
                                    </a>
                                <?php endif; ?>

                                <a href="<?= $base_url ?>/academic-years/delete?id=<?= $year['id'] ?>"
                                    class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>