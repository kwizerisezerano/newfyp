<?php
// manage_penalties.php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

// Fetch penalties from the database
$sql = "SELECT p.id, m.username as member_name, p.penalty_amount, p.infraction_date, p.reason 
        FROM penalties p 
        JOIN members m ON p.member_id = m.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$penalties = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>IZU MIS - Manage Penalties</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="header">
        <h2>IZU MIS - Manage Penalties</h2>
    </div>
    <a href="admindashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>
    <div class="content">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Member</th>
                <th>Penalty Amount</th>
                <th>Infraction Date</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($penalties as $penalty): ?>
                <tr>
                    <td><?php echo htmlspecialchars($penalty['id']); ?></td>
                    <td><?php echo htmlspecialchars($penalty['member_name']); ?></td>
                    <td><?php echo htmlspecialchars($penalty['penalty_amount']); ?></td>
                    <td><?php echo htmlspecialchars($penalty['infraction_date']); ?></td>
                    <td><?php echo htmlspecialchars($penalty['reason']); ?></td>
                    <td>
                        <a href="edit_penalty.php?id=<?php echo $penalty['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_penalty.php?id=<?php echo $penalty['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this penalty?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add New Penalty</h3>
        <form action="insert_penalty.php" method="POST">
            <div class="form-group">
                <label for="member_id">Member</label>
                <select id="member_id" name="member_id" class="form-control" required>
                    <?php foreach ($members as $member): ?>
                        <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="penalty_amount">Penalty Amount</label>
                <input type="number" id="penalty_amount" name="penalty_amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="infraction_date">Infraction Date</label>
                <input type="date" id="infraction_date" name="infraction_date" class="form-control" value="<?php echo $current_date; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="reason">Reason</label>
                <textarea id="reason" name="reason" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Penalty</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
