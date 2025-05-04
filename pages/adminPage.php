<?php
session_start();
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
  <title>Admin Page</title>
  <style>
    body {
      margin: 0;
      font-family: Open Source;
      background-color: #dbe4ff;
      position: relative;
      min-height: 100vh;
    }

    .header {
      background-color: #fdf1dc;
      display: flex;
      align-items: center;
      padding: 20px 40px;
    }

    .header img {
      height: 50px;
      margin-right: 20px;
    }

    .header h1 {
      font-size: 24px;
      font-weight: bold;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      padding: 40px;
      padding-bottom: 100px; /* extra space to not overlap logout */
    }

    .tile {
      border-radius: 20px;
      padding: 30px;
      text-align: center;
      font-size: 35px;
      text-decoration: underline;
      font-weight: normal;
      box-shadow: 0 0 8px rgba(0,0,0,0.2);
      cursor: pointer;
    }

    .tile img {
      width: 100px;
      height: 100px;
      margin-bottom: 20px;
    }

    .tile.employee { background-color: #d4b8d6; height: 160px; }
    .tile.client   { background-color: #9dc9f2; height: 160px; } 
    .tile.device   { background-color: #fdf1dc; height: 160px; }

    .tile.register { background-color: #9dc9f2; height: 160px; }
    .tile.submit   { background-color: #fdf1dc; height: 160px; }
    .tile.history  { background-color: #d4b8d6; height: 160px; }

    .logout-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      padding: 10px 30px;
      font-size: 16px;
      border: 2px solid black;
      background: white;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="header">
    <img src="../images/ant.png" alt="Ant Logo">
    <h1>ANT IT Company</h1>
    <div class="welcome-message" style="margin-left: auto; font-size: 18px; font-weight: bold; color: #000;">
    Welcome, <?php echo htmlspecialchars($username); ?>
    </div>
  </div>

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

  <button class="logout-btn" onclick="document.location='../php/logout.php'">LOGOUT</button>

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
