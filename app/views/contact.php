<div class="container mt-4">
    <h1>Formular de Contact</h1>
    <p>Te rugăm să completezi informațiile de mai jos:</p>
    <?php if (!empty($returnMsg)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($returnMsg); ?></div>
    <?php endif; ?>
    <div id="message">
        <form action="/contact/submit" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nume:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Telefon:</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Mesaj:</label>
                <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="g-recaptcha mb-3" data-sitekey="6Lc5Za0qAAAAAPyDHRG7cmn3RkaL0YiwD48q04a5"></div>
            <button type="submit" name="submit" class="btn btn-primary">Trimite</button>
        </form>
    </div>
</div>