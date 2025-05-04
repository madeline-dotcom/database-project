<?php
session_start();
include '../php/template.php';
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'employee') {
    header("Location: Login.html");
    exit();
}
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard</title>
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

    .card-container {
      display: flex;
      justify-content: center;
      gap: 60px;
      margin-top: 60px;
      flex-wrap: nowrap;
    }

    .card {
      width: 400px;
      height: 500px;
      border: 1px solid #000;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      text-align: center;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      background-color: #fff;
    }

    .card:hover {
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.25);
      transform: translateY(-5px);
      cursor: pointer;
    }

    .card-top {
      flex: 3; /* 75% of the card height */
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0;
    }

    .card-top img {
      width: 75%;
      height: 75%;
      object-fit: contain;
    }

    .card-bottom {
      flex: 1; /* 25% of height */
      padding: 20px;
      border-top: 1px solid #000;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .card-bottom h3 {
      margin: 0 0 10px 0;
      font-size: 22px;
      color: #000;
      text-decoration: underline;
    }

    .card-bottom p {
      margin: 0;
      font-size: 16px;
      color: #000;
      line-height: 1.4;
    }

    .ticket-history {
      background-color: #c9abd1;
    }

    .my-tickets {
      background-color: #a4d3f4;
    }

    .devices {
      background-color: #fdf1dc;
    }
    .device-img {
      margin-left: 45px;
      width: 75%;
      height: 75%;
      object-fit: contain;
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
      <?php 
        // Fetch and display employee ID
        $stmt = $conn->prepare("SELECT userID FROM users WHERE username = ?");
        if (!$stmt) {
            echo "Database error: " . $conn->error;
            exit;
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($employeeID);
        $stmt->fetch();
        $stmt->close();
        $_SESSION['employeeID'] = $employeeID;
        echo "EmployeeID: " . htmlspecialchars($employeeID);
      ?>
    </div>
  </div>

  <h1 class="welcome-heading">Welcome, <?php echo htmlspecialchars($username); ?></h1>

  <div class="card-container">

    <div class="card ticket-history" onclick="document.location='TicketHistory.php'">
      <div class="card-top">
        <img src="../images/ticketHistory.png" alt="Ticket History Icon">
      </div>
      <div class="card-bottom">
        <h3>Ticket History</h3>
        <p>You can search, view, and start tickets as needed for support.</p>
      </div>
    </div>

    <div class="card my-tickets" onclick="document.location='myTickets.php'">
      <div class="card-top">
        <img src="../images/myTicket.png" alt="My Tickets Icon">
      </div>
      <div class="card-bottom">
        <h3>My Tickets</h3>
        <p>Review and close the tickets currently assigned to you.</p>
      </div>
    </div>

    <div class="card devices" onclick="document.location='DeviceMng.php'">
      <div class="card-top">
      <img src="../images/devices.png" alt="Devices Icon" class="device-img">
      </div>
      <div class="card-bottom">
        <h3>Devices</h3>
        <p>Search, add, or remove devices in the system.</p>
      </div>
    </div>

  </div>

  <button class="logout-button" onclick="document.location='../php/logout.php'">LOGOUT</button>

  <script>
    window.addEventListener('pageshow', function (event) {
      if (event.persisted) {
        window.location.reload();
      }
    });
  </script>

</body>
</html>
