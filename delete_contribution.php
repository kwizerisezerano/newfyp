<?php
// delete_contribution.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Get the contribution ID from the URL
$contribution_id = $_GET['id'];

// Delete the contribution from the database
$sql = "DELETE FROM contributions WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $contribution_id);

if ($stmt->execute()) {
    echo "<script>alert('Contribution deleted successfully!'); window.location.href = 'manage_contributions.php';</script>";
} else {
    echo "<script>alert('Error deleting contribution. Please try again.'); window.location.href = 'manage_contributions.php';</script>";
}
?>
