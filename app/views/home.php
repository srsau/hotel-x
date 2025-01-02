<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Welcome to Hotel X</h1>
    <h2>Available Rooms</h2>
    <ul>
        <?php foreach ($rooms as $room): ?>
            <li><?php echo htmlspecialchars($room['name']); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="/about">About Us</a>
</body>
</html>
