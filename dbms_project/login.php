<?php
require 'db.php'; // your database connection file
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        // Handle empty input (you can improve this later)
        echo "Please fill in both fields.";
        exit;
    }

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Correct password — set session and redirect
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name']; // optional, for greeting

        header('Location: dashboard.php');
        exit;
    } else {
        // Invalid credentials
        echo "Invalid email or password.";
        exit;
    }
} else {
    // If not POST request, redirect to login form
    header('Location: login.html');
    exit;
}
