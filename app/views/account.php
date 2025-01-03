<div class="container mt-4">
    <h2>My Account</h2>
    <p>Welcome to your account page, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</p>
    <p>Email: <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>

    <h3>Your Bookings</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Room</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
                <th>Addons</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['room_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['check_in_date']) ?></td>
                    <td><?= htmlspecialchars($booking['check_out_date']) ?></td>
                    <td><?= htmlspecialchars($booking['addon_names'] ?? 'None') ?></td>
                    <td><?= htmlspecialchars($booking['total_price']) ?></td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                    <td>
                        <?php if ($booking['status'] === 'valid'): ?>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" data-booking-id="<?= $booking['id'] ?>">Cancel</button>
                        <?php endif; ?>
                        <a href="/account/pdf?booking_id=<?= $booking['id'] ?>" class="btn btn-primary btn-sm">Download PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this booking?
            </div>
            <div class="modal-footer">
                <form id="cancelBookingForm" method="post" action="/account/cancel">
                    <input type="hidden" name="booking_id" id="bookingIdInput">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var cancelModal = document.getElementById('cancelModal');
    cancelModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var bookingId = button.getAttribute('data-booking-id');
        var bookingIdInput = document.getElementById('bookingIdInput');
        bookingIdInput.value = bookingId;
    });
});
</script>