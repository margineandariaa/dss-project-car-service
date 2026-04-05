<?php
session_start();
require 'db.php';
$pageTitle = 'Service Categories';

$categories = $pdo->query("
    SELECT c.*, COUNT(s.id) as service_count
    FROM categories c
    LEFT JOIN services s ON s.category_id = c.id
    GROUP BY c.id
")->fetchAll();
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none; padding:0;">
                <li class="breadcrumb-item"><a href="index.php" style="color:var(--primary);">Home</a></li>
                <li class="breadcrumb-item active" style="color:#aaa;">Services</li>
            </ol>
        </nav>

        <h2 class="section-title">SERVICE <span>CATEGORIES</span></h2>
        <div class="section-divider"></div>
        <p style="color:#aaa; margin-bottom:2.5rem;">
            Choose a category to see all available services and pricing.
        </p>

        <div class="row g-4">
            <?php foreach ($categories as $cat): ?>
            <div class="col-md-6 col-lg-4">
                <a href="services.php?category=<?= $cat['id'] ?>" class="text-decoration-none">
                    <div class="card h-100 p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div style="width:56px;height:56px;background:rgba(230,57,70,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:1rem;flex-shrink:0;">
                                <i class="fas <?= htmlspecialchars($cat['icon'] ?? 'fa-wrench') ?>" style="font-size:1.5rem;color:var(--primary);"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0"><?= htmlspecialchars($cat['name']) ?></h5>
                                <span style="color:var(--muted); font-size:0.85rem;">
                                    <?= $cat['service_count'] ?> service<?= $cat['service_count'] != 1 ? 's' : '' ?>
                                </span>
                            </div>
                        </div>
                        <p style="color:#aaa; font-size:0.9rem; flex-grow:1;">
                            <?= htmlspecialchars($cat['description']) ?>
                        </p>
                        <div style="color:var(--primary); font-family:'Barlow Condensed',sans-serif; font-weight:700; font-size:0.95rem; letter-spacing:0.05em;">
                            VIEW SERVICES <i class="fas fa-arrow-right ms-1"></i>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
