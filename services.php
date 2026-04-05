<?php
session_start();
require 'db.php';
$pageTitle = 'Services';

$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

if ($categoryId) {
    $catStmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $catStmt->execute([$categoryId]);
    $category = $catStmt->fetch();
    if (!$category) {
        header('Location: categories.php');
        exit;
    }
    $services = $pdo->prepare("SELECT * FROM services WHERE category_id = ? ORDER BY price ASC");
    $services->execute([$categoryId]);
    $services = $services->fetchAll();
    $pageTitle = htmlspecialchars($category['name']);
} else {
    // Show all services
    $category = null;
    $services = $pdo->query("
        SELECT s.*, c.name as cat_name FROM services s
        JOIN categories c ON s.category_id = c.id
        ORDER BY c.name, s.price ASC
    ")->fetchAll();
}
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none; padding:0;">
                <li class="breadcrumb-item"><a href="index.php" style="color:var(--primary);">Home</a></li>
                <li class="breadcrumb-item"><a href="categories.php" style="color:var(--primary);">Services</a></li>
                <?php if ($category): ?>
                <li class="breadcrumb-item active" style="color:#aaa;"><?= htmlspecialchars($category['name']) ?></li>
                <?php endif; ?>
            </ol>
        </nav>

        <h2 class="section-title">
            <?php if ($category): ?>
                <?= strtoupper(htmlspecialchars($category['name'])) ?> <span>SERVICES</span>
            <?php else: ?>
                ALL <span>SERVICES</span>
            <?php endif; ?>
        </h2>
        <div class="section-divider"></div>

        <?php if ($category): ?>
        <p style="color:#aaa; margin-bottom:2rem;"><?= htmlspecialchars($category['description']) ?></p>
        <?php endif; ?>

        <?php if (empty($services)): ?>
            <div class="alert alert-danger">No services found in this category.</div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($services as $svc): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title"><?= htmlspecialchars($svc['name']) ?></h5>
                        <span class="badge-price ms-2 flex-shrink-0">€<?= number_format($svc['price'], 2) ?></span>
                    </div>
                    <?php if (!$category && isset($svc['cat_name'])): ?>
                        <div style="color:var(--primary); font-size:0.8rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.5rem;">
                            <?= htmlspecialchars($svc['cat_name']) ?>
                        </div>
                    <?php endif; ?>
                    <p style="color:#aaa; font-size:0.9rem; flex-grow:1;">
                        <?= htmlspecialchars($svc['description']) ?>
                    </p>
                    <div style="color:var(--muted); font-size:0.85rem; margin-bottom:1rem;">
                        <i class="fas fa-clock me-1"></i>
                        <?= $svc['duration_minutes'] >= 60
                            ? floor($svc['duration_minutes']/60) . 'h ' . ($svc['duration_minutes']%60 ? $svc['duration_minutes']%60 . 'min' : '')
                            : $svc['duration_minutes'] . ' min' ?>
                    </div>
                    <a href="service_details.php?id=<?= $svc['id'] ?>" class="btn btn-primary w-100">
                        View Details & Book
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="text-center mt-5">
            <a href="categories.php" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
