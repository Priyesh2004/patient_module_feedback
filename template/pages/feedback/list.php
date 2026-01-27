<?php
include('../../config/db.php');
include('../../config/auth.php');


/* ================= DELETE FEEDBACK ================= */
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $feedback_id = intval($_GET['id']);
    $deleteQuery = "DELETE FROM patient_feedback WHERE id = $feedback_id";
    
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Feedback deleted successfully'); window.location.href='list.php';</script>";
    } else {
        echo "<script>alert('Error deleting feedback: " . mysqli_error($conn) . "');</script>";
    }
}

/* ================= STATISTICS ================= */

/* Total Feedback */
$total = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM patient_feedback")
)['total'];

/* Compliments */
$compliments = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM patient_feedback WHERE feedback_type='compliment'")
)['total'];

/* Complaints */
$complaints = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM patient_feedback WHERE feedback_type='complaint'")
)['total'];

/* Resolved */
$resolved = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM patient_feedback WHERE status='resolved'")
)['total'];

$resolvedRate = ($total > 0) ? round(($resolved / $total) * 100) : 0;

/* Average Rating */
$avgRating = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT ROUND(AVG(rating),1) AS avg FROM patient_feedback WHERE rating IS NOT NULL")
)['avg'] ?? 0;

/* ================= FEEDBACK LIST ================= */
$result = mysqli_query($conn, "SELECT * FROM patient_feedback ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Feedback List - Hospital Management System</title>
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body>
  <!-- ================= TOP NAVBAR ================= -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container-fluid">
      <span class="navbar-brand font-weight-bold">
        <i class="typcn typcn-message-typing text-primary mr-2"></i>
        Feedback Management
      </span>
      <div class="navbar-nav ml-auto">
        <div class="nav-item">
          <span class="nav-link text-muted mr-3">
            <i class="typcn typcn-user-outline mr-1"></i>
            <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
          </span>
        </div>
        <div class="nav-item">
          <a href="../auth/logout.php" class="nav-link btn btn-outline-danger btn-sm">
            <i class="typcn typcn-power-outline mr-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="card mb-4 shadow-sm">
      <div class="card-body py-3">
        <div class="row align-items-end g-3">
          <div class="col-md-4">
            <label class="text-uppercase font-weight-bold text-muted small mb-1" for="searchFeedback">
              <i class="typcn typcn-zoom mr-1"></i>Search
            </label>
            <div class="input-group input-group-sm">
              <input type="text"
                     class="form-control border-left-0 pl-0"
                     placeholder="Search by Name, ID..."
                     id="searchFeedback"
                     style="border: 1px solid #e0e0e0; border-left: 3px solid #007bff; height: 36px;">
              <div class="input-group-append">
                <span class="input-group-text bg-white border-0">
                  <i class="typcn typcn-zoom text-primary"></i>
                </span>
                
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <label class="text-uppercase font-weight-bold text-muted small mb-1" for="filterFbType">
              <i class="typcn typcn-filter mr-1"></i>Type
            </label>
            <select class="form-control form-control-sm border-left-0" id="filterFbType" style="border: 1px solid #e0e0e0; border-left: 3px solid #17a2b8; height: 36px;">
              <option value="">All Types</option>
              <option value="compliment">✓ Compliment</option>
              <option value="suggestion">💡 Suggestion</option>
              <option value="complaint">⚠ Complaint</option>
              <option value="concern">❓ Concern</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="text-uppercase font-weight-bold text-muted small mb-1" for="filterFbStatus">
              <i class="typcn typcn-info-large mr-1"></i>Status
            </label>
            <select class="form-control form-control-sm border-left-0" id="filterFbStatus" style="border: 1px solid #e0e0e0; border-left: 3px solid #28a745; height: 36px;">
              <option value="">All Status</option>
              <option value="pending">⏱ Pending</option>
              <option value="in_progress">⚙ In Progress</option>
              <option value="resolved">✓ Resolved</option>
            </select>
          </div>

          <div class="col-md-2">
            <button class="btn btn-outline-secondary btn-sm w-100" type="reset" title="Reset filters" style="border: 1px solid #e0e0e0; border-left: 3px solid #6c757d; height: 36px;">
              <i class="typcn typcn-times"></i> Reset
            </button>
          </div>

          <div class="col-md-2">
            <a href="#statisticsSection" class="btn btn-info btn-sm w-100" style="height: 36px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: white;">
              <i class="typcn typcn-chart-line mr-1"></i> Statistics
            </a>
          </div>
          
        </div>
        
      </div>
    </div>
  


<!-- ================= TABLE ================= -->
<div class="container-fluid page-body-wrapper">
<div class="main-panel">
<div class="content-wrapper">

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<h4 class="card-title mb-4">Feedback & Complaints</h4>

<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
  <th>ID</th>
  <th>Patient Name</th>
  <th>Date</th>
  <th>Type</th>
  <th>Department</th>
  <th>Rating</th>
  <th>Status</th>
  <th>Actions</th>
</tr>
</thead>


<tbody  id="feedbackTableBody">
<?php if(mysqli_num_rows($result) > 0): ?>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td>#FB-<?php echo $row['id']; ?></td>

<td>
<?php echo htmlspecialchars($row['name']); ?>
<?php if(!empty($row['patient_id'])) echo " ({$row['patient_id']})"; ?>
</td>

<td><?php echo $row['experience_date']; ?></td>

<td>
<?php

$badge = 'secondary';
if($row['feedback_type']=='compliment') $badge='success';
elseif($row['feedback_type']=='suggestion') $badge='info';
elseif($row['feedback_type']=='complaint') $badge='danger';
elseif($row['feedback_type']=='concern') $badge='warning';
?>
<span class="badge badge-<?php echo $badge; ?>">
<?php echo ucfirst($row['feedback_type']); ?>
</span>
</td>

<td><?php echo $row['department']; ?></td>

<td>
<?php
for($i=1;$i<=5;$i++){
  echo ($i <= $row['rating'])
  ? '<i class="typcn typcn-star text-warning"></i>'
  : '<i class="typcn typcn-star-outline"></i>';
}
?>
<small><?php echo $row['rating']; ?>/5</small>
</td>

<td>
<span class="badge badge-primary"><?php echo ucfirst($row['status']); ?></span>
</td>
<td>
<?php if ($row['status'] != 'resolved'): ?>

  <a href="update-status.php?id=<?php echo $row['id']; ?>&status=in_progress"
     class="btn btn-sm btn-warning mb-1">
     In Progress
  </a>

  <a href="update-status.php?id=<?php echo $row['id']; ?>&status=resolved"
     class="btn btn-sm btn-success mb-1"
     onclick="return confirm('Mark this feedback as resolved?');">
     Resolve
  </a>

<?php else: ?>
  <span class="text-muted">No action</span>
<?php endif; ?>

  <a href="list.php?action=delete&id=<?php echo $row['id']; ?>"
     class="btn btn-sm btn-danger"
     onclick="return confirm('Are you sure you want to delete this feedback? This action cannot be undone.');">
     <i class="typcn typcn-trash"></i> Delete
  </a>
</td>

</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="7" class="text-center text-muted">No feedback found</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
</div>
</div>
</div>

<!-- ================= STATISTICS ================= -->

<div class="row mt-4" id="statisticsSection">
<div class="col-lg-6 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<h6 class="card-title mb-3">Feedback Statistics</h6>

<div class="list-item pb-3 border-bottom">
<div class="d-flex justify-content-between">
<span>Total Feedback</span>
<span class="badge badge-light"><?php echo $total; ?></span>
</div>
</div>

<div class="list-item pb-3 border-bottom">
<div class="d-flex justify-content-between">
<span>Compliments</span>
<span class="badge badge-success"><?php echo $compliments; ?></span>
</div>
</div>

<div class="list-item pb-3 border-bottom">
<div class="d-flex justify-content-between">
<span>Complaints</span>
<span class="badge badge-danger"><?php echo $complaints; ?></span>
</div>
</div>

<div class="list-item">
<div class="d-flex justify-content-between">
<span>Resolved Rate</span>
<span class="badge badge-info"><?php echo $resolvedRate; ?>%</span>
</div>
</div>

</div>
</div>
</div>

<div class="col-lg-6 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<h6 class="card-title mb-3">Average Rating</h6>

<div class="list-item pb-3 border-bottom">
<div class="d-flex justify-content-between align-items-center">
<span>Overall Hospital Rating</span>
<div>
<?php
for($i=1;$i<=5;$i++){
  echo ($i <= floor($avgRating))
  ? '<i class="typcn typcn-star text-warning"></i>'
  : '<i class="typcn typcn-star-outline"></i>';
}
?>
<small><strong><?php echo $avgRating; ?>/5</strong></small>
</div>
</div>
</div>

</div>
</div>
</div>

</div>
</div>
</div>

</div>

<script src="../../vendors/js/vendor.bundle.base.js"></script>
<script src="../../js/off-canvas.js"></script>
<script src="../../js/template.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchFeedback");
    const typeFilter  = document.getElementById("filterFbType");
    const statusFilter= document.getElementById("filterFbStatus");
    const tableBody   = document.getElementById("feedbackTableBody");

    if (!searchInput || !typeFilter || !statusFilter || !tableBody) {
        console.error("Required elements not found in DOM");
        return;
    }

    function loadFeedback() {

        const formData = new FormData();
        formData.append("search", searchInput.value);
        formData.append("type", typeFilter.value);
        formData.append("status", statusFilter.value);

        fetch("search-feedback.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            tableBody.innerHTML = data;
        });
    }

    searchInput.addEventListener("keyup", loadFeedback);
    typeFilter.addEventListener("change", loadFeedback);
    statusFilter.addEventListener("change", loadFeedback);

});
</script>


</body>
</html>
