<div class="container mt-4">
    <h2><?php echo $title; ?></h2>
    <?php if (isset($error) && $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Nume Cameră</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($room['name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descriere</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($room['description'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price_per_night" class="form-label">Preț pe Noapte (USD)</label>
            <input type="number" step="0.01" class="form-control" id="price_per_night" name="price_per_night" value="<?php echo htmlspecialchars($room['price_per_night'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacitate</label>
            <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($room['capacity'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="floor" class="form-label">Etaj</label>
            <input type="number" class="form-control" id="floor" name="floor" value="<?php echo htmlspecialchars($room['floor'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="available_rooms" class="form-label">Camere Disponibile</label>
            <input type="number" class="form-control" id="available_rooms" name="available_rooms" value="<?php echo htmlspecialchars($room['available_rooms'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="popular" class="form-label">Popular</label>
            <input type="checkbox" id="popular" name="popular" <?php echo isset($room['popular']) && $room['popular'] ? 'checked' : ''; ?>>
        </div>
        <div class="mb-3">
            <label for="facilities" class="form-label">Facilități</label>
            <select class="form-control" id="facilities" name="facilities[]" multiple>
                <?php foreach ($facilities as $facility): ?>
                    <option value="<?php echo $facility['id']; ?>" <?php echo in_array($facility['id'], $roomFacilities ?? []) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($facility['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image_url" class="form-label">Imagine Principală</label>
            <input type="file" class="form-control" id="image_url" name="image_url">
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Imagini Carusel</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Salvează</button>
    </form>
</div>
