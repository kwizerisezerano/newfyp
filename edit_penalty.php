<?php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

$id = $_GET['id'];

// Fetch the penalty details
$sql = "SELECT * FROM penalties WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$penalty = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch members for the dropdown
$sql = "SELECT id, username FROM members";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $penalty_amount = $_POST['penalty_amount'];
    $infraction_date = $_POST['infraction_date'];
    $reason = $_POST['reason'];

    $sql = "UPDATE penalties SET member_id = :member_id, penalty_amount = :penalty_amount, infraction_date = :infraction_date, reason = :reason WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'member_id' => $member_id,
        'penalty_amount' => $penalty_amount,
        'infraction_date' => $infraction_date,
        'reason' => $reason,
        'id' => $id
    ]);

    header('Location: manage_penalties.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penalty</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            background-color: #007bff; /* Consistent with edit_contribution.php */
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="header">
        <h2>IZU MIS - Edit Penalty</h2>
    </div>
    <a href="manage_penalties.php" class="btn btn-primary mb-3">Back to Manage Penalties</a>
    <form action="" method="POST">
        <div class="form-group">
            <label for="member_id">Member</label>
            <select id="member_id" name="member_id" class="form-control" required>
                <?php foreach ($members as $member): ?>
                    <option value="<?php echo $member['id']; ?>" <?php if ($member['id'] == $penalty['member_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($member['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="penalty_amount">Penalty Amount</label>
            <input type="number" id="penalty_amount" name="penalty_amount" class="form-control" value="<?php echo htmlspecialchars($penalty['penalty_amount']); ?>" required>
        </div>
        <div class="form-group">
            <label for="infraction_date">Infraction Date</label>
            <input type="date" id="infraction_date" name="infraction_date" class="form-control" value="<?php echo htmlspecialchars($penalty['infraction_date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea id="reason" name="reason" class="form-control" required><?php echo htmlspecialchars($penalty['reason']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Penalty</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
