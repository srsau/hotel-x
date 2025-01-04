<?php
    require_once __DIR__ . '/../helpers/convertPrice.php';
    require_once __DIR__ . '/../helpers/getCurrencies.php';
    $preferred_currency = $_SESSION['preferred_currency'];
    $currencies = getCurrencies();
?>
<div class="container mt-4">
    <h2>Contul Meu</h2>
    <p>Bine ai venit pe pagina contului tău, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</p>
    <p>Email: <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>

    <h4>Setări Cont</h4>

    <div class="mb-3">
        <label for="preferred_currency" class="form-label">Moneda Preferată</label>
        <select class="form-control" id="preferred_currency" name="preferred_currency" onchange="changeCurrency(this.value)">
            <?php foreach ($currencies as $currency): ?>
                <option value="<?php echo htmlspecialchars($currency); ?>" <?php echo $currency === $preferred_currency ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($currency); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <h4>Rezervările Tale</h4>

    <table class="table table-striped">
        <thead>
            <tr>
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
                    <td><?= htmlspecialchars($booking['room_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['check_in_date']) ?></td>
                    <td><?= htmlspecialchars($booking['check_out_date']) ?></td>
                    <td><?= htmlspecialchars($booking['addon_names'] ?? 'None') ?></td>
                    <td><?= htmlspecialchars(convertPrice($booking['total_price'], $preferred_currency)) ?></td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                    <td>
                        <?php if ($booking['status'] === 'valid'): ?>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" data-booking-id="<?= $booking['id'] ?>">Anulează</button>
                        <?php endif; ?>
                        <a href="/account/pdf?booking_id=<?= $booking['id'] ?>" class="btn btn-primary btn-sm">Descarcă PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function changeCurrency(currency) {
    fetch('/account/change_currency', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ currency: currency, user_id: <?php echo $_SESSION['user']['id']; ?> })
    }).then(response => {
        if (response.ok) {
            location.reload();
        }
    });
}
</script>