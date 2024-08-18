<?php
// process_payment.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Get loan ID and payment amount from POST request
$loan_id = isset($_POST['loan_id']) ? $_POST['loan_id'] : null;
$payment_amount = isset($_POST['payment_amount']) ? $_POST['payment_amount'] : 0;

if (!$loan_id || $payment_amount <= 0) {
    die('Invalid loan ID or payment amount.');
}

// Fetch the loan details
$sql = "SELECT loan_amount, interest_rate, loan_date, last_interest_date, total_paid FROM loans WHERE id = :loan_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':loan_id' => $loan_id]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loan) {
    die('Loan not found.');
}

// Function to calculate accrued interest
function calculateAccruedInterest($principal, $annual_rate, $last_interest_date, $current_date) {
    $last_interest_date = new DateTime($last_interest_date);
    $current_date = new DateTime($current_date);
    $interval = $last_interest_date->diff($current_date);

    $months = $interval->m + ($interval->y * 12);
    $rate_per_period = $annual_rate / 100 / 12;
    $amount_with_interest = $principal * pow((1 + $rate_per_period), $months);

    return $amount_with_interest - $principal;
}

// Get current date
$current_date = date('Y-m-d');

// Calculate total interest accrued
$interest_accrued = calculateAccruedInterest($loan['loan_amount'], $loan['interest_rate'], $loan['last_interest_date'], $current_date);

// Calculate total amount due
$total_due = $loan['loan_amount'] + $interest_accrued;

// Ensure payment does not exceed amount due
$payment_amount = min($payment_amount, $total_due);

// Calculate new total paid
$new_total_paid = $loan['total_paid'] + $payment_amount;

// Determine remaining balance
$remaining_balance = $total_due - $new_total_paid;

// Update loan record
$update_sql = "UPDATE loans
               SET total_paid = :new_total_paid,
                   last_interest_date = :current_date
               WHERE id = :loan_id";
$update_stmt = $pdo->prepare($update_sql);
$update_stmt->execute([
    ':new_total_paid' => $new_total_paid,
    ':current_date' => $current_date,
    ':loan_id' => $loan_id
]);

// Update loan status based on the remaining balance
$status = $remaining_balance <= 0 ? 'Paid' : 'Active';
$update_status_sql = "UPDATE loans
                      SET status = :status
                      WHERE id = :loan_id";
$status_stmt = $pdo->prepare($update_status_sql);
$status_stmt->execute([
    ':status' => $status,
    ':loan_id' => $loan_id
]);

// Calculate total interest paid and interest not paid
$total_interest_paid = $new_total_paid - $loan['loan_amount'];
$total_interest_not_paid = $interest_accrued - $total_interest_paid;

$_SESSION['message'] = "Payment processed successfully. 
                        Total Interest To Be Paid: " . number_format($interest_accrued, 2) . " RWF. 
                        Total Amount Due: " . number_format($total_due, 2) . " RWF. 
                        Total Amount Paid: " . number_format($new_total_paid, 2) . " RWF. 
                        Total Interest Paid: " . number_format($total_interest_paid, 2) . " RWF. 
                        Total Interest Not Paid: " . number_format($total_interest_not_paid, 2) . " RWF.";

header('Location: view_interest.php?id=' . $loan_id);
exit;
?>
