<div class="container mt-4">
    <h2>Toate Rezervările</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Utilizator</th>
                <th>Cameră</th>
                <th>Data Check-in</th>
                <th>Data Check-out</th>
                <th>Adăugiri</th>
                <th>Preț Total</th>
                <th>Stare</th>
                <th>Acțiuni</th>
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
                    <td><?= "$" . htmlspecialchars($booking['total_price']) ?></td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                    <td>
                        <?php if ($booking['status'] === 'valid'): ?>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" data-booking-id="<?= $booking['id'] ?>">Anulează</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Anulare Rezervare</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
            </div>
            <div class="modal-body">
                <form id="cancelBookingForm" method="post" action="/admin/cancel">
                    <input type="hidden" name="booking_id" id="bookingIdInput">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Motivul Anulării</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nu</button>
                    <button type="submit" class="btn btn-danger">Da, Anulează</button>
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