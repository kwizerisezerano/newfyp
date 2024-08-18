<?php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: loginform.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $penalty_amount = $_POST['penalty_amount'];
    $infraction_date = $_POST['infraction_date'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO penalties (member_id, penalty_amount, infraction_date, reason) VALUES (:member_id, :penalty_amount, :infraction_date, :reason)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'member_id' => $member_id,
        'penalty_amount' => $penalty_amount,
        'infraction_date' => $infraction_date,
        'reason' => $reason
    ]);

    header('Location: manage_penalties.php');
    exit;
}
?>
