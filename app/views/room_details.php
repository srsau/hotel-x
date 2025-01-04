<?php
    require_once __DIR__ . '/../helpers/convertPrice.php';
    $preferred_currency = $_SESSION['preferred_currency'];
    $is_authenticated = isset($_SESSION['user']['id']);
    ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center">
            <h1>
            <?php echo htmlspecialchars($room['name']); ?>
            <?php if ($room['popular']): ?>
                    <span class="text-warning">&#9733;</span>
                <?php endif; ?>
            </h1>
        </div>
        <div class="card-body text-center">
            <div id="roomCarousel" class="carousel slide m-auto mb-4" data-bs-ride="carousel" style="width: fit-content;">
                <div class="carousel-inner">
                    <?php foreach ($room['images'] as $index => $image): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-auto" alt="Imagine Cameră" style="height: 268px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($room['images']) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev" style="height: 268px;">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next" style="height: 268px;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Următor</span>
                    </button>
                <?php endif; ?>
            </div>
            <p class="my-2"><strong>Descriere:</strong> <?php echo htmlspecialchars($room['description']); ?></p>
            <p class="my-2"><strong>Preț pe noapte:</strong> <?php echo htmlspecialchars(convertPrice($room['price_per_night'], $preferred_currency)); ?></p>
            <p class="my-2"><strong>Capacitate:</strong> <?php echo htmlspecialchars($room['capacity']); ?> persoane</p>
            <p class="my-2"><strong>Etaj:</strong> <?php echo htmlspecialchars($room['floor']); ?></p>
            <p class="my-2"><strong>Facilități:</strong> <?php echo isset($room['facilities']) ? htmlspecialchars(implode(', ', $room['facilities'])) : 'N/A'; ?></p>
            <?php if ($is_authenticated): ?>
                <p class="my-2"><strong>Camere disponibile in prezent:</strong> <?php echo htmlspecialchars($room['available_rooms']); ?></p>
            <?php endif; ?>
            <div class="text-center mt-4">
                <a href="#" class="btn btn-primary">Rezervă acum</a>
            </div>
        </div>
    </div>
</div>
