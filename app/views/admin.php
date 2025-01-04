<h1>Panou de Administrare</h1>
<p>Bine ai venit, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</p>
<p>Acesta este panoul de administrare.</p>
<p><a href="/admin/bookings" class="btn btn-primary">Vezi Toate Rezervările</a></p>
<p><a href="/admin/analytics" class="btn btn-primary">Vezi Analizele</a></p>