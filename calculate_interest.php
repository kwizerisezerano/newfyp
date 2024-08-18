<?php
// calculate_interest.php
require_once 'config.php';

// Get current date
$current_date = date('Y-m-d');

// Fetch loans that need interest calculations
$sql = "SELECT id, loan_amount, interest_rate, last_interest_date FROM loans WHERE status = 'Approved'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($loans as $loan) {
    $loan_id = $loan['id'];
    $loan_amount = $loan['loan_amount'];
    $interest_rate = $loan['interest_rate'];
    $last_interest_date = $loan['last_interest_date'];

    // Calculate the difference in months between the last interest date and today
    $datetime1 = new DateTime($last_interest_date);
    $datetime2 = new DateTime($current_date);
    $interval = $datetime1->diff($datetime2);
    $months = $interval->m + ($interval->y * 12);

    if ($months >= 1) {
        // Calculate interest for the number of months passed
        for ($i = 0; $i < $months; $i++) {
            $interest = $loan_amount * ($interest_rate / 100);
            $loan_amount += $interest;
        }

        // Update the loan amount and last interest date
        $sql = "UPDATE loans SET loan_amount = :loan_amount, last_interest_date = :last_interest_date WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':loan_amount' => $loan_amount,
            ':last_interest_date' => $current_date,
            ':id' => $loan_id,
        ]);
    }
}

echo "Interest calculations completed for all eligible loans.";
?>
