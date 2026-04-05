<?php
session_start();
require 'db.php';
$pageTitle = 'Login';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!$email || !$pass) {
        $error = 'Please enter your email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $redirect = $_GET['redirect'] ?? 'index.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card p-4">
                    <h2 class="section-title mb-1">SIGN <span>IN</span></h2>
                    <div class="section-divider"></div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger mt-3"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if (isset($_GET['redirect'])): ?>
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-lock me-2"></i>Please log in to book an appointment.
                        </div>
                    <?php endif; ?>

                    <form method="POST" novalidate class="mt-3">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                placeholder="you@example.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Log In
                        </button>
                    </form>
                    <p class="text-center mt-3" style="color:var(--muted); font-size:0.9rem;">
                        No account yet? <a href="register.php" style="color:var(--primary);">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
