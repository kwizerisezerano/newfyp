<?php
// dashboard.php
session_start();

// Assuming that the username is stored in the session upon login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IZU MIS - Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #007bff;
            color: white;
            padding-top: 20px;
            position: fixed;
        }

        .sidebar .username {
            padding: 15px 25px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            background-color: #0056b3;
            border-bottom: 1px solid #004494;
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
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

        .manage-link {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="username">
            Welcome, <?php echo htmlspecialchars($username); ?>!
        </div>
        <a href="#members">Members</a>
        <a href="#contributions">Contributions</a>
        <a href="#penalties">Penalties</a>
        <a href="#loans">Loans</a>
        <a href="#dividends">Dividends</a>
        <a href="#profit">Profit</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>IZU MIS Dashboard</h2>
        </div>

        <div id="members" class="card">
            <h3>Members</h3>
            <p>Here you can find the list of all members of the organization. You can view member details, add new members, update existing member information, or delete members from the system.</p>
            <a href="manage_members.php" class="btn btn-primary manage-link">Manage Members</a>
        </div>

        <div id="contributions" class="card">
            <h3>Contributions</h3>
            <p>This section contains information about member contributions. You can view all contributions, add new contributions, update existing contribution records, or delete them as needed.</p>
            <a href="manage_contributions.php" class="btn btn-primary manage-link">Manage Contributions</a>
        </div>

        <div id="penalties" class="card">
            <h3>Penalties</h3>
            <p>Details about member penalties are available here. You can view all penalty records, add new penalties, update existing ones, or remove penalties from the system.</p>
            <a href="manage_penalties.php" class="btn btn-primary manage-link">Manage Penalties</a>
        </div>

        <div id="loans" class="card">
            <h3>Loans</h3>
            <p>This section provides information on member loans. You can view all loan records, add new loans, update loan information, or delete loans as necessary.</p>
            <a href="manage_loans.php" class="btn btn-primary manage-link">Manage Loans</a>
        </div>

        <div id="dividends" class="card">
            <h3>Dividends</h3>
            <p>Here you can find details about member dividends. You can view dividend records, add new dividends, update existing ones, or delete dividend entries.</p>
            <a href="manage_dividends.php" class="btn btn-primary manage-link">Manage Dividends</a>
        </div>

        <div id="profit" class="card">
            <h3>Profit</h3>
            <p>Information on the organization's profit distribution is available in this section. You can view profit records, add new profit entries, update existing records, or delete them.</p>
            <a href="manage_profit.php" class="btn btn-primary manage-link">Manage Profit</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

