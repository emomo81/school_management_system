<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'School System' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link href="<?= $base_url ?>/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex">
        <?php if (isset($_SESSION['user'])): ?>
            <nav class="sidebar col-md-2" id="sidebar">
                <div class="brand">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-graduation-cap text-primary"></i>
                        <span>SCHOOLSYS</span>
                    </div>
                    <button class="btn btn-link text-white d-md-none" id="sidebarClose">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="px-4 mb-4">
                    <div class="text-xs text-uppercase font-semibold text-muted mb-2">Main Menu</div>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?= $base_url ?>/dashboard"
                            class="<?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <!-- Noticeboard moved to role specific block -->

                    <?php if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'teacher'): ?>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/students"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'students') ? 'active' : '' ?>">
                                <i class="fas fa-user-graduate"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/academic-years"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'academic-years') ? 'active' : '' ?>">
                                <i class="fas fa-calendar-alt"></i> Academic Years
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/classes"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'classes') ? 'active' : '' ?>">
                                <i class="fas fa-graduation-cap"></i> Programs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/subjects"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'subjects') ? 'active' : '' ?>">
                                <i class="fas fa-book"></i> Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/timetables"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'timetables') ? 'active' : '' ?>">
                                <i class="fas fa-calendar-week"></i> Timetable
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/attendance"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'attendance') ? 'active' : '' ?>">
                                <i class="fas fa-calendar-check"></i> Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/exams"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'exams') ? 'active' : '' ?>">
                                <i class="fas fa-file-invoice"></i> Exams & Marks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/notices"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'notices') ? 'active' : '' ?>">
                                <i class="fas fa-bullhorn"></i> Noticeboard
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/teachers"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'teachers') ? 'active' : '' ?>">
                                <i class="fas fa-chalkboard-teacher"></i> Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/assignments"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'assignments') ? 'active' : '' ?>">
                                <i class="fas fa-tasks"></i> Assignments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/fees"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'fees') ? 'active' : '' ?>">
                                <i class="fas fa-file-invoice-dollar"></i> Fees & Finance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/departments"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'departments') ? 'active' : '' ?>">
                                <i class="fas fa-building"></i> Departments
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['user']['role'] === 'student'): ?>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/timetables"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'timetables') ? 'active' : '' ?>">
                                <i class="fas fa-calendar-week"></i> My Timetable
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $base_url ?>/students/report"
                                class="<?= str_contains($_SERVER['REQUEST_URI'], 'report') ? 'active' : '' ?>">
                                <i class="fas fa-file-alt"></i> My Report Card
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="mt-auto nav-item px-3 mb-4">
                        <a href="<?= $base_url ?>/logout" class="text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

        <div class="main-wrapper">
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            <?php if (isset($_SESSION['user'])): ?>
                <header class="top-bar">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-link text-dark d-md-none me-3" id="sidebarToggle">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end">
                            <div class="font-semibold text-sm"><?= $_SESSION['user']['name'] ?></div>
                            <div class="text-xs text-muted"><?= ucfirst($_SESSION['user']['role']) ?></div>
                        </div>
                        <a href="<?= $base_url ?>/profile" class="text-decoration-none">
                            <?php if (!empty($_SESSION['user']['profile_pic'])): ?>
                                <img src="<?= $base_url . '/' . $_SESSION['user']['profile_pic'] ?>" alt="Profile"
                                    class="rounded-circle border border-2 border-white shadow-sm"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                </header>
            <?php endif; ?>

            <div class="content">
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger border-0 shadow-sm rounded-lg"><?= $_SESSION['flash_error'];
                    unset($_SESSION['flash_error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success border-0 shadow-sm rounded-lg"><?= $_SESSION['flash_success'];
                    unset($_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            $('.table-datatable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search anything..."
                },
                "dom": '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"f>>t<"d-flex justify-content-between align-items-center mt-3"ip>'
            });

            // Mobile Sidebar Toggles
            $('#sidebarToggle, #sidebarOverlay, #sidebarClose').on('click', function () {
                $('#sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('show');
            });
        });
    </script>
</body>

</html>