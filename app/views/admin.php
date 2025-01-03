<h1>Admin Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</p>
<p>This is the admin dashboard.</p>
<p><a href="/admin/bookings" class="btn btn-primary">View All Bookings</a></p>