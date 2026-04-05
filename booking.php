<?php
session_start();
require 'db.php';
$pageTitle = 'Book Appointment';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=booking.php' . (isset($_GET['service']) ? '?service=' . (int)$_GET['service'] : ''));
    exit;
}

// Load all services for dropdown
$allServices = $pdo->query("
    SELECT s.*, c.name as cat_name FROM services s
    JOIN categories c ON s.category_id = c.id
    ORDER BY c.name, s.name
")->fetchAll();

// Pre-selected service
$preselected = isset($_GET['service']) ? (int)$_GET['service'] : 0;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id   = (int)($_POST['service_id'] ?? 0);
    $car_make     = trim($_POST['car_make'] ?? '');
    $car_model    = trim($_POST['car_model'] ?? '');
    $car_year     = (int)($_POST['car_year'] ?? 0);
    $car_plate    = trim($_POST['car_plate'] ?? '');
    $booking_date = $_POST['booking_date'] ?? '';
    $booking_time = $_POST['booking_time'] ?? '';
    $notes        = trim($_POST['notes'] ?? '');

    // Validate
    if (!$service_id || !$car_make || !$car_model || !$car_year || !$booking_date || !$booking_time) {
        $error = 'Please fill in all required fields.';
    } elseif ($car_year < 1950 || $car_year > (int)date('Y') + 1) {
        $error = 'Please enter a valid car year.';
    } elseif (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
        $error = 'Booking date cannot be in the past.';
    } else {
        // Check service exists
        $chk = $pdo->prepare("SELECT id FROM services WHERE id = ?");
        $chk->execute([$service_id]);
        if (!$chk->fetch()) {
            $error = 'Invalid service selected.';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO bookings (user_id, service_id, car_make, car_model, car_year, car_plate, booking_date, booking_time, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_SESSION['user_id'], $service_id,
                $car_make, $car_model, $car_year, $car_plate,
                $booking_date, $booking_time, $notes
            ]);
            $bookingId = $pdo->lastInsertId();
            header('Location: confirmation.php?id=' . $bookingId);
            exit;
        }
    }
}

