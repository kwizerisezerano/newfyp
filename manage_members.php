<?php
// fetch_members.php
require_once 'config.php';

$sql = 'SELECT * FROM members';
$stmt = $pdo->query($sql);
$members = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IZU MIS - Manage Members</title>
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
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h2>IZU MIS - Manage Members</h2>
        </div>
        <div id="members" class="card">
            <h3>Members</h3>
            <button class="btn btn-primary mb-3" onclick="location.href='add_member.php'">Add Member</button>
            <button class="btn btn-secondary mb-3" onclick="location.href='admindashboard.php'">Admin Dashboard</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Join Date</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['id']); ?></td>
                            <td><?php echo htmlspecialchars($member['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($member['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($member['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($member['type']); ?></td>
                            <td><?php echo htmlspecialchars($member['address']); ?></td>
                            <td><?php echo htmlspecialchars($member['join_date']); ?></td>
                            <td><?php echo htmlspecialchars($member['username']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning" onclick="location.href='update_member.php?id=<?php echo $member['id']; ?>'">Update</button>
                                    <button class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this member?')) location.href='delete_member.php?id=<?php echo $member['id']; ?>'">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
