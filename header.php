<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' — AutoServ' : 'AutoServ — Car Service & Repair' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #e63946;
            --dark: #0d0d0d;
            --dark2: #1a1a1a;
            --dark3: #252525;
            --light: #f5f5f5;
            --muted: #888;
            --border: #2e2e2e;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Barlow', sans-serif;
            background: var(--dark);
            color: var(--light);
            min-height: 100vh;
        }
        h1, h2, h3, h4, h5, .display-font {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        /* Navbar */
        .navbar {
            background: var(--dark2) !important;
            border-bottom: 2px solid var(--primary);
            padding: 0.75rem 0;
        }
        .navbar-brand {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #fff !important;
        }
        .navbar-brand span { color: var(--primary); }
        .nav-link {
            color: #ccc !important;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0.03em;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s;
        }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; }
        .btn-nav-primary {
            background: var(--primary);
            color: #fff !important;
            border-radius: 4px;
            font-weight: 600;
            padding: 0.4rem 1.1rem !important;
            transition: background 0.2s;
        }
        .btn-nav-primary:hover { background: #c1121f; color: #fff !important; }
        /* Buttons */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.55rem 1.5rem;
            border-radius: 4px;
        }
        .btn-primary:hover { background: #c1121f; border-color: #c1121f; }
        .btn-outline-light {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        /* Cards */
        .card {
            background: var(--dark2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--light);
            transition: transform 0.2s, border-color 0.2s;
        }
        .card:hover { transform: translateY(-4px); border-color: var(--primary); }
        .card-title { font-family: 'Barlow Condensed', sans-serif; font-size: 1.3rem; font-weight: 700; }
        /* Badge */
        .badge-price {
            background: var(--primary);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            padding: 0.35em 0.7em;
            border-radius: 4px;
        }
        /* Alerts */
        .alert-danger { background: #2d0a0a; border-color: var(--primary); color: #f88; }
        .alert-success { background: #0a2d0a; border-color: #28a745; color: #8f8; }
        /* Forms */
        .form-control, .form-select {
            background: var(--dark3);
            border: 1px solid var(--border);
            color: var(--light);
            border-radius: 4px;
        }
        .form-control:focus, .form-select:focus {
            background: var(--dark3);
            border-color: var(--primary);
            color: var(--light);
            box-shadow: 0 0 0 0.2rem rgba(230,57,70,0.25);
        }
        .form-control::placeholder { color: var(--muted); }
        .form-label { font-weight: 600; font-size: 0.9rem; letter-spacing: 0.03em; color: #bbb; }
        /* Section titles */
        .section-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 0.03em;
        }
        .section-title span { color: var(--primary); }
        .section-divider {
            width: 60px; height: 3px;
            background: var(--primary);
            margin: 0.5rem 0 1.5rem 0;
        }
        /* Footer */
        footer {
            background: var(--dark2);
            border-top: 2px solid var(--border);
            color: var(--muted);
            font-size: 0.9rem;
        }
        footer a { color: var(--muted); text-decoration: none; }
        footer a:hover { color: var(--primary); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">AUTO<span>SERV</span></a>
        <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'categories.php' ? 'active' : '' ?>" href="categories.php">Services</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'my_appointments.php' ? 'active' : '' ?>" href="my_appointments.php">My Appointments</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-muted">
                            <i class="fas fa-user-circle me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-nav-primary" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'login.php' ? 'active' : '' ?>" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-nav-primary" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
