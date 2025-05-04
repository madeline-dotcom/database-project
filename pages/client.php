<?php
session_start();
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'client') {
    header("Location: Login.html");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Client Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
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

    .card-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-top: 60px;
    }

    .card {
      width: 280px;
      border: 1px solid #000;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card-top {
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 200px;
    }

    .card-top img {
      width: 120px;
      height: auto;
    }

    .card-bottom {
      padding: 20px;
      border-top: 1px solid #000;
    }

    .card-bottom h3 {
      margin: 0 0 10px 0;
      font-size: 18px;
      text-decoration: underline;
      color: #000;
    }

    .card-bottom p {
      margin: 0;
      font-size: 14px;
      color: #000;
    }

    .submit {
      background-color: #c9abd1;
    }

    .mytickets {
      background-color: #a4d3f4;
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
  <div class="welcome-message" style="margin-left: auto; font-size: 18px; font-weight: bold; color: #000;">
    Welcome, <?php echo htmlspecialchars($username); ?>
  </div>
</div>

<div class="card-container">
  <div class="card submit">
    <div class="card-top">
      <img src="../images/ticketSubmission.png" alt="Submit Ticket Icon">
    </div>
    <div class="card-bottom">
      <h3><a href="TicketSubmission.php">Submit a Ticket</a></h3>
      <p>Allows a client to file a ticket; needs to enter clientID, device type, device serial number. <br> Tickets are open.</p>
    </div>
  </div>

  <div class="card mytickets">
    <div class="card-top">
      <img src="../images/myTicket.png" alt="My Tickets Icon">
    </div>
    <div class="card-bottom">
      <h3><a href="myTicketsClient.php">My Tickets</a></h3>
      <p>Allows the clients to enter their ID and view all their tickets</p>
    </div>
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
