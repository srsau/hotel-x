<div class="container mt-4">
<?php if (isset($stepper)) echo $stepper; ?>
    <div id="step2">
        <h2>Selectează Oaspeții</h2>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/book?step=2">
        <input type="hidden" name="current_step" value="2">

            <label for="guests">Numărul de Oaspeți:</label>
            <input type="number" 
                   id="guests" 
                   name="guests" 
                   class="form-control" 
                   value="<?= isset($data['guests']) ? htmlspecialchars($data['guests']) : ''; ?>" 
                   min="1" max="12" required>

            <div class="mt-3">
                <a href="/book?step=<?= $step - 1 ?>" class="prev-step btn btn-secondary">Anterior</a>
                <button type="submit" class="next-step btn btn-primary">Următorul</button>
                </div>
        </form>
    </div>
</div>
