<?php
session_start();
require 'db.php';
$pageTitle = 'Booking Confirmed';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$bookingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$bookingId) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT b.*, s.name as service_name, s.price, s.duration_minutes,
           c.name as cat_name, u.name as user_name, u.email as user_email
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    JOIN categories c ON s.category_id = c.id
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ? AND b.user_id = ?
");
$stmt->execute([$bookingId, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    header('Location: index.php');
    exit;
}
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <!-- Success banner -->
                <div class="text-center mb-5">
                    <div style="width:80px;height:80px;background:rgba(40,167,69,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                        <i class="fas fa-check-circle" style="font-size:2.5rem;color:#28a745;"></i>
                    </div>
                    <h2 class="section-title" style="font-size:2.8rem;">BOOKING <span>CONFIRMED!</span></h2>
                    <p style="color:#aaa; font-size:1.1rem; margin-top:0.5rem;">
                        Your appointment has been successfully booked. See you soon!
                    </p>
                    <div style="display:inline-block; background:var(--dark3); border:1px solid var(--border); border-radius:6px; padding:0.4rem 1.2rem; color:var(--muted); font-size:0.85rem; margin-top:0.5rem;">
                        Booking Reference: <strong style="color:var(--primary);">#<?= str_pad($booking['id'], 5, '0', STR_PAD_LEFT) ?></strong>
                    </div>
                </div>

                <!-- Booking summary card -->
                <div class="card p-4">
                    <h5 style="font-family:'Barlow Condensed',sans-serif; font-size:1.3rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border); padding-bottom:1rem;">
                        BOOKING <span style="color:var(--primary);">SUMMARY</span>
                    </h5>

                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div style="color:var(--muted); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.25rem;">Service</div>
                            <div style="font-weight:600; color:#fff;"><?= htmlspecialchars($booking['service_name']) ?></div>
                            <div style="color:var(--muted); font-size:0.85rem;"><?= htmlspecialchars($booking['cat_name']) ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div style="color:var(--muted); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.25rem;">Price</div>
                            <div style="font-family:'Barlow Condensed',sans-serif; font-size:1.6rem; font-weight:800; color:var(--primary);">
                                €<?= number_format($booking['price'], 2) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div style="color:var(--muted); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.25rem;">Date & Time</div>
                            <div style="font-weight:600;">
                                <?= date('D, d M Y', strtotime($booking['booking_date'])) ?>
                            </div>
                            <div style="color:#aaa;"><?= substr($booking['booking_time'], 0, 5) ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div style="color:var(--muted); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.25rem;">Duration</div>
                            <div style="font-weight:600;">
                                <?= $booking['duration_minutes'] >= 60
                                    ? floor($booking['duration_minutes']/60) . 'h ' . ($booking['duration_minutes']%60 ? $booking['duration_minutes']%60 . 'm' : '')
                                    : $booking['duration_minutes'] . ' min' ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr style="border-color:var(--border);">
                        </div>
                        <div class="col-sm-6">
                            <div style="color:var(--muted); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.25rem;">Vehicle</div>
                            <div style="font-weight:600;"><?= htmlspecialchars($booking['car_make'] . ' ' . $booking['car_model']) ?></div>
                            <div style="color:#aaa; font-size:0.85rem;">
                                <?= $booking['car_year'] ?>
                                <?php if ($booking['car_plate']): ?> &bull; <?= htmlspecialchars($booking['car_plate']) ?><?php endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div style="color:var(--muted); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.25rem;">Customer</div>
                            <div style="font-weight:600;"><?= htmlspecialchars($booking['user_name']) ?></div>
                            <div style="color:#aaa; font-size:0.85rem;"><?= htmlspecialchars($booking['user_email']) ?></div>
                        </div>
                        <?php if ($booking['notes']): ?>
                        <div class="col-12">
                            <div style="color:var(--muted); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:0.25rem;">Notes</div>
                            <div style="color:#ccc; font-size:0.9rem;"><?= htmlspecialchars($booking['notes']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div style="margin-top:1.5rem; padding:1rem; background:rgba(230,57,70,0.08); border:1px solid rgba(230,57,70,0.25); border-radius:6px; font-size:0.9rem; color:#ccc;">
                        <i class="fas fa-info-circle me-2" style="color:var(--primary);"></i>
                        Please arrive 5–10 minutes before your scheduled appointment time. Bring your vehicle's documentation if available.
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4 flex-wrap">
                    <a href="my_appointments.php" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>My Appointments
                    </a>
                    <a href="categories.php" class="btn btn-outline-light">
                        <i class="fas fa-tools me-2"></i>Book Another Service
                    </a>
                    <a href="index.php" class="btn btn-outline-light">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
