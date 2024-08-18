<?php
// update_member.php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the member's current details
    $sql = 'SELECT * FROM members WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $member = $stmt->fetch();

    if (!$member) {
        die('Member not found.');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update member details
        $sql = 'UPDATE members SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number, type = :type, address = :address, join_date = :join_date WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'phone_number' => $_POST['phone_number'],
            'type' => $_POST['type'],
            'address' => $_POST['address'],
            'join_date' => $_POST['join_date'],
            'id' => $id
        ]);
        header('Location: manage_members.php');
        exit;
    }
} else {
    die('ID not specified.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IZU MIS - Update Member</title>
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
            <h2>IZU MIS - Update Member</h2>
        </div>
        <div id="update-member" class="card">
            <form method="POST" id="updateForm">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($member['first_name']); ?>" required pattern="[A-Za-z]+" title="Letters only">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($member['last_name']); ?>" required pattern="[A-Za-z]+" title="Letters only">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($member['phone_number']); ?>" required pattern="^(078|079|073|072)\d{7}$" title="Phone number must start with 078, 079, 073, or 072 and be exactly 10 digits" maxlength="10" minlength="10">
                </div>
                <div class="form-group">
                    <label for="type">Role</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="Admin" <?php if ($member['type'] == 'Admin') echo 'selected'; ?>>Admin</option>
                        <option value="Member" <?php if ($member['type'] == 'Member') echo 'selected'; ?>>Member</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($member['address']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="join_date">Join Date</label>
                    <input type="date" class="form-control" id="join_date" name="join_date" value="<?php echo date('Y-m-d'); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($member['username']); ?>" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Update Member</button>
            </form>
        </div>
    </div>

    <script>
        // Set join date to current date and make it readonly
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date().toISOString().split('T')[0];
            var joinDateInput = document.getElementById('join_date');
            joinDateInput.value = today;
            joinDateInput.readOnly = true;
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
