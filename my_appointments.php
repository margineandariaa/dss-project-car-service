<?php
session_start();
require 'db.php';
$pageTitle = 'My Appointments';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=my_appointments.php');
    exit;
}

// Handle cancellation
if (isset($_POST['cancel_id'])) {
    $cancelId = (int)$_POST['cancel_id'];
    $stmt = $pdo->prepare("UPDATE bookings SET status='cancelled' WHERE id=? AND user_id=? AND status='pending'");
    $stmt->execute([$cancelId, $_SESSION['user_id']]);
}

// Fetch all bookings for this user
$bookings = $pdo->prepare("
    SELECT b.*, s.name as service_name, s.price, s.duration_minutes, c.name as cat_name
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    JOIN categories c ON s.category_id = c.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC, b.booking_time DESC
");
$bookings->execute([$_SESSION['user_id']]);
$bookings = $bookings->fetchAll();

$statusColors = [
    'pending'   => '#f0ad4e',
    'confirmed' => '#28a745',
    'completed' => '#6c757d',
    'cancelled' => '#dc3545',
];
$statusIcons = [
    'pending'   => 'fa-clock',
    'confirmed' => 'fa-check',
    'completed' => 'fa-flag-checkered',
    'cancelled' => 'fa-times',
];
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none; padding:0;">
                <li class="breadcrumb-item"><a href="index.php" style="color:var(--primary);">Home</a></li>
                <li class="breadcrumb-item active" style="color:#aaa;">My Appointments</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="section-title mb-0">MY <span>APPOINTMENTS</span></h2>
                <div class="section-divider"></div>
            </div>
            <a href="booking.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Booking
            </a>
        </div>

        <?php if (empty($bookings)): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times" style="font-size:3rem; color:var(--muted); margin-bottom:1.5rem;"></i>
                <h4 style="color:#aaa;">You have no appointments yet.</h4>
                <p style="color:var(--muted);">Book a service to get started.</p>
                <a href="categories.php" class="btn btn-primary mt-2">Browse Services</a>
            </div>
        <?php else: ?>

        <!-- Summary stats -->
        <?php
        $totals = ['pending'=>0,'confirmed'=>0,'completed'=>0,'cancelled'=>0];
        foreach ($bookings as $b) $totals[$b['status']]++;
        ?>
        <div class="row g-3 mb-4">
            <?php foreach ($totals as $status => $count): ?>
            <div class="col-6 col-md-3">
                <div class="card p-3 text-center">
                    <div style="font-family:'Barlow Condensed',sans-serif; font-size:2rem; font-weight:800; color:<?= $statusColors[$status] ?>;">
                        <?= $count ?>
                    </div>
                    <div style="color:var(--muted); font-size:0.85rem; text-transform:capitalize;"><?= $status ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex flex-column gap-3">
            <?php foreach ($bookings as $booking):
                $isPast = strtotime($booking['booking_date']) < strtotime(date('Y-m-d'));
                $color  = $statusColors[$booking['status']] ?? '#888';
                $icon   = $statusIcons[$booking['status']] ?? 'fa-calendar';
            ?>
            <div class="card p-4" style="border-left:4px solid <?= $color ?>;">
                <div class="row align-items-center g-3">
                    <div class="col-md-5">
                        <div style="color:var(--muted); font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.25rem;">
                            #<?= str_pad($booking['id'], 5, '0', STR_PAD_LEFT) ?> &bull; <?= htmlspecialchars($booking['cat_name']) ?>
                        </div>
                        <div style="font-family:'Barlow Condensed',sans-serif; font-size:1.3rem; font-weight:700;">
                            <?= htmlspecialchars($booking['service_name']) ?>
                        </div>
                        <div style="color:#aaa; font-size:0.9rem; margin-top:0.25rem;">
                            <?= htmlspecialchars($booking['car_make'] . ' ' . $booking['car_model']) ?>
                            (<?= $booking['car_year'] ?>)
                            <?php if ($booking['car_plate']): ?>
                                &bull; <?= htmlspecialchars($booking['car_plate']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 text-md-center">
                        <div style="color:var(--muted); font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase;">Date</div>
                        <div style="font-weight:600;"><?= date('d M Y', strtotime($booking['booking_date'])) ?></div>
                        <div style="color:#aaa; font-size:0.85rem;"><?= substr($booking['booking_time'], 0, 5) ?></div>
                    </div>
                    <div class="col-6 col-md-2 text-md-center">
                        <div style="color:var(--muted); font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase;">Price</div>
                        <div style="font-family:'Barlow Condensed',sans-serif; font-size:1.3rem; font-weight:800; color:var(--primary);">
                            €<?= number_format($booking['price'], 2) ?>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 text-md-center">
                        <span style="display:inline-flex; align-items:center; gap:0.4rem; background:rgba(0,0,0,0.3); border:1px solid <?= $color ?>; color:<?= $color ?>; padding:0.3rem 0.75rem; border-radius:20px; font-size:0.85rem; font-weight:600; text-transform:capitalize;">
                            <i class="fas <?= $icon ?>"></i> <?= $booking['status'] ?>
                        </span>
                    </div>
                    <div class="col-6 col-md-1 text-md-center">
                        <?php if ($booking['status'] === 'pending' && !$isPast): ?>
                        <form method="POST" onsubmit="return confirm('Cancel this appointment?');">
                            <input type="hidden" name="cancel_id" value="<?= $booking['id'] ?>">
                            <button type="submit" class="btn btn-sm"
                                style="background:transparent; border:1px solid #555; color:#aaa; font-size:0.8rem;"
                                title="Cancel">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($booking['notes']): ?>
                <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid var(--border); color:#aaa; font-size:0.85rem;">
                    <i class="fas fa-comment-alt me-2" style="color:var(--primary);"></i>
                    <?= htmlspecialchars($booking['notes']) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
