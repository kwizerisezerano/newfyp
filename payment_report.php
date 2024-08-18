<?php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Fetch all payments from the database along with the requested loan amount and total interest paid
$sql = "SELECT p.id, l.id AS loan_id, m.username AS member_name, l.loan_amount, p.payment_date, p.payment_amount, 
               p.interest_payment, p.principal_payment, 
               (SELECT SUM(interest_payment) FROM payments WHERE loan_id = l.id) AS total_interest_paid
        FROM payments p
        JOIN loans l ON p.loan_id = l.id
        JOIN members m ON l.member_id = m.id
        ORDER BY p.payment_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content {
            padding: 20px;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h2>Payment Report</h2>
        </div>
        <a href="admindashboard.php" class="btn btn-primary mb-3">Go to Dashboard</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Loan ID</th>
                    <th>Member Name</th>
                    <th>Requested Loan Amount</th>
                    <th>Payment Date</th>
                    <th>Payment Amount</th>
                    <th>Interest Payment</th>
                    <th>Principal Payment</th>
                    <th>Total Interest Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['loan_id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['member_name']); ?></td>
                        <td><?php echo htmlspecialchars($payment['loan_amount']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_amount']); ?></td>
                        <td><?php echo htmlspecialchars($payment['interest_payment']); ?></td>
                        <td><?php echo htmlspecialchars($payment['principal_payment']); ?></td>
                        <td><?php echo htmlspecialchars($payment['total_interest_paid']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

