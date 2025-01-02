<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
</head>
<body>
    <h2>My Account</h2>
    <p>Welcome to your account page, <?php echo $_SESSION['user']['name']; ?>!</p>
    <p>Email: <?php echo $_SESSION['user']['email']; ?></p>
</body>
</html>