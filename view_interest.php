<?php
// view_interest.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Get loan ID from query parameter
$loan_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$loan_id) {
    die('Invalid loan ID.');
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

// Calculate total interest accrued since the last payment
$interest_accrued = calculateAccruedInterest($loan['loan_amount'], $loan['interest_rate'], $loan['last_interest_date'], $current_date);

// Calculate total amount due (principal + accrued interest)
$total_due = $loan['loan_amount'] + $interest_accrued;

// Calculate total interest paid and interest not paid
$total_interest_paid = $loan['total_paid'] - $loan['loan_amount'];
$total_interest_due = $interest_accrued;
$total_interest_not_paid = $total_interest_due - $total_interest_paid;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Interest</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Loan Interest Details</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Amount Due</th>
                    <th>Total Amount Paid</th>
                    <th>Total Interest Paid</th>
                    <th>Total Interest Not Paid</th>
                    <th>Interest To Be Paid</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo number_format($total_due, 2); ?> RWF</td>
                    <td><?php echo number_format($loan['total_paid'], 2); ?> RWF</td>
                    <td><?php echo number_format($total_interest_paid, 2); ?> RWF</td>
                    <td><?php echo number_format($total_interest_not_paid, 2); ?> RWF</td>
                    <td><?php echo number_format($interest_accrued, 2); ?> RWF</td>
                </tr>
            </tbody>
        </table>
        <a href="manage_loans.php" class="btn btn-primary">Back to Manage Loans</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

