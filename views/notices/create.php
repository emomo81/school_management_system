<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="font-semibold">Post New Notice</h2>
        <p class="text-muted text-sm">Announcement will be visible to all users.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-body">
                <form action="<?= $base_url ?>/notices/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-sm font-semibold">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Sports Day Announcement"
                            required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-sm font-semibold">Notice Content</label>
                        <textarea name="content" class="form-control" rows="5"
                            placeholder="Details of the announcement..." required></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Post Announcement</button>
                        <a href="<?= $base_url ?>/notices" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>