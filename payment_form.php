<?php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loan_id = $_POST['loan_id'];
    $payment_amount = $_POST['payment_amount'];

    // Fetch the loan details
    $sql = "SELECT * FROM loans WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $loan_id]);
    $loan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$loan) {
        die('Error: Loan not found.');
    }

    $principal = $loan['loan_amount'];
    $annual_interest_rate = $loan['interest_rate'];
    $monthly_interest_rate = ($annual_interest_rate / 100) / 12;
    $interest_due = $principal * $monthly_interest_rate;

    // Validate the payment amount
    if ($payment_amount > $principal + $interest_due) {
        $_SESSION['error'] = "Payment amount exceeds the outstanding loan amount and interest due.";
        header('Location: payment_form.php?id=' . $loan_id);
        exit;
    }

    // Split the payment
    $interest_payment = min($payment_amount, $interest_due);
    $principal_payment = $payment_amount - $interest_payment;

    // Update the loan amount if interest is unpaid
    if ($interest_payment < $interest_due) {
        $new_principal = $principal + ($interest_due - $interest_payment);
        $sql = "UPDATE loans SET loan_amount = :loan_amount WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':loan_amount' => $new_principal, ':id' => $loan_id]);
    } else {
        // If interest payment is fully covered, adjust the principal
        $new_principal = $principal - $principal_payment;
        $sql = "UPDATE loans SET loan_amount = :loan_amount WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':loan_amount' => $new_principal, ':id' => $loan_id]);
    }

    // Record the payment
    $sql = "INSERT INTO payments (loan_id, payment_date, payment_amount, interest_payment, principal_payment) 
            VALUES (:loan_id, NOW(), :payment_amount, :interest_payment, :principal_payment)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':loan_id' => $loan_id,
        ':payment_amount' => $payment_amount,
        ':interest_payment' => $interest_payment,
        ':principal_payment' => $principal_payment
    ]);

    // Redirect to the manage loans page with a success message
    $_SESSION['success'] = "Payment recorded successfully.";
    header('Location: manage_loans.php');
    exit;
}

// Get loan details for the form
$loan_id = $_GET['id'];
$sql = "SELECT l.id, m.username AS member_name, l.loan_amount, l.interest_rate, l.loan_date, l.repayment_date, l.status
        FROM loans l
        JOIN members m ON l.member_id = m.id
        WHERE l.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $loan_id]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Make Payment for Loan #<?php echo htmlspecialchars($loan['id']); ?></h3>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
                <form method="POST" action="payment_form.php">
                    <input type="hidden" name="loan_id" value="<?php echo htmlspecialchars($loan['id']); ?>">
                    <div class="form-group">
                        <label for="member_name">Member Name</label>
                        <input type="text" id="member_name" class="form-control" value="<?php echo htmlspecialchars($loan['member_name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="loan_amount">Outstanding Loan Amount</label>
                        <input type="text" id="loan_amount" class="form-control" value="<?php echo htmlspecialchars($loan['loan_amount']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="interest_rate">Interest Rate (%)</label>
                        <input type="text" id="interest_rate" class="form-control" value="<?php echo htmlspecialchars($loan['interest_rate']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="payment_amount">Payment Amount</label>
                        <input type="number" id="payment_amount" name="payment_amount" class="form-control" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-success">Submit Payment</button>
                </form>
            </div>
        </div>
        <a href="manage_loans.php" class="btn btn-primary mt-3">Back to Manage Loans</a>
    </div>
</body>
</html>

