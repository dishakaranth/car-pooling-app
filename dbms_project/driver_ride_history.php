<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch rides posted by user (driver)
$stmt_driver = $pdo->prepare("SELECT * FROM rides WHERE user_id = ? ORDER BY departure_time DESC");
$stmt_driver->execute([$user_id]);
$posted_rides = $stmt_driver->fetchAll(PDO::FETCH_ASSOC);

// Fetch rides booked by user (passenger)
$stmt_booked = $pdo->prepare("
  SELECT r.*, b.seats_booked, b.booked_at
  FROM bookings b
  JOIN rides r ON b.ride_id = r.id
  WHERE b.user_id = ?
  ORDER BY b.booked_at DESC
");
$stmt_booked->execute([$user_id]);
$booked_rides = $stmt_booked->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ride History</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-6 min-h-screen">
  <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">My Ride History</h1>

  <!-- Rides Posted -->
  <h2 class="text-2xl font-semibold text-green-600 mb-4">Rides You Posted</h2>
  <?php if ($posted_rides): ?>
    <div class="space-y-4 mb-8">
      <?php foreach ($posted_rides as $ride): ?>
        <div class="border rounded p-4 shadow">
          <p><strong><?= htmlspecialchars($ride['departure']) ?> → <?= htmlspecialchars($ride['destination']) ?></strong></p>
          <p>Date: <?= date('Y-m-d H:i', strtotime($ride['departure_time'])) ?></p>
          <p>Price: $<?= $ride['price'] ?> | Seats Left: <?= $ride['available_seats'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="mb-8 text-gray-500">You haven't posted any rides.</p>
  <?php endif; ?>

  <h2 class="text-2xl font-semibold text-purple-600 mb-4">Rides You Booked</h2>
  <?php if ($booked_rides): ?>
    <div class="space-y-4">
      <?php foreach ($booked_rides as $ride): ?>
        <div class="border rounded p-4 shadow">
          <p><strong><?= htmlspecialchars($ride['departure']) ?> → <?= htmlspecialchars($ride['destination']) ?></strong></p>
          <p>Date: <?= date('Y-m-d H:i', strtotime($ride['departure_time'])) ?></p>
          <p>Seats Booked: <?= $ride['seats_booked'] ?> | Price: $<?= $ride['price'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-gray-500">You haven't booked any rides.</p>
  <?php endif; ?>

  <div class="mt-8 text-center">
    <a href="dashboard.php" class="text-blue-500 hover:underline">← Back to Dashboard</a>
  </div>
</body>
</html>
