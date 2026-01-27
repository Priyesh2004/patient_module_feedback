<?php
include('../../config/db.php');

$search = $_POST['search'] ?? '';
$type   = $_POST['type'] ?? '';
$status = $_POST['status'] ?? '';

$where = "WHERE 1";

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (name LIKE '%$search%' OR patient_id LIKE '%$search%')";
}

if (!empty($type)) {
    $type = mysqli_real_escape_string($conn, $type);
    $where .= " AND feedback_type = '$type'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where .= " AND status = '$status'";
}

$sql = "SELECT * FROM patient_feedback $where ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        // Badge color
        $badge = 'secondary';
        if ($row['feedback_type'] == 'compliment') $badge = 'success';
        elseif ($row['feedback_type'] == 'suggestion') $badge = 'info';
        elseif ($row['feedback_type'] == 'complaint') $badge = 'danger';
        elseif ($row['feedback_type'] == 'concern') $badge = 'warning';

        echo "<tr>";
        echo "<td>#FB-{$row['id']}</td>";
        echo "<td>{$row['name']} ({$row['patient_id']})</td>";
        echo "<td>{$row['experience_date']}</td>";
        echo "<td><span class='badge badge-$badge'>".ucfirst($row['feedback_type'])."</span></td>";
        echo "<td>{$row['department']}</td>";

        echo "<td>";
        for ($i = 1; $i <= 5; $i++) {
            echo ($i <= $row['rating'])
                ? "<i class='typcn typcn-star text-warning'></i>"
                : "<i class='typcn typcn-star-outline'></i>";
        }
        echo " {$row['rating']}/5</td>";

        echo "<td><span class='badge badge-primary'>".ucfirst($row['status'])."</span></td>";
        echo "</tr>";
    }

} else {
    echo "<tr>
            <td colspan='7' class='text-center text-muted'>
              No matching feedback found
            </td>
          </tr>";
}
?>
