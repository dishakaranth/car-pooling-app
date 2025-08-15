<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - BlaBlaCar Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body class="bg-blue-50 flex flex-col items-center justify-center min-h-screen p-6">
    <h1 class="text-3xl font-bold text-blue-600 mb-8">Dashboard</h1>

    <div class="space-y-4 w-full max-w-sm text-center">
      <a
        href="book-ride.html"
        class="w-full block py-3 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
      >
        Book a Ride
      </a>

      <a
        href="post-ride.html"
        class="w-full block py-3 bg-green-500 text-white rounded hover:bg-green-600 transition"
      >
        Post a Ride
      </a>

      <a
        href="driver_ride_history.php"
        class="w-full block py-3 bg-purple-500 text-white rounded hover:bg-purple-600 transition"
      >
        My Ride History
      </a>

      <a
        href="logout.php"
        class="block mt-8 text-sm text-gray-500 hover:underline"
      >
        ← Logout
      </a>
    </div>
  </body>
</html>
