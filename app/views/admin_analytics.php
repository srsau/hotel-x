<h1 class="mb-4">Analytics</h1>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Device Type Distribution</h5>
                <div class="text-center mb-4">
                    <img src="<?php echo $deviceChartDataUri; ?>" alt="Device Type Distribution Chart" class="img-fluid" style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Browser Distribution</h5>
                <div class="text-center mb-4">
                    <img src="<?php echo $browserChartDataUri; ?>" alt="Browser Distribution Chart" class="img-fluid" style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Unique IPs</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($uniqueIps as $ip): ?>
                        <li class="list-group-item"><?php echo htmlspecialchars($ip['ip_address']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Unique Pages Views</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($uniquePages as $page): ?>
                        <li class="list-group-item"><?php echo htmlspecialchars($page['page']); ?> - <?php echo htmlspecialchars($page['unique_access_count']); ?> views</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Unique Access Count</h5>
                <p class="card-text"><?php echo htmlspecialchars($uniqueAccessCount); ?></p>
            </div>
        </div>
    </div>
</div>

<h2 class="mb-4">All Analytics Data</h2>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>IP Address</th>
                <th>Page</th>
                <th>Session ID</th>
                <th>Load Count</th>
                <th>User Agent</th>
                <th>Device Type</th>
                <th>Browser Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($analytics as $entry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($entry['ip_address']); ?></td>
                    <td><?php echo htmlspecialchars($entry['page']); ?></td>
                    <td><?php echo htmlspecialchars($entry['session_id']); ?></td>
                    <td><?php echo htmlspecialchars($entry['load_count']); ?></td>
                    <td><?php echo htmlspecialchars($entry['user_agent']); ?></td>
                    <td><?php echo htmlspecialchars($entry['device_type']); ?></td>
                    <td><?php echo htmlspecialchars($entry['browser_name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
