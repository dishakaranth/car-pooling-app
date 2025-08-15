<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ride_id = $_POST['ride_id'] ?? null;
    $seats_requested = (int) ($_POST['seats'] ?? 1);
    $user_id = $_SESSION['user_id'];

    if (!$ride_id || $seats_requested <= 0) {
        echo "Invalid input.";
        exit();
    }

    $stmt = $pdo->prepare("SELECT available_seats FROM rides WHERE id = ?");
    $stmt->execute([$ride_id]);
    $ride = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ride) {
        echo "Ride not found.";
        exit();
    }

    if ($ride['available_seats'] < $seats_requested) {
        echo "Not enough seats available.";
        exit();
    }

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Insert booking
        $stmt = $pdo->prepare("INSERT INTO bookings (ride_id, user_id, seats_booked) VALUES (?, ?, ?)");
        $stmt->execute([$ride_id, $user_id, $seats_requested]);

        // Update available seats
        $stmt = $pdo->prepare("UPDATE rides SET available_seats = available_seats - ? WHERE id = ?");
        $stmt->execute([$seats_requested, $ride_id]);

        $pdo->commit();

        echo "<script>alert('Booking confirmed!'); window.location.href='dashboard.php';</script>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Booking failed: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
