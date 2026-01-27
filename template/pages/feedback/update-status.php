<?php
include('../../config/db.php');
include('../../config/auth.php');

$id = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';

$allowed = ['pending', 'in_progress', 'resolved'];

if (in_array($status, $allowed) && is_numeric($id)) {
    mysqli_query($conn,
        "UPDATE patient_feedback SET status='$status' WHERE id='$id'"
    );
}

header("Location: list.php");
exit;
?>
