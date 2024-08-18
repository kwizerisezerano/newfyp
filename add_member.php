<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IZU MIS - Member Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
            margin-bottom: 20px;
        }
        .card {
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <div class="header">
                        <h2>IZU MIS</h2>
                    </div>
                    <div class="card">
                        <h3 class="text-center">Member Registration</h3>
                        <form id="registrationForm" method="POST" action="register.php">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required pattern="[A-Za-z]+" title="Letters only">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required pattern="[A-Za-z]+" title="Letters only">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required pattern="^(078|079|073|072)\d{7}$" title="Phone number must start with 078, 079, 073, or 072 and be exactly 10 digits" maxlength="10" minlength="10">
                            </div>
                            <div class="form-group">
                                <label for="type">Role</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Member">Member</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="form-group">
                                <label for="join_date">Join Date</label>
                                <input type="date" class="form-control" id="join_date" name="join_date" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required pattern="[A-Za-z]+" title="Letters only">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required pattern="\d{5}" title="Five digits only">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Add Member</button>
                        </form>
                    </div>
                </div>
            </div>
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
