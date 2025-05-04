<?php
session_start();
include '../php/template.php';
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'admin') {
    header("Location: Login.html");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #dbe4ff;
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

    .welcome-message {
      margin-left: auto;
      font-size: 18px;
      font-weight: bold;
      color: #000;
    }

    .welcome-heading {
      text-align: center;
      margin-top: 40px;
      font-size: 32px;
      color: #000;
      font-weight: bold;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      padding: 40px 60px 120px;
      justify-items: center;
    }

    .tile {
      width: 100%;
      max-width: 300px;
      height: 160px;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      font-size: 20px;
      text-decoration: underline;
      font-weight: bold;
      box-shadow: 0 0 8px rgba(0,0,0,0.2);
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .tile img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      margin-bottom: 10px;
    }

    /* Tile colors */
    .employee { background-color: #d4b8d6; }
    .client   { background-color: #9dc9f2; }
    .device   { background-color: #fdf1dc; }
    .register { background-color: #9dc9f2; }
    .submit   { background-color: #fdf1dc; }
    .history  { background-color: #d4b8d6; }

    .tile:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .logout-button {
      position: absolute;
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
  </style>
</head>
<body>

  <div class="top-bar">
    <img src="../images/ant.png" alt="Logo" class="logo">
    <div class="company-name">ANT IT Company</div>
    <div class="welcome-message">
      UserID: <?php 
        $stmt = $conn->prepare("SELECT userID FROM users WHERE username = ?");
        if (!$stmt) {
            echo "Database error: " . $conn->error;
            exit;
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($userID);
        $stmt->fetch();
        $stmt->close();
        $_SESSION['userID'] = $userID;
        echo htmlspecialchars($userID);
      ?>
    </div>
  </div>

  <h1 class="welcome-heading">Welcome, <?php echo htmlspecialchars($username); ?></h1>

  <div class="grid-container">

    <div class="tile employee" onclick="location.href='EmployeeMng.php'">
      <img src="../images/employeeMng.png" alt="Employee Mng Icon">
      <div>Employee Management</div>
    </div>

    <div class="tile client" onclick="location.href='ClientMng.php'">
      <img src="../images/clientMng.png" alt="Client Mng Icon">
      <div>Client Management</div>
    </div>

    <div class="tile device" onclick="location.href='DeviceMng.php'">
      <img src="../images/devices.png" alt="Devices Icon">
      <div>Devices</div>
    </div>

    <div class="tile register" onclick="location.href='registerNewUser.php'">
      <img src="../images/registerNewUser.png" alt="Register Icon">
      <div>Register New User</div>
    </div>

    <div class="tile submit" onclick="location.href='TicketSubmission.php'">
      <img src="../images/ticketSubmission.png" alt="Submit Icon">
      <div>Ticket Submission</div>
    </div>

    <div class="tile history" onclick="location.href='TicketHistory.php'">
      <img src="../images/ticketHistory.png" alt="Ticket Icon">
      <div>Ticket History</div>
    </div>

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
