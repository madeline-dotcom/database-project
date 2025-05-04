<?php
session_start();
// Ensure the user is an admin, or redirect them to the login page
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'admin') {
    header("Location: ../pages/Login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register New User</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #dde4ff;
    }

    .top-bar {
      background-color: #fdf1dc;
      display: flex;
      align-items: center;
      padding: 20px 40px;
    }

    .logo {
      width: 40px;
      margin-right: 20px;
    }

    .company-name {
      font-size: 26px;
      font-weight: bold;
      color: #000;
    }

    .user-info {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .user-info span {
      font-size: 18px;
      color: #000;
    }

    .home-button {
      padding: 8px 16px;
      background-color: #a4d3f4;
      color: #000;
      border: 1px solid #000;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
    }

    .home-button:hover {
      background-color: #90c0e0;
    }

    .logout-button {
      position: fixed;
      right: 40px;
      bottom: 40px;
      padding: 10px 20px;
      background-color: #dde4ff;
      color: #000;
      border: 1px solid #000;
      font-weight: bold;
      cursor: pointer;
    }

    .logout-button:hover {
      background-color: #ccc;
    }

    .form-card {
      background-color: white;
      color: #000;
      width: 500px;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border: 1px solid #000;
      margin: 80px auto;
    }

    .form-card h2 {
      text-align: center;
      font-size: 24px;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"],
    select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #000;
      background-color: #f9f9f9;
    }

    input[type="submit"] {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background-color: #c9abd1;
      color: #000;
      border: 1px solid #000;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #b89bc3;
    }

    @media (max-width: 1100px) {
      .form-card {
        width: 90%;
      }
    }
  </style>
</head>
<body>

  <div class="top-bar">
    <img src="../images/ant.png" alt="Logo" class="logo">
    <div class="company-name">ANT IT Company</div>
    <div class="user-info">
      <span>Register</span>
      <button class="home-button" onclick="document.location='adminPage.php'">Home</button>
    </div>
  </div>

  <div class="form-card">
    <h2>Register New User</h2>
    <form action="../php/registerNewUser.php" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <label for="UserID">User ID:</label>
      <input type="text" id="UserID" name="UserID" required>

      <label for="usertype">User Type:</label>
      <select id="usertype" name="usertype" required>
        <option value="">--Select--</option>
        <option value="Client">Client</option>
        <option value="Employee">Employee</option>
        <option value="Admin">Admin</option>
      </select>

      <input type="submit" value="Register">
    </form>
  </div>

  <button class="logout-button" onclick="document.location='../php/logout.php'">LOGOUT</button>

  <script>
  // Reload page if restored from back/forward cache (after logout)
  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      window.location.reload();
    }
  });
</script>

</body>
</html>
