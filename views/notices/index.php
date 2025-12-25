<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold">Noticeboard</h2>
                <p class="text-muted text-sm">Stay updated with the latest school announcements.</p>
            </div>
            <?php if ($isAdmin): ?>
                <a href="<?= $base_url ?>/notices/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Post Notice
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($notices)): ?>
        <div class="col-md-12">
            <div class="card border-0 py-5">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-bullhorn fa-3x mb-3 opacity-25"></i>
                    <p>No announcements yet.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($notices as $notice): ?>
            <div class="col-md-6">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="font-semibold mb-0"><?= htmlspecialchars($notice['title']) ?></h5>
                            <?php if ($isAdmin): ?>
                                <a href="<?= $base_url ?>/notices/delete?id=<?= $notice['id'] ?>"
                                    class="text-danger opacity-50 hover-opacity-100"
                                    onclick="return confirm('Delete this notice?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-secondary mb-3"><?= nl2br(htmlspecialchars($notice['content'])) ?></p>
                        <div class="text-xs text-muted d-flex align-items-center gap-1">
                            <i class="far fa-clock"></i>
                            <?= date('M d, Y - h:i A', strtotime($notice['created_at'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>