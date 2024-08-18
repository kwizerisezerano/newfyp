<?php
// manage_loans.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Fetch loans from the database
$sql = "SELECT l.id, m.username as member_name, l.loan_amount, l.interest_rate, l.loan_date, l.repayment_date, l.status
        FROM loans l 
        JOIN members m ON l.member_id = m.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch members for the dropdown
$sql = "SELECT id, username FROM members";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get current date
$current_date = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IZU MIS - Manage Loans</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content {
            padding: 20px;
            width: 100%;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .card {
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .status-approved {
            color: green;
            font-weight: bold;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
        }

        .status-pending {
            color: blue;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h2>IZU MIS - Manage Loans</h2>
        </div>
        <div class="card">
            <a href="admindashboard.php" class="btn btn-primary mb-3">Go to Dashboard</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Loan Amount</th>
                        <th>Interest Rate</th>
                        <th>Loan Date</th>
                        <th>Repayment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($loan['id']); ?></td>
                            <td><?php echo htmlspecialchars($loan['member_name']); ?></td>
                            <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
                            <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
                            <td><?php echo htmlspecialchars($loan['loan_date']); ?></td>
                            <td><?php echo htmlspecialchars($loan['repayment_date']); ?></td>
                            <td class="<?php
                                echo $loan['status'] === 'Approved' ? 'status-approved' : 
                                     ($loan['status'] === 'Rejected' ? 'status-rejected' : 
                                     ($loan['status'] === 'Pending' ? 'status-pending' : '')); ?>">
                                <?php echo htmlspecialchars($loan['status']); ?>
                            </td>
                            <td>
                                <a href="edit_loan.php?id=<?php echo $loan['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_loan.php?id=<?php echo $loan['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this loan?');">Delete</a>
                                <a href="payment_report.php?id=<?php echo $loan['id']; ?>" class="btn btn-info btn-sm">View Interest</a>
                                <a href="payment_form.php?id=<?php echo $loan['id']; ?>" class="btn btn-success btn-sm">Make Payment</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Add New Loan</h3>
            <form action="insert_loan.php" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="member_id">Member</label>
                    <select id="member_id" name="member_id" class="form-control" required>
                        <?php foreach ($members as $member): ?>
                            <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['username']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="loan_amount">Loan Amount</label>
                    <input type="number" id="loan_amount" name="loan_amount" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label for="interest_rate">Interest Rate (%)</label>
                    <input type="number" step="0.01" id="interest_rate" name="interest_rate" class="form-control" value="5.00" min="0.01" required>
                </div>
                <div class="form-group">
                    <label for="loan_date">Loan Date</label>
                    <input type="date" id="loan_date" name="loan_date" class="form-control" value="<?php echo $current_date; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="repayment_date">Repayment Date</label>
                    <input type="date" id="repayment_date" name="repayment_date" class="form-control" min="<?php echo $current_date; ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" id="status" name="status" class="form-control" value="Pending" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Add Loan</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function validateForm() {
            const loanAmount = document.getElementById('loan_amount').value;
            const interestRate = document.getElementById('interest_rate').value;
            const repaymentDate = document.getElementById('repayment_date').value;
            const currentDate = '<?php echo $current_date; ?>';

            if (loanAmount <= 0) {
                alert('Loan amount must be a positive number.');
                return false;
            }

            if (interestRate <= 0) {
                alert('Interest rate must be a positive number.');
                return false;
            }

            if (repaymentDate < currentDate) {
                alert('Repayment date must be today or a future date.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
