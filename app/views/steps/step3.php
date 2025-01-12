<div class="container mt-4">
    <div id="step3">
        <h2>Camere Disponibile</h2>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error); ?> 
            </div>
        <?php endif; ?>
        <form method="post" action="/book?step=3">
        <div class="row" id="available-rooms">
            <?php if (isset($rooms) && count($rooms) > 0): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="col-md-6 mb-2">
                        <div class="card" style="height: 100%;">
                            <img src="<?= htmlspecialchars($room['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($room['name']); ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($room['name']); ?></h5>
                                <p class="card-text m-0"><?= htmlspecialchars($room['description']); ?></p>
                                <p class="card-text m-0"><strong>Capacity:</strong> <?= htmlspecialchars($room['capacity']); ?> people</p>
                                <p class="card-text m-0"><strong>Floor:</strong> <?= htmlspecialchars($room['floor']); ?></p>
                                <p class="m-0"><strong>Facilities:</strong> <?= htmlspecialchars($room['facilities']) ?: 'N/A'; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-text m-0"><strong>Price per night:</strong> $<?= htmlspecialchars($room['price_per_night']); ?></p>
                                    <input type="checkbox" name="selected_room[]"
                                           value="<?= $room['id']; ?>"
                                           class="select-room-checkbox" style="transform: scale(1.5);"
                                           <?= isset($data['selected_room']) && in_array($room['id'], $data['selected_room']) ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No rooms available for the selected dates and number of guests.</p>
            <?php endif; ?>
        </div>

        <a href="/book?step=<?= $step - 1 ?>" class="prev-step btn btn-secondary">Anterior</a>
        <button class="next-step btn btn-primary mt-3" type="submit">Următorul</button>
        </form>
    </div>
</div>
