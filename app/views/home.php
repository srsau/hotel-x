<?php
    require_once __DIR__ . '/../helpers/convertPrice.php';
    $preferred_currency = $_SESSION['preferred_currency'];
    ?>
<div class="container-md mt-4">
    <h2 class="text-center mb-4">Camerele Noastre</h2>
    <div class="row">
        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <div class="col-12 mb-4 text-end">
                <a href="/room/create" class="btn btn-success">Creează Cameră</a>
            </div>
        <?php endif; ?>
        <?php foreach ($rooms as $room): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($room['image_url'] ?? ''); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($room['name']); ?>" style="height: 268px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($room['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($room['description']); ?></p>
                        <p class="card-text"><strong>Capacitate:</strong> <?php echo htmlspecialchars($room['capacity']); ?> persoane</p>
                        <p class="my-2"><strong>Facilități:</strong> <?php echo isset($room['facilities']) ? htmlspecialchars($room['facilities']) : 'N/A'; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="card-text m-0"><strong>Preț pe noapte:</strong> <?php echo htmlspecialchars(convertPrice($room['price_per_night'],$preferred_currency)); ?></p>
                            <div>
                                <a href="/room?id=<?php echo $room['id']; ?>" class="btn btn-primary">Detalii</a>
                                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                                    <a href="/room/edit?id=<?php echo $room['id']; ?>" class="btn btn-warning">Editează</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>