<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';
$seats_requested = $_GET['seats'] ?? 1;

$stmt = $pdo->prepare("SELECT * FROM rides WHERE departure = ? AND destination = ? AND DATE(departure_time) = ?");
$stmt->execute([$from, $to, $date]);
$rides = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Available Rides - BlaBlaCar Clone</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen p-6">
  <h1 class="text-3xl font-bold text-blue-600 mb-6 text-center">Available Rides</h1>

  <div class="max-w-3xl mx-auto space-y-4">
    <?php if (count($rides) === 0): ?>
      <p class="text-center text-gray-600">No rides found for your search.</p>
    <?php else: ?>
      <?php foreach ($rides as $ride): ?>
        <div class="border rounded p-4 shadow hover:shadow-lg transition">
          <div class="flex justify-between items-center mb-2">
            <div>
              <p class="text-lg font-semibold"><?= htmlspecialchars($ride['departure']) ?> → <?= htmlspecialchars($ride['destination']) ?></p>
              <p class="text-sm text-gray-600">Date: <?= date('Y-m-d H:i', strtotime($ride['departure_time'])) ?></p>
            </div>
            <div class="text-right">
              <p class="text-green-600 font-bold">$<?= $ride['price'] ?> per seat</p>
              <p class="text-sm text-gray-600">Seats Available: <?= $ride['available_seats'] ?></p>
            </div>
          </div>

          <?php if ($ride['available_seats'] >= $seats_requested): ?>
            <form action="book-seat.php" method="POST">
              <input type="hidden" name="ride_id" value="<?= $ride['id'] ?>">
              <input type="hidden" name="seats" value="<?= $seats_requested ?>">
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Book <?= $seats_requested ?> Seat<?= $seats_requested > 1 ? 's' : '' ?>
              </button>
            </form>
          <?php else: ?>
            <p class="text-red-500 mt-2">Not enough seats available.</p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    
    <a href="book-ride.html" class="block mt-8 text-center text-blue-500 underline">
      ← Back to Search
    </a>
  </div>
</body>
</html>
