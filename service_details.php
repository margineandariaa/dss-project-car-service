<?php
session_start();
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: categories.php'); exit; }

$stmt = $pdo->prepare("
    SELECT s.*, c.name as cat_name, c.id as cat_id, c.description as cat_desc
    FROM services s
    JOIN categories c ON s.category_id = c.id
    WHERE s.id = ?
");
$stmt->execute([$id]);
$service = $stmt->fetch();
if (!$service) { header('Location: categories.php'); exit; }

// Related services in same category
$related = $pdo->prepare("SELECT * FROM services WHERE category_id = ? AND id != ? LIMIT 3");
$related->execute([$service['cat_id'], $id]);
$related = $related->fetchAll();

$pageTitle = htmlspecialchars($service['name']);
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none; padding:0;">
                <li class="breadcrumb-item"><a href="index.php" style="color:var(--primary);">Home</a></li>
                <li class="breadcrumb-item"><a href="categories.php" style="color:var(--primary);">Services</a></li>
                <li class="breadcrumb-item"><a href="services.php?category=<?= $service['cat_id'] ?>" style="color:var(--primary);"><?= htmlspecialchars($service['cat_name']) ?></a></li>
                <li class="breadcrumb-item active" style="color:#aaa;"><?= htmlspecialchars($service['name']) ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Left: details -->
            <div class="col-lg-7">
                <!-- Category tag -->
                <div style="color:var(--primary); font-family:'Barlow Condensed',sans-serif; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; font-size:0.9rem; margin-bottom:0.5rem;">
                    <?= htmlspecialchars($service['cat_name']) ?>
                </div>
                <h1 class="section-title" style="font-size:2.5rem;"><?= htmlspecialchars($service['name']) ?></h1>
                <div class="section-divider"></div>

                <p style="color:#bbb; font-size:1.05rem; line-height:1.8; margin-top:1.5rem;">
                    <?= htmlspecialchars($service['description']) ?>
                </p>

                <!-- Meta info -->
                <div class="row g-3 mt-2 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="card p-3 text-center">
                            <i class="fas fa-euro-sign mb-2" style="color:var(--primary); font-size:1.3rem;"></i>
                            <div style="font-family:'Barlow Condensed',sans-serif; font-size:1.5rem; font-weight:800; color:var(--primary);">
                                €<?= number_format($service['price'], 2) ?>
                            </div>
                            <div style="color:var(--muted); font-size:0.8rem;">Price</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="card p-3 text-center">
                            <i class="fas fa-clock mb-2" style="color:var(--primary); font-size:1.3rem;"></i>
                            <div style="font-family:'Barlow Condensed',sans-serif; font-size:1.5rem; font-weight:800;">
                                <?= $service['duration_minutes'] >= 60
                                    ? floor($service['duration_minutes']/60) . 'h' . ($service['duration_minutes']%60 ? ' ' . $service['duration_minutes']%60 . 'm' : '')
                                    : $service['duration_minutes'] . 'm' ?>
                            </div>
                            <div style="color:var(--muted); font-size:0.8rem;">Duration</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="card p-3 text-center">
                            <i class="fas fa-shield-alt mb-2" style="color:var(--primary); font-size:1.3rem;"></i>
                            <div style="font-family:'Barlow Condensed',sans-serif; font-size:1.5rem; font-weight:800;">30 days</div>
                            <div style="color:var(--muted); font-size:0.8rem;">Guarantee</div>
                        </div>
                    </div>
                </div>

                <a href="booking.php?service=<?= $service['id'] ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-check me-2"></i>Book This Service
                </a>
                <a href="services.php?category=<?= $service['cat_id'] ?>" class="btn btn-outline-light btn-lg ms-3">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>

            <!-- Right: visual panel -->
            <div class="col-lg-5">
                <div class="card p-4">
                    <h5 style="font-family:'Barlow Condensed',sans-serif; font-size:1.3rem; margin-bottom:1.5rem;">
                        WHAT'S <span style="color:var(--primary);">INCLUDED</span>
                    </h5>
                    <?php
                    $includes = [
                        'Professional assessment before work begins',
                        'Certified mechanic assigned to your vehicle',
                        'Only quality-approved parts used',
                        'Full diagnostic report on completion',
                        '30-day service guarantee',
                    ];
                    foreach ($includes as $item): ?>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle me-3" style="color:var(--primary); flex-shrink:0;"></i>
                        <span style="color:#ccc; font-size:0.95rem;"><?= $item ?></span>
                    </div>
                    <?php endforeach; ?>

                    <hr style="border-color:var(--border);">
                    <div style="font-size:0.85rem; color:var(--muted);">
                        <i class="fas fa-info-circle me-2" style="color:var(--primary);"></i>
                        Final price may vary depending on your vehicle. Confirmed at the workshop.
                    </div>
                </div>
            </div>
        </div>

        <!-- Related services -->
        <?php if ($related): ?>
        <div class="mt-5">
            <h3 class="section-title">OTHER <span>SERVICES IN THIS CATEGORY</span></h3>
            <div class="section-divider"></div>
            <div class="row g-4 mt-2">
                <?php foreach ($related as $r): ?>
                <div class="col-md-4">
                    <div class="card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0"><?= htmlspecialchars($r['name']) ?></h6>
                            <span class="badge-price ms-2">€<?= number_format($r['price'], 2) ?></span>
                        </div>
                        <p style="color:#aaa; font-size:0.85rem; flex-grow:1;"><?= htmlspecialchars($r['description']) ?></p>
                        <a href="service_details.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm w-100 mt-2">View & Book</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
