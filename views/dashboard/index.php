<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="font-semibold">Dashboard Overview</h2>
        <p class="text-muted text-sm">Welcome back, <?= htmlspecialchars($name) ?>. Here's what's happening today.</p>
    </div>
</div>

<?php if ($role === 'admin' || $role === 'teacher'): ?>
    <div class="row g-4 mb-5">
        <!-- Student Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Students</h3>
                    <p class="value"><?= $stats['students'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <!-- Teacher Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>Teachers</h3>
                    <p class="value"><?= $stats['teachers'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <!-- Classes Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-info">
                    <h3>Classes</h3>
                    <p class="value"><?= $stats['classes'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <!-- Exams Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="stat-info">
                    <h3>Exams</h3>
                    <p class="value"><?= $stats['exams'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="font-semibold">Student Distribution by Class</span>
                    <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip"
                        title="Number of students in each class"></i>
                </div>
                <div class="card-body">
                    <canvas id="classDistChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 h-100">
                <div class="card-header">
                    <span class="font-semibold">Quick Access</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?= $base_url ?>/students/create"
                            class="list-group-item list-group-item-action border-0 d-flex align-items-center gap-3 py-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-2"><i class="fas fa-plus"></i></div>
                            <div>
                                <div class="font-semibold text-sm">Add Student</div>
                                <div class="text-xs text-muted">Register a new student profile</div>
                            </div>
                        </a>
                        <a href="<?= $base_url ?>/exams/create"
                            class="list-group-item list-group-item-action border-0 d-flex align-items-center gap-3 py-3">
                            <div class="bg-success bg-opacity-10 text-success rounded p-2"><i
                                    class="fas fa-calendar-plus"></i></div>
                            <div>
                                <div class="font-semibold text-sm">Schedule Exam</div>
                                <div class="text-xs text-muted">Create a new examination period</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('classDistChart').getContext('2d');
            const labels = <?= json_encode(array_column($stats['classDistribution'] ?? [], 'name')) ?>;
            const data = <?= json_encode(array_column($stats['classDistribution'] ?? [], 'count')) ?>;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Students',
                        data: data,
                        backgroundColor: '#4f46e5',
                        borderRadius: 8,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5] },
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>

<?php elseif ($role === 'student'): ?>
    <?php $s = $stats['student_info'] ?? null; ?>
    <div class="row g-4 mb-5">
        <!-- Attendance Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>Attendance</h3>
                    <p class="value text-success"><?= $stats['attendance'] ?>%</p>
                </div>
            </div>
        </div>
        <!-- Fees Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-info">
                    <h3>Pending Fees</h3>
                    <p class="value text-warning">$<?= number_format($stats['pending_fees'], 2) ?></p>
                </div>
            </div>
        </div>
        <!-- Performance Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-award"></i>
                </div>
                <div class="stat-info">
                    <h3>Avg. Performance</h3>
                    <p class="value text-primary"><?= $stats['avg_score'] ?>%</p>
                </div>
            </div>
        </div>
        <!-- Subjects Stat -->
        <div class="col-md-3">
            <div class="card stat-card border-0">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Subjects</h3>
                    <p class="value text-info"><?= $stats['subjects'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Student Profile Quick View -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 h-100">
                <div class="card-header">
                    <span class="font-semibold">My Profile</span>
                </div>
                <div class="card-body text-center py-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="mb-1"><?= htmlspecialchars($name) ?></h5>
                    <p class="text-muted text-sm mb-3"><?= htmlspecialchars($s['class_name'] ?? 'No Class') ?> -
                        <?= htmlspecialchars($s['section'] ?? '') ?>
                    </p>
                    <div class="badge bg-light text-muted p-2 rounded-lg mb-3">
                        <i class="fas fa-id-card me-2"></i> ID: <?= htmlspecialchars($s['admission_no'] ?? 'N/A') ?>
                    </div>
                    <hr class="my-4">
                    <a href="<?= $base_url ?>/students/report" class="btn btn-primary w-100">
                        <i class="fas fa-file-alt me-2"></i> Full Report Card
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Notifications -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 h-100">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs border-0" id="activityTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active border-0 py-3 px-4 font-semibold text-sm" id="notices-tab"
                                data-bs-toggle="tab" data-bs-target="#notices" type="button"
                                role="tab">Announcements</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link border-0 py-3 px-4 font-semibold text-sm" id="notifications-tab"
                                data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                                Notifications
                                <?php
                                $unreadCount = count(array_filter($stats['notifications'] ?? [], fn($n) => !$n['is_read']));
                                if ($unreadCount > 0): ?>
                                    <span class="badge bg-danger ms-1"><?= $unreadCount ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="activityTabsContent">
                        <!-- Announcements Tab -->
                        <div class="tab-pane fade show active" id="notices" role="tabpanel">
                            <div class="list-group list-group-flush">
                                <?php
                                $db = \App\Core\Database::getInstance()->getConnection();
                                $notices = $db->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3")->fetchAll();
                                if (empty($notices)): ?>
                                    <div class="p-4 text-center text-muted">No announcements yet.</div>
                                <?php else: ?>
                                    <?php foreach ($notices as $notice): ?>
                                        <div class="list-group-item border-0 p-4">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="mb-0 font-semibold"><?= htmlspecialchars($notice['title']) ?></h6>
                                                <span
                                                    class="text-xs text-muted"><?= date('M d, Y', strtotime($notice['created_at'])) ?></span>
                                            </div>
                                            <p class="text-sm text-muted mb-0">
                                                <?= substr(strip_tags($notice['content']), 0, 120) ?>...</p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel">
                            <div class="list-group list-group-flush">
                                <?php if (empty($stats['notifications'])): ?>
                                    <div class="p-4 text-center text-muted">No personal notifications.</div>
                                <?php else: ?>
                                    <?php foreach (array_slice($stats['notifications'], 0, 5) as $n): ?>
                                        <div
                                            class="list-group-item border-0 p-4 <?= !$n['is_read'] ? 'bg-light bg-opacity-50' : '' ?>">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="stat-icon bg-primary bg-opacity-10 text-primary"
                                                    style="width: 32px; height: 32px; font-size: 0.875rem;">
                                                    <i
                                                        class="fas <?= $n['type'] === 'exam' ? 'fa-award' : ($n['type'] === 'fee' ? 'fa-wallet' : 'fa-bell') ?>"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="mb-0 text-sm font-semibold"><?= htmlspecialchars($n['title']) ?>
                                                        </h6>
                                                        <span
                                                            class="text-xs text-muted"><?= date('M d, H:i', strtotime($n['created_at'])) ?></span>
                                                    </div>
                                                    <p class="text-xs text-muted mb-0 mt-1"><?= htmlspecialchars($n['message']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>