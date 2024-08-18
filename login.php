<?php
// login.php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $sql = "SELECT * FROM members WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Valid credentials
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['type'];

        if ($user['type'] === 'Admin') {
            echo "<script>alert('Login successful! Redirecting to admin dashboard.'); window.location.href = 'admindashboard.php';</script>";
        } else {
            echo "<script>alert('Login successful! Redirecting to member dashboard.'); window.location.href = 'memberdashboard.php';</script>";
        }
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password. Please try again.'); window.location.href = 'loginform.php';</script>";
    }
}
?>
