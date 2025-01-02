<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center">
            <h1>
                <?php echo htmlspecialchars($room['name']); ?>
                <?php if ($room['popular']): ?>
                    <span class="text-warning">&#9733;</span> <!-- Star icon -->
                <?php endif; ?>
            </h1>
        </div>
        <div class="card-body text-center">
            <div id="roomCarousel" class="carousel slide m-auto mb-4" data-bs-ride="carousel" style="width: fit-content;">
                <div class="carousel-inner">
                    <?php foreach ($room['images'] as $index => $image): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-auto" alt="Room Image" style="height: 268px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($room['images']) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev" style="height: 268px;">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next" style="height: 268px;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                <?php endif; ?>
            </div>
            <p class="my-2"><strong>Description:</strong> <?php echo htmlspecialchars($room['description']); ?></p>
            <p class="my-2"><strong>Price per night:</strong> $<?php echo htmlspecialchars($room['price_per_night']); ?></p>
            <p class="my-2"><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?> people</p>
            <p class="my-2"><strong>Floor:</strong> <?php echo htmlspecialchars($room['floor']); ?></p>
            <p class="my-2"><strong>Facilities:</strong> <?php echo htmlspecialchars($room['facilities']); ?></p>
            <div class="text-center mt-4">
                <a href="#" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>
</div>