// Generate time slots 08:00 – 17:00
$timeSlots = [];
for ($h = 8; $h <= 17; $h++) {
    $timeSlots[] = sprintf('%02d:00', $h);
    if ($h < 17) $timeSlots[] = sprintf('%02d:30', $h);
}
$minDate = date('Y-m-d');
$maxDate = date('Y-m-d', strtotime('+3 months'));
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none; padding:0;">
                <li class="breadcrumb-item"><a href="index.php" style="color:var(--primary);">Home</a></li>
                <li class="breadcrumb-item"><a href="categories.php" style="color:var(--primary);">Services</a></li>
                <li class="breadcrumb-item active" style="color:#aaa;">Book Appointment</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-7">
                <h2 class="section-title">BOOK AN <span>APPOINTMENT</span></h2>
                <div class="section-divider"></div>

                <?php if ($error): ?>
                    <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" novalidate class="mt-4">
                    <!-- Service selection -->
                    <div class="mb-4">
                        <label class="form-label">Select Service <span style="color:var(--primary);">*</span></label>
                        <select name="service_id" class="form-select" required>
                            <option value="">— Choose a service —</option>
                            <?php
                            $lastCat = '';
                            foreach ($allServices as $svc):
                                if ($svc['cat_name'] !== $lastCat) {
                                    if ($lastCat) echo '</optgroup>';
                                    echo '<optgroup label="' . htmlspecialchars($svc['cat_name']) . '">';
                                    $lastCat = $svc['cat_name'];
                                }
                                $selected = ($preselected == $svc['id'] || (int)($_POST['service_id'] ?? 0) === $svc['id']) ? 'selected' : '';
                            ?>
                                <option value="<?= $svc['id'] ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($svc['name']) ?> — €<?= number_format($svc['price'], 2) ?>
                                </option>
                            <?php endforeach; if ($lastCat) echo '</optgroup>'; ?>
                        </select>
                    </div>

                    <hr style="border-color:var(--border); margin: 1.5rem 0;">
                    <h5 style="font-family:'Barlow Condensed',sans-serif; margin-bottom:1rem;">
                        YOUR <span style="color:var(--primary);">CAR</span>
                    </h5>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Make <span style="color:var(--primary);">*</span></label>
                            <input type="text" name="car_make" class="form-control"
                                placeholder="e.g. Volkswagen"
                                value="<?= htmlspecialchars($_POST['car_make'] ?? '') ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Model <span style="color:var(--primary);">*</span></label>
                            <input type="text" name="car_model" class="form-control"
                                placeholder="e.g. Golf"
                                value="<?= htmlspecialchars($_POST['car_model'] ?? '') ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Year <span style="color:var(--primary);">*</span></label>
                            <input type="number" name="car_year" class="form-control"
                                placeholder="e.g. 2019" min="1950" max="<?= date('Y')+1 ?>"
                                value="<?= htmlspecialchars($_POST['car_year'] ?? '') ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Plate Number</label>
                            <input type="text" name="car_plate" class="form-control"
                                placeholder="e.g. TM 01 ABC"
                                value="<?= htmlspecialchars($_POST['car_plate'] ?? '') ?>">
                        </div>
                    </div>

                    <hr style="border-color:var(--border); margin: 1.5rem 0;">
                    <h5 style="font-family:'Barlow Condensed',sans-serif; margin-bottom:1rem;">
                        DATE & <span style="color:var(--primary);">TIME</span>
                    </h5>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Preferred Date <span style="color:var(--primary);">*</span></label>
                            <input type="date" name="booking_date" class="form-control"
                                min="<?= $minDate ?>" max="<?= $maxDate ?>"
                                value="<?= htmlspecialchars($_POST['booking_date'] ?? '') ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Preferred Time <span style="color:var(--primary);">*</span></label>
                            <select name="booking_time" class="form-select" required>
                                <option value="">— Select time —</option>
                                <?php foreach ($timeSlots as $slot): ?>
                                    <option value="<?= $slot ?>"
                                        <?= (($_POST['booking_time'] ?? '') === $slot) ? 'selected' : '' ?>>
                                        <?= $slot ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Any specific issues, symptoms, or requests..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg mt-4 w-100">
                        <i class="fas fa-calendar-check me-2"></i>Confirm Booking
                    </button>
                </form>
            </div>

            <!-- Info sidebar -->
            <div class="col-lg-5">
                <div class="card p-4 mb-4">
                    <h5 style="font-family:'Barlow Condensed',sans-serif; font-size:1.2rem; margin-bottom:1rem;">
                        HOW IT <span style="color:var(--primary);">WORKS</span>
                    </h5>
                    <?php
                    $steps = [
                        ['1', 'Select your service and fill in your car details.'],
                        ['2', 'Choose a date and time that suits you.'],
                        ['3', 'Receive a booking confirmation on screen.'],
                        ['4', 'Bring your car in at the scheduled time.'],
                    ];
                    foreach ($steps as [$num, $desc]): ?>
                    <div class="d-flex mb-3">
                        <div style="width:32px;height:32px;background:var(--primary);color:#fff;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1rem;display:flex;align-items:center;justify-content:center;border-radius:50%;flex-shrink:0;margin-right:1rem;">
                            <?= $num ?>
                        </div>
                        <div style="color:#ccc; font-size:0.9rem; padding-top:4px;"><?= $desc ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="card p-4">
                    <h5 style="font-family:'Barlow Condensed',sans-serif; font-size:1.2rem; margin-bottom:1rem;">
                        OPENING <span style="color:var(--primary);">HOURS</span>
                    </h5>
                    <table style="width:100%; color:#ccc; font-size:0.9rem;">
                        <tr><td>Mon – Fri</td><td class="text-end">08:00 – 18:00</td></tr>
                        <tr><td>Saturday</td><td class="text-end">09:00 – 14:00</td></tr>
                        <tr><td style="color:var(--muted);">Sunday</td><td class="text-end" style="color:var(--muted);">Closed</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
