<?php
// delete_loan.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Get the loan ID from the URL
$loan_id = $_GET['id'];

// Delete loan from the database
$sql = "DELETE FROM loans WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $loan_id]);

// Redirect to manage_loans.php
header('Location: manage_loans.php');
exit;
?>
