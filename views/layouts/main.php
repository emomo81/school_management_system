<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'School System' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= $base_url ?>/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }

        .sidebar a {
            color: rgba(255, 255, 255, .8);
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }

        .sidebar a:hover {
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        .content {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <?php if (isset($_SESSION['user'])): ?>
            <nav class="sidebar col-md-2 d-none d-md-block">
                <div class="p-3">
                    <h4>School Sys</h4>
                    <small><?= $_SESSION['user']['name'] ?> (<?= ucfirst($_SESSION['user']['role']) ?>)</small>
                </div>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="<?= $base_url ?>/dashboard">Dashboard</a></li>

                    <?php if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'teacher'): ?>
                        <li class="nav-item"><a href="<?= $base_url ?>/students">Students</a></li>
                        <li class="nav-item"><a href="<?= $base_url ?>/teachers">Teachers</a></li>
                        <li class="nav-item"><a href="<?= $base_url ?>/classes">Classes</a></li>
                        <li class="nav-item"><a href="<?= $base_url ?>/subjects">Subjects</a></li>
                        <li class="nav-item"><a href="<?= $base_url ?>/exams">Exams & Marks</a></li>
                    <?php endif; ?>

                    <?php if ($_SESSION['user']['role'] === 'student'): ?>
                        <li class="nav-item"><a href="<?= $base_url ?>/students/report">My Report Card</a></li>
                    <?php endif; ?>

                    <li class="nav-item"><a href="<?= $base_url ?>/logout">Logout</a></li>
                </ul>
            </nav>
        <?php endif; ?>

        <main class="w-100 <?= isset($_SESSION['user']) ? '' : 'p-0' ?>">
            <?php if (isset($_SESSION['user'])): ?>
                <!-- Top Nav could go here -->
            <?php endif; ?>

            <div class="content">
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['flash_error'];
                    unset($_SESSION['flash_error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['flash_success'];
                    unset($_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>