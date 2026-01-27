<?php
session_start();
include('../../config/db.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn,
        "SELECT * FROM admins WHERE username='$username' AND password='$password'"
    );

    if (mysqli_num_rows($query) == 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: ../feedback/list.php");
        exit;
    } else {
        $error = "Invalid login credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Login - Hospital Feedback Management</title>
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }
    .login-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      max-width: 450px;
      width: 100%;
      animation: slideUp 0.5s ease-out;
    }
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .login-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 40px 20px;
      text-align: center;
      color: white;
    }
    .login-header h1 {
      margin: 0;
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 5px;
    }
    .login-header p {
      margin: 0;
      font-size: 14px;
      opacity: 0.9;
    }
    .login-header-icon {
      font-size: 48px;
      margin-bottom: 15px;
    }
    .login-body {
      padding: 40px 30px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #333;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      font-family: 'Poppins', sans-serif;
    }
    .form-group input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .form-group input::placeholder {
      color: #999;
    }
    .login-button {
      width: 100%;
      padding: 12px 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-top: 10px;
    }
    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    .login-button:active {
      transform: translateY(0);
    }
    .error-message {
      background-color: #fee;
      color: #c33;
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border-left: 4px solid #c33;
      font-size: 14px;
      animation: shake 0.3s ease-in-out;
    }
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
    .login-footer {
      text-align: center;
      padding: 20px 30px;
      background-color: #f9f9f9;
      border-top: 1px solid #e0e0e0;
      font-size: 13px;
      color: #666;
    }
    .login-footer i {
      color: #667eea;
      margin: 0 3px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <div class="login-header-icon">
        <i class="typcn typcn-lock-closed"></i>
      </div>
      <h1>Hospital Admin</h1>
      <p>Feedback Management System</p>
    </div>
    
    <div class="login-body">
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <i class="typcn typcn-times-outline"></i> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label for="username">
            <i class="typcn typcn-user"></i> Username
          </label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
        </div>

        <div class="form-group">
          <label for="password">
            <i class="typcn typcn-lock"></i> Password
          </label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="login-button">
          <i class="typcn typcn-lock-closed"></i> Login
        </button>
      </form>
    </div>

    <div class="login-footer">
      <i class="typcn typcn-info-large"></i>
      Secure Access Only
    </div>
  </div>
</body>
</html>
