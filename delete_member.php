<?php
// delete_member.php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Check if the member ID is referenced in the contributions table
        $sql = 'SELECT COUNT(*) FROM contributions WHERE member_id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Member ID is referenced, display an error message and redirect
            $_SESSION['error'] = 'Cannot delete member as they have existing contributions.';
            header('Location: manage_members.php');
            exit;
        }

        // No references found, proceed with deletion
        $sql = 'DELETE FROM members WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $_SESSION['success'] = 'Member deleted successfully.';
        header('Location: manage_members.php');
        exit;

    } catch (PDOException $e) {
        // Handle any other errors
        $_SESSION['error'] = 'Error deleting member: ' . $e->getMessage();
        header('Location: manage_members.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'ID not specified.';
    header('Location: manage_members.php');
    exit;
}
?>
