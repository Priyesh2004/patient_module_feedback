<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Logout - Hospital Feedback Management</title>
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }
    .logout-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      max-width: 450px;
      width: 100%;
      text-align: center;
      padding: 60px 40px;
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
    .logout-icon {
      font-size: 80px;
      color: #28a745;
      margin-bottom: 20px;
      animation: popIn 0.5s ease-out;
    }
    @keyframes popIn {
      0% {
        transform: scale(0);
      }
      50% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
      }
    }
    .logout-container h1 {
      font-size: 28px;
      color: #333;
      margin: 20px 0 10px 0;
      font-weight: 700;
    }
    .logout-container p {
      color: #666;
      font-size: 16px;
      margin-bottom: 30px;
      line-height: 1.6;
    }
    .logout-button {
      display: inline-block;
      padding: 12px 40px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }
    .logout-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
      text-decoration: none;
      color: white;
    }
    .logout-info {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e0e0e0;
      font-size: 13px;
      color: #999;
    }
    .logout-info i {
      color: #28a745;
      margin-right: 5px;
    }
    .button-group {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 20px;
    }
    .logout-button-secondary {
      display: inline-block;
      padding: 12px 40px;
      background: white;
      color: #667eea;
      text-decoration: none;
      border: 2px solid #667eea;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      cursor: pointer;
    }
    .logout-button-secondary:hover {
      background: #667eea;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="logout-container">
    <div class="logout-icon">
      <i class="typcn typcn-tick"></i>
    </div>
    <h1>Logged Out Successfully</h1>
    <p>You have been securely logged out from the Hospital Feedback Management System.</p>
    
    <div class="button-group">
      <a href="../../index.php" class="logout-button-secondary">
        <i class="typcn typcn-home"></i> Back to Dashboard
      </a>
      
      <a href="login.php" class="logout-button">
        <i class="typcn typcn-lock-closed"></i> Back to Login
      </a>
    </div>

    <div class="logout-info">
      <i class="typcn typcn-info-large"></i>
      Thank you for using our system
    </div>
  </div>
</body>
</html>
