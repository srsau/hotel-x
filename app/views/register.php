<form method="POST" action="/register">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Nume</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Parolă</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="preferred_currency" class="form-label">Monedă Preferată</label>
        <select class="form-control" id="preferred_currency" name="preferred_currency">
            <?php foreach ($currencies as $currency): ?>
                <option value="<?php echo htmlspecialchars($currency); ?>"><?php echo htmlspecialchars($currency); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Înregistrează-te</button>
</form>