<?php
// edit_loan.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Check if the ID is provided
if (!isset($_GET['id'])) {
    die('Error: Loan ID is required.');
}

$loan_id = $_GET['id'];

// Fetch the loan details
$sql = "SELECT * FROM loans WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $loan_id]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loan) {
    die('Error: Loan not found.');
}

// Fetch members for the dropdown
$sql = "SELECT id, username FROM members";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $loan_amount = $_POST['loan_amount'];
    $interest_rate = $_POST['interest_rate'];
    $loan_date = $_POST['loan_date'];
    $repayment_date = $_POST['repayment_date'];
    $status = $_POST['status'];

    // Validate status
    if (!in_array($status, ['Pending', 'Approved', 'Overdue', 'Paid', 'Rejected'])) {
        die('Error: Invalid status.');
    }

    // Validate repayment date to be today or in the future
    $current_date = date('Y-m-d');
    if ($repayment_date < $current_date) {
        die('Error: Repayment date must be today or in the future.');
    }

    // Update loan in the database
    $sql = "UPDATE loans SET member_id = :member_id, loan_amount = :loan_amount, interest_rate = :interest_rate, loan_date = :loan_date, repayment_date = :repayment_date, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':member_id' => $member_id,
        ':loan_amount' => $loan_amount,
        ':interest_rate' => $interest_rate,
        ':loan_date' => $loan_date,
        ':repayment_date' => $repayment_date,
        ':status' => $status,
        ':id' => $loan_id,
    ]);

    // Redirect to manage_loans.php
    header('Location: manage_loans.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Loan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // JavaScript function to set the minimum date for repayment
        document.addEventListener('DOMContentLoaded', function() {
            var currentDate = new Date().toISOString().split('T')[0];
            document.getElementById('repayment_date').setAttribute('min', currentDate);
        });
        
        // JavaScript function to validate repayment date before form submission
        function validateForm() {
            var repaymentDate = new Date(document.getElementById('repayment_date').value);
            var currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0); // Set to start of day for comparison

            if (repaymentDate < currentDate) {
                alert('Repayment date must be today or in the future.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Edit Loan</h2>
        <form action="edit_loan.php?id=<?php echo htmlspecialchars($loan_id); ?>" method="POST" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="member_id">Member</label>
                <select id="member_id" name="member_id" class="form-control" required>
                    <?php foreach ($members as $member): ?>
                        <option value="<?php echo htmlspecialchars($member['id']); ?>" <?php echo $member['id'] == $loan['member_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($member['username']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="loan_amount">Loan Amount</label>
                <input type="number" id="loan_amount" name="loan_amount" class="form-control" value="<?php echo htmlspecialchars($loan['loan_amount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="interest_rate">Interest Rate (%)</label>
                <input type="number" step="0.01" id="interest_rate" name="interest_rate" class="form-control" value="<?php echo htmlspecialchars($loan['interest_rate']); ?>" required>
            </div>
            <div class="form-group">
                <label for="loan_date">Loan Date</label>
                <input type="date" id="loan_date" name="loan_date" class="form-control" value="<?php echo htmlspecialchars($loan['loan_date']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="repayment_date">Repayment Date</label>
                <input type="date" id="repayment_date" name="repayment_date" class="form-control" value="<?php echo htmlspecialchars($loan['repayment_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Pending" <?php echo $loan['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Approved" <?php echo $loan['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option> 
                    <option value="Rejected" <?php echo $loan['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Loan</button>
            <a href="manage_loans.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
