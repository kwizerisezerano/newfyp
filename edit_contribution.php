<?php
// edit_contribution.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Get the contribution ID from the URL
$contribution_id = $_GET['id'];

// Fetch the contribution details from the database
$sql = "SELECT * FROM contributions WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $contribution_id);
$stmt->execute();
$contribution = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch members for the dropdown
$sql = "SELECT id, username FROM members";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $contribution_date = $_POST['contribution_date'];

    // Update the contribution in the database
    $sql = "UPDATE contributions SET member_id = :member_id, amount = :amount, contribution_date = :contribution_date WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':member_id', $member_id);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':contribution_date', $contribution_date);
    $stmt->bindParam(':id', $contribution_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Contribution updated successfully!'); window.location.href = 'manage_contributions.php';</script>";
    } else {
        echo "<script>alert('Error updating contribution. Please try again.'); window.location.href = 'edit_contribution.php?id=$contribution_id';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IZU MIS - Edit Contribution</title>
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
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h2>IZU MIS - Edit Contribution</h2>
        </div>
        <div class="card">
            <form action="edit_contribution.php?id=<?php echo $contribution_id; ?>" method="POST">
                <div class="form-group">
                    <label for="member_id">Member</label>
                    <select id="member_id" name="member_id" class="form-control" required>
                        <?php foreach ($members as $member): ?>
                            <option value="<?php echo $member['id']; ?>" <?php echo $member['id'] == $contribution['member_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($member['username']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" class="form-control" value="<?php echo htmlspecialchars($contribution['amount']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contribution_date">Date</label>
                    <input type="date" id="contribution_date" name="contribution_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Update Contribution</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
