<div class="container mt-4">
    <div id="step1">
        <h2>Selectează Datele</h2>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/book?step=1">

            <label for="start-date">Data Începerii:</label>
            <input type="date" 
                   id="start-date" 
                   name="start-date" 
                   class="form-control" 
                   value="<?= isset($data['start-date']) ? htmlspecialchars($data['start-date']) : ''; ?>" 
                   required> 

            <label for="end-date">Data Încheierii:</label>
            <input type="date" 
                   id="end-date" 
                   name="end-date" 
                   class="form-control" 
                   value="<?= isset($data['end-date']) ? htmlspecialchars($data['end-date']) : ''; ?>" 
                   required> 

            <button type="submit" class="next-step btn btn-primary mt-3">Următorul</button>
        </form>
    </div>
</div>
