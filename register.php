<?php
// register.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $type = $_POST['type'];
    $address = $_POST['address'];
    $join_date = $_POST['join_date'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the type
    if ($type !== 'Admin' && $type !== 'Member') {
        echo "<script>alert('Invalid type. Please choose Admin or Member.'); window.location.href = 'add_member.php';</script>";
        exit;
    }

    // Check if phone number already exists
    $checkPhoneNumberSQL = "SELECT COUNT(*) FROM members WHERE phone_number = :phone_number";
    $checkStmt = $pdo->prepare($checkPhoneNumberSQL);
    $checkStmt->bindParam(':phone_number', $phone_number);
    $checkStmt->execute();
    $phoneNumberCount = $checkStmt->fetchColumn();

    // Check if username already exists
    $checkUsernameSQL = "SELECT COUNT(*) FROM members WHERE username = :username";
    $checkUsernameStmt = $pdo->prepare($checkUsernameSQL);
    $checkUsernameStmt->bindParam(':username', $username);
    $checkUsernameStmt->execute();
    $usernameCount = $checkUsernameStmt->fetchColumn();

    if ($phoneNumberCount > 0) {
        // Phone number already exists
        echo "<script>alert('Phone number already registered. Please use a different phone number.'); window.location.href = 'add_member.php';</script>";
    } elseif ($usernameCount > 0) {
        // Username already exists
        echo "<script>alert('Username already registered. Please use a different username.'); window.location.href = 'add_member.php';</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new member
        $sql = "INSERT INTO members (first_name, last_name, phone_number, type, address, join_date, username, password) 
                VALUES (:first_name, :last_name, :phone_number, :type, :address, :join_date, :username, :password)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':join_date', $join_date);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            // Redirect based on type
            if ($type === 'Admin') {
                echo "<script>alert('Admin registered successfully!'); window.location.href = 'manage_members.php';</script>";
            } else {
                echo "<script>alert('Member registered successfully!'); window.location.href = 'manage_members.php';</script>";
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "<script>alert('Registration failed: " . $errorInfo[2] . "'); window.location.href = 'add_member.php';</script>";
        }
    }
}
?>
