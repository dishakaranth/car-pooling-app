<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $departure = trim($_POST['departure']);
    $destination = trim($_POST['destination']);
    $departure_time = $_POST['departure_time'];
    $available_seats = (int)$_POST['available_seats'];
    $price = (float)$_POST['price'];

    if (empty($departure) || empty($destination) || empty($departure_time) || $available_seats <= 0 || $price < 0) {
        exit('Please fill in all fields correctly.');
    }

    $stmt = $pdo->prepare("INSERT INTO rides (user_id, departure, destination, departure_time, available_seats, price) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$user_id, $departure, $destination, $departure_time, $available_seats, $price])) {
        echo "<script>
                alert('Ride posted successfully!');
                window.location.href = 'dashboard.php';
              </script>";
        exit;
    } else {
        exit('Something went wrong. Please try again.');
    }
} else {
    exit('Invalid request method.');
}
