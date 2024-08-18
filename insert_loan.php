<?php
// insert_loan.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Retrieve form data
$member_id = $_POST['member_id'];
$loan_amount = $_POST['loan_amount'];
$interest_rate = $_POST['interest_rate'];
$loan_date = $_POST['loan_date'];
$repayment_date = $_POST['repayment_date'];
$status = $_POST['status'];

// Prepare and execute the SQL statement
$sql = "INSERT INTO loans (member_id, loan_amount, interest_rate, loan_date, repayment_date, status)
        VALUES (:member_id, :loan_amount, :interest_rate, :loan_date, :repayment_date, :status)";
$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        ':member_id' => $member_id,
        ':loan_amount' => $loan_amount,
        ':interest_rate' => $interest_rate,
        ':loan_date' => $loan_date,
        ':repayment_date' => $repayment_date,
        ':status' => $status
    ]);
    // Redirect to manage_loans.php after successful insertion
    header('Location: manage_loans.php');
} catch (PDOException $e) {
    // Display error message if something goes wrong
    echo "Error: " . $e->getMessage();
}
?>
