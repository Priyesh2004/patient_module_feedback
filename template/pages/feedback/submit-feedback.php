<?php
include('../../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $patient_id     = $_POST['fbPatientID'] ?? null;
    $name           = $_POST['fbName'];
    $email          = $_POST['fbEmail'];
    $phone          = $_POST['fbPhone'];
    $feedback_type  = $_POST['fbType'];
    $department     = $_POST['fbDepartment'] ?? null;
    $staff_name     = $_POST['fbStaff'] ?? null;
    $experience_date= $_POST['fbDate'];
    $subject        = $_POST['fbSubject'];
    $message        = $_POST['fbMessage'];
    $rating         = $_POST['rating'] ?? null;
    $is_confidential= isset($_POST['confidential']) ? 1 : 0;
    $consent        = isset($_POST['followup']) ? 1 : 0;

    // SQL Query
    $sql = "INSERT INTO patient_feedback
    (patient_id, name, email, phone, feedback_type, department, staff_name,
     experience_date, subject, message, rating, is_confidential, consent)
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssiii",
        $patient_id,
        $name,
        $email,
        $phone,
        $feedback_type,
        $department,
        $staff_name,
        $experience_date,
        $subject,
        $message,
        $rating,
        $is_confidential,
        $consent
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Feedback submitted successfully'); window.location.href='submit-feedback.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
