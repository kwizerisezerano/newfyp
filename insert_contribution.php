<?php
// insert_contribution.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];

    // Get the current date in the format YYYY-MM-DD
    $contribution_date = date('Y-m-d');

    // Insert contribution into the database
    $sql = "INSERT INTO contributions (member_id, amount, contribution_date) VALUES (:member_id, :amount, :contribution_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':member_id', $member_id);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':contribution_date', $contribution_date);

    if ($stmt->execute()) {
        echo "<script>alert('Contribution added successfully!'); window.location.href = 'manage_contributions.php';</script>";
    } else {
        echo "<script>alert('Error adding contribution. Please try again.'); window.location.href = 'manage_contributions.php';</script>";
    }
}
?>

