<div class="container mt-4">
<?php if (isset($stepper)) echo $stepper; ?>
    <div id="step3">
        <h2>Selectează Addons</h2>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form method="post" action="/book?step=4">
        <input type="hidden" name="current_step" value="4">

            <div class="row" id="available-rooms">
                <?php if (isset($addons) && count($addons) > 0): ?>
                    <?php foreach ($addons as $addon): ?>
                        <div class="col-md-6 mb-2">
                            <div class="card" style="height: 100%;">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($addon['name']); ?></h5>
                                    <p class="card-text"><strong>Price:</strong> $<?= htmlspecialchars($addon['price']); ?></p>
                                    <input type="checkbox" name="selected_addons[]"
                                        value="<?= $addon['id']; ?>"
                                        class="select-room-checkbox" style="transform: scale(1.5);"
                                        <?= isset($data['selected_addons']) && in_array(
                                            ['id' => $addon['id'], 'name' => $addon['name'], 'price' => $addon['price']],
                                            $data['selected_addons']
                                        ) ? 'checked' : ''; ?>>

                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nu avem camere disponibile.</p>
                <?php endif; ?>
            </div>

            <a href="/book?step=<?= $step - 1 ?>" class="prev-step btn btn-secondary">Anterior</a>
            <button class="next-step btn btn-primary" type="submit">Următorul</button>
        </form>
    </div>
</div>