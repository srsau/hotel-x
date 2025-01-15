<form method="POST" action="/login">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" id="error-message">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Parolă</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <button type="submit" class="btn btn-primary">Autentificare</button>
</form>