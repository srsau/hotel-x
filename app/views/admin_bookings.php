<div class="container mt-4">
    <h2>All Bookings</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Room</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
                <th>Addons</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['room_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['check_in_date']) ?></td>
                    <td><?= htmlspecialchars($booking['check_out_date']) ?></td>
                    <td><?= htmlspecialchars($booking['addon_names'] ?? 'None') ?></td>
                    <td><?= htmlspecialchars($booking['total_price']) ?></td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>