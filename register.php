<?php
session_start();
require 'db.php';
$pageTitle = 'Register';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (!$name || !$email || !$pass || !$pass2) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($pass !== $pass2) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'An account with that email already exists.';
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $hashed]);
            $success = 'Account created! You can now <a href="login.php" style="color:var(--primary);">log in</a>.';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card p-4">
                    <h2 class="section-title mb-1">CREATE <span>ACCOUNT</span></h2>
                    <div class="section-divider"></div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger mt-3"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success mt-3"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="POST" novalidate class="mt-3">
                        <div class="mb-3">
                            <label class="form-label">Full Name <span style="color:var(--primary);">*</span></label>
                            <input type="text" name="name" class="form-control"
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                placeholder="John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address <span style="color:var(--primary);">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                placeholder="you@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                placeholder="+40 721 000 000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span style="color:var(--primary);">*</span></label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Min. 6 characters" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm Password <span style="color:var(--primary);">*</span></label>
                            <input type="password" name="password2" class="form-control"
                                placeholder="Repeat password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>
                    <p class="text-center mt-3" style="color:var(--muted); font-size:0.9rem;">
                        Already have an account? <a href="login.php" style="color:var(--primary);">Log in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
