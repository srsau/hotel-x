<div class="container-md mt-4">
    <h1 class="text-center">Welcome to Hotel X</h1>
    <h2 class="text-center">Available Rooms</h2>
    <div class="row">
        <?php foreach ($rooms as $room): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($room['image_url'] ?? ''); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($room['name']); ?>" style="height: 268px; width: auto;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($room['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($room['description']); ?></p>
                        <p class="card-text"><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                        <p class="card-text"><strong>Facilities:</strong> 
                            <?php echo htmlspecialchars($room['facilities']); ?>
                        </p>
                        <a href="/room?id=<?php echo $room['id']; ?>" class="btn btn-primary">Details</a>
                        <a href="#" class="btn btn-secondary">Book</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>