<?php
include('config/db.php');

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

/* Pending */
$pending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM patient_feedback WHERE status='pending'")
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

/* Feedback Categories Count */
$categoryQuery = mysqli_query($conn, "
    SELECT feedback_type, COUNT(*) as count 
    FROM patient_feedback 
    GROUP BY feedback_type
");
$categories = [];
while($row = mysqli_fetch_assoc($categoryQuery)) {
    $categories[$row['feedback_type']] = $row['count'];
}

/* Status Distribution */
$statusQuery = mysqli_query($conn, "
    SELECT status, COUNT(*) as count 
    FROM patient_feedback 
    GROUP BY status
");
$statuses = [];
while($row = mysqli_fetch_assoc($statusQuery)) {
    $statuses[$row['status']] = $row['count'];
}

/* Recent Feedback */
$recentResult = mysqli_query($conn, "
    SELECT id, subject, feedback_type, experience_date, status 
    FROM patient_feedback 
    ORDER BY created_at DESC 
    LIMIT 4
");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Feedback & Complaints Management System</title>
  <!-- base:css -->
  <link rel="stylesheet" href="vendors/typicons/typicons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="row" id="proBanner">
    <div class="col-12">
      <span class="d-flex align-items-center purchase-popup">
        <p>Feedback & Complaints Management - Monitor and Resolve Patient Feedback</p>
        <i class="typcn typcn-delete-outline" id="bannerClose"></i>
      </span>
    </div>
  </div>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand brand-logo" href="index.php"><img src="images/logo.jpeg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="images/faces/face5.jpg" alt="profile"/>
              <span class="nav-profile-name"> Admin</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="typcn typcn-cog-outline text-primary"></i>
                Settings
              </a>
              <a class="dropdown-item" href="pages/auth/logout.php">
                <i class="typcn typcn-eject text-primary"></i>
                Logout
              </a>
            </div>
          </li>
          <li class="nav-item nav-user-status dropdown">
              <p class="mb-0"> Admin - Welcome</p>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-date dropdown">
            <a class="nav-link d-flex justify-content-center align-items-center" href="javascript:;">
              <h6 class="date mb-0">Today : <?php echo date('M d'); ?></h6>
              <i class="typcn typcn-calendar"></i>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="typcn typcn-th-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <nav class="navbar-breadcrumb col-xl-12 col-12 d-flex flex-row p-0">
      <div class="navbar-links-wrapper d-flex align-items-stretch">
        <div class="nav-link">
          <a href="javascript:;"><i class="typcn typcn-calendar-outline"></i></a>
        </div>
        <div class="nav-link">
          <a href="javascript:;"><i class="typcn typcn-mail"></i></a>
        </div>
        <div class="nav-link">
          <a href="javascript:;"><i class="typcn typcn-folder"></i></a>
        </div>
        <div class="nav-link">
          <a href="javascript:;"><i class="typcn typcn-document-text"></i></a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item ml-0">
            <h4 class="mb-0">Dashboard</h4>
          </li>
          <li class="nav-item">
            <div class="d-flex align-items-baseline">
              <p class="mb-0">Home</p>
              <i class="typcn typcn-chevron-right"></i>
              <p class="mb-0">Main Dashboard</p>
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-search d-none d-md-block mr-0">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search..." aria-label="search" aria-describedby="search">
              <div class="input-group-prepend">
                <span class="input-group-text" id="search">
                  <i class="typcn typcn-zoom"></i>
                </span>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="typcn typcn-device-desktop menu-icon"></i>
              <span class="menu-title">Dashboard</span>
              <div class="badge badge-danger">new</div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#feedback" aria-expanded="false" aria-controls="feedback">
              <i class="typcn typcn-message-typing menu-icon"></i>
              <span class="menu-title">Feedback & Complaints</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="feedback">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="pages/feedback/new-feedback.html">Submit Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/feedback/list.php">View Feedback</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="row">
            <div class="col-xl-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="mb-0 text-muted">Total Feedbacks</p>
                    <p class="mb-0 text-muted text-success">Live</p>
                  </div>
                  <h4><?php echo $total; ?></h4>
                  <div class="progress mt-3 mb-0">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="mb-0 text-muted">Pending Feedback</p>
                    <p class="mb-0 text-muted text-warning">Live</p>
                  </div>
                  <h4><?php echo $pending; ?></h4>
                  <div class="progress mt-3 mb-0">
                    <div class="progress-bar bg-warning" style="width: <?php echo ($total > 0) ? (($pending / $total) * 100) : 0; ?>%"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="mb-0 text-muted">Resolved Issues</p>
                    <p class="mb-0 text-muted text-info">Live</p>
                  </div>
                  <h4><?php echo $resolved; ?></h4>
                  <div class="progress mt-3 mb-0">
                    <div class="progress-bar bg-info" style="width: <?php echo $resolvedRate; ?>%"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="mb-0 text-muted">Avg. Rating</p>
                    <p class="mb-0 text-muted text-success">Live</p>
                  </div>
                  <h4><?php echo $avgRating; ?>/5</h4>
                  <div class="progress mt-3 mb-0">
                    <div class="progress-bar bg-success" style="width: <?php echo ($avgRating / 5) * 100; ?>%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-xl-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body border-bottom">
                  <h6 class="mb-3 text-uppercase font-weight-medium">Feedback Types</h6>
                </div>
                <div class="card-body">
                  <?php foreach(['compliment', 'suggestion', 'complaint', 'concern'] as $type): ?>
                  <div class="list-item pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <p class="mb-0 text-muted"><?php echo ucfirst($type); ?></p>
                        <h6 class="mb-0"><?php echo ($categories[$type] ?? 0); ?> Feedbacks</h6>
                      </div>
                      <h6 class="text-success"><?php echo ($total > 0) ? round((($categories[$type] ?? 0) / $total) * 100) : 0; ?>%</h6>
                    </div>
                    <div class="progress mt-2">
                      <div class="progress-bar bg-success" style="width: <?php echo ($total > 0) ? round((($categories[$type] ?? 0) / $total) * 100) : 0; ?>%"></div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="col-xl-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body border-bottom">
                  <h6 class="mb-3 text-uppercase font-weight-medium">Status Distribution</h6>
                </div>
                <div class="card-body">
                  <?php foreach(['pending', 'in_progress', 'resolved'] as $status): ?>
                  <div class="list-item pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <p class="mb-0 text-muted"><?php echo ucwords(str_replace('_', ' ', $status)); ?></p>
                        <h6 class="mb-0"><?php echo ($statuses[$status] ?? 0); ?> Items</h6>
                      </div>
                      <h6><?php echo ($total > 0) ? round((($statuses[$status] ?? 0) / $total) * 100) : 0; ?>%</h6>
                    </div>
                    <div class="progress mt-2">
                      <div class="progress-bar" style="width: <?php echo ($total > 0) ? round((($statuses[$status] ?? 0) / $total) * 100) : 0; ?>%; background-color: <?php echo ($status == 'resolved' ? '#28a745' : ($status == 'in_progress' ? '#17a2b8' : '#ffc107')); ?>"></div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-xl-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body border-bottom">
                  <h6 class="mb-3 text-uppercase font-weight-medium">Recent Feedback & Complaints</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Subject</th>
                          <th>Type</th>
                          <th>Date</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(mysqli_num_rows($recentResult) > 0): ?>
                          <?php while($row = mysqli_fetch_assoc($recentResult)): ?>
                          <tr>
                            <td>#FB-<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars(substr($row['subject'], 0, 30)); ?></td>
                            <td>
                              <?php
                              $badge = 'secondary';
                              if($row['feedback_type']=='compliment') $badge='success';
                              elseif($row['feedback_type']=='suggestion') $badge='info';
                              elseif($row['feedback_type']=='complaint') $badge='danger';
                              elseif($row['feedback_type']=='concern') $badge='warning';
                              ?>
                              <span class="badge badge-<?php echo $badge; ?>"><?php echo ucfirst($row['feedback_type']); ?></span>
                            </td>
                            <td><?php echo $row['experience_date']; ?></td>
                            <td><span class="badge badge-primary"><?php echo ucfirst($row['status']); ?></span></td>
                          </tr>
                          <?php endwhile; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center text-muted">No feedback found</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body border-bottom">
                  <h6 class="mb-3 text-uppercase font-weight-medium">Quick Actions</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <a href="pages/feedback/new-feedback.html" class="btn btn-block btn-primary btn-sm">
                      <i class="typcn typcn-plus"></i> New Feedback
                    </a>
                  </div>
                  <div class="mb-3">
                    <a href="pages/feedback/list.php" class="btn btn-block btn-info btn-sm">
                      <i class="typcn typcn-th-small-outline"></i> View All
                    </a>
                  </div>
                  <div class="mb-3">
                    <a href="pages/feedback/list.php" class="btn btn-block btn-warning btn-sm">
                      <i class="typcn typcn-flag"></i> Pending Items
                    </a>
                  </div>
                  <div class="mb-3">
                    <a href="pages/feedback/new-feedback.html" class="btn btn-block btn-secondary btn-sm">
                      <i class="typcn typcn-message-typing"></i> Submit Complaint
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- base:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>