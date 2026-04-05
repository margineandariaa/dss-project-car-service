<footer class="py-4 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="fw-bold fs-5" style="font-family:'Barlow Condensed',sans-serif; color:#fff;">
                    AUTO<span style="color:var(--primary);">SERV</span>
                </div>
                <div class="mt-1">Professional car service & repair.</div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0 text-md-center">
                <a href="index.php" class="me-3">Home</a>
                <a href="categories.php" class="me-3">Services</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="my_appointments.php">My Appointments</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-end">
                <i class="fas fa-phone me-2"></i>+40 721 000 000<br>
                <i class="fas fa-envelope me-2"></i>contact@autoserv.ro
            </div>
        </div>
        <hr style="border-color:var(--border); margin-top:1.5rem;">
        <div class="text-center" style="font-size:0.8rem;">
            &copy; <?= date('Y') ?> AutoServ. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
