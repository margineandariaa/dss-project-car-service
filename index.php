<?php
session_start();
require 'db.php';
$pageTitle = 'Home';

// Fetch featured categories
$cats = $pdo->query("SELECT * FROM categories LIMIT 6")->fetchAll();

// Fetch a few featured services
$featured = $pdo->query("SELECT s.*, c.name as cat_name FROM services s JOIN categories c ON s.category_id = c.id ORDER BY RAND() LIMIT 3")->fetchAll();
?>
<?php include 'includes/header.php'; ?>

<!-- Hero -->
<section style="background: linear-gradient(135deg, #0d0d0d 60%, #1a0404 100%); padding: 100px 0 80px; position:relative; overflow:hidden;">
    <div style="position:absolute;top:0;right:0;width:45%;height:100%;opacity:0.05;background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔧</text></svg>') center/cover no-repeat;"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div style="font-family:'Barlow Condensed',sans-serif; font-size:1rem; font-weight:700; letter-spacing:0.15em; color:var(--primary); text-transform:uppercase; margin-bottom:1rem;">
                    Professional Auto Care
                </div>
                <h1 style="font-family:'Barlow Condensed',sans-serif; font-size:clamp(2.8rem,6vw,5rem); font-weight:800; line-height:1.05; margin-bottom:1.5rem;">
                    YOUR CAR DESERVES<br><span style="color:var(--primary);">THE BEST SERVICE</span>
                </h1>
                <p style="font-size:1.15rem; color:#bbb; max-width:520px; margin-bottom:2rem; line-height:1.7;">
                    Book an appointment online in minutes. Expert mechanics, transparent pricing, and fast turnaround for all makes and models.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="categories.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-tools me-2"></i>View Services
                    </a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-outline-light btn-lg">
                        Create Account
                    </a>
                    <?php else: ?>
                    <a href="my_appointments.php" class="btn btn-outline-light btn-lg">
                        My Appointments
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats bar -->
<section style="background:var(--primary); padding:1.2rem 0;">
    <div class="container">
        <div class="row text-center text-white g-2">
            <div class="col-6 col-md-3">
                <div style="font-family:'Barlow Condensed',sans-serif; font-size:2rem; font-weight:800;">5000+</div>
                <div style="font-size:0.85rem; opacity:0.9;">Cars Serviced</div>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-family:'Barlow Condensed',sans-serif; font-size:2rem; font-weight:800;">18</div>
                <div style="font-size:0.85rem; opacity:0.9;">Services Available</div>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-family:'Barlow Condensed',sans-serif; font-size:2rem; font-weight:800;">12+</div>
                <div style="font-size:0.85rem; opacity:0.9;">Expert Mechanics</div>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-family:'Barlow Condensed',sans-serif; font-size:2rem; font-weight:800;">4.9 ★</div>
                <div style="font-size:0.85rem; opacity:0.9;">Customer Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- Service Categories -->
<section class="py-5 mt-3">
    <div class="container">
        <h2 class="section-title">OUR <span>CATEGORIES</span></h2>
        <div class="section-divider"></div>
        <div class="row g-3 mt-2">
            <?php
            $icons = ['fa-cogs','fa-circle-notch','fa-stop-circle','fa-bolt','fa-paint-brush','fa-snowflake'];
            $i = 0;
            foreach ($cats as $cat): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="services.php?category=<?= $cat['id'] ?>" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div style="font-size:2rem; color:var(--primary); margin-bottom:0.75rem;">
                            <i class="fas <?= htmlspecialchars($cat['icon'] ?? $icons[$i % count($icons)]) ?>"></i>
                        </div>
                        <div class="card-title" style="font-size:1rem;"><?= htmlspecialchars($cat['name']) ?></div>
                    </div>
                </a>
            </div>
            <?php $i++; endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="categories.php" class="btn btn-outline-light">
                <i class="fas fa-th-large me-2"></i>All Service Categories
            </a>
        </div>
    </div>
</section>

<!-- Featured Services -->
<section class="py-5" style="background:var(--dark2);">
    <div class="container">
        <h2 class="section-title">FEATURED <span>SERVICES</span></h2>
        <div class="section-divider"></div>
        <div class="row g-4 mt-2">
            <?php foreach ($featured as $svc): ?>
            <div class="col-md-4">
                <div class="card h-100 p-3">
                    <div style="background:var(--dark3); border-radius:6px; padding:1.5rem; margin-bottom:1rem; text-align:center;">
                        <i class="fas fa-wrench" style="font-size:2.5rem; color:var(--primary);"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($svc['name']) ?></h5>
                        <span class="badge-price">€<?= number_format($svc['price'], 2) ?></span>
                    </div>
                    <p style="color:#aaa; font-size:0.9rem; flex-grow:1;"><?= htmlspecialchars($svc['description']) ?></p>
                    <div style="color:var(--muted); font-size:0.85rem; margin-bottom:1rem;">
                        <i class="fas fa-clock me-1"></i><?= $svc['duration_minutes'] ?> min
                        &nbsp;&bull;&nbsp;
                        <span style="color:#aaa;"><?= htmlspecialchars($svc['cat_name']) ?></span>
                    </div>
                    <a href="service_details.php?id=<?= $svc['id'] ?>" class="btn btn-primary w-100">View & Book</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why choose us -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">WHY <span>CHOOSE US</span></h2>
        <div class="section-divider mx-auto"></div>
        <div class="row g-4 mt-3 text-center">
            <?php
            $perks = [
                ['fa-calendar-check', 'Easy Online Booking', 'Book an appointment any time, from any device, in under 2 minutes.'],
                ['fa-euro-sign', 'Transparent Pricing', 'No hidden fees. All service prices shown upfront before you book.'],
                ['fa-user-cog', 'Certified Mechanics', 'All our technicians are fully certified and experienced professionals.'],
                ['fa-shield-alt', 'Service Guarantee', 'All services come with a 30-day satisfaction guarantee.'],
            ];
            foreach ($perks as [$icon, $title, $desc]):
            ?>
            <div class="col-6 col-md-3">
                <div style="font-size:2rem; color:var(--primary); margin-bottom:1rem;">
                    <i class="fas <?= $icon ?>"></i>
                </div>
                <h5 style="font-family:'Barlow Condensed',sans-serif; font-size:1.2rem;"><?= $title ?></h5>
                <p style="color:#aaa; font-size:0.9rem;"><?= $desc ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="background:var(--primary); padding:60px 0;">
    <div class="container text-center text-white">
        <h2 style="font-family:'Barlow Condensed',sans-serif; font-size:2.5rem; font-weight:800;">READY TO BOOK YOUR SERVICE?</h2>
        <p style="opacity:0.9; font-size:1.1rem; margin-bottom:2rem;">Choose a service and schedule your appointment today.</p>
        <a href="categories.php" class="btn btn-lg" style="background:#fff; color:var(--primary); font-family:'Barlow Condensed',sans-serif; font-weight:800; font-size:1.1rem; letter-spacing:0.05em;">
            <i class="fas fa-tools me-2"></i>BROWSE SERVICES
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
