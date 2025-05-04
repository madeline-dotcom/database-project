<?php
session_start();
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'employee') {
    header("Location: Login.html");
    exit();
}
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html>
<head>
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

    .welcome {
      font-size: 40px;
      font-weight: bold;
      margin: 100px 80px 20px;
      color: #000;
    }

    /* Wrapper for centering cards vertically */
    .content {
      display: flex;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 120px); /* 100vh minus the top bar height */
    }

    .card-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 60px;
      flex-wrap: nowrap;
    }

    .card {
      width: 350px; /* Same width */
      height: 300px; /* Same height */
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: center; /* Center content vertically */
      align-items: center; /* Center content horizontally */
      border: 3px solid #000; /* Thick border */
      box-sizing: border-box; /* Include padding in total size */
      cursor: pointer;
      background-color: #fff; /* White background */
      text-align: center; /* Center the text */
      transition: all 0.3s ease; /* Smooth transition for shadow effect and scaling */
    }

    /* Stronger hover effect */
    .card:hover {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4); /* Larger shadow */
      transform: scale(1.05); /* Slight scaling */
    }

    .card h3 {
      font-size: 30px; /* Increased size for heading */
      margin-bottom: 20px;
      text-decoration: underline;
      color: #000;
      font-weight: 700; /* Stronger weight for more impact */
      display: flex;
      align-items: center; /* Center title vertically */
      justify-content: center; /* Center title horizontally */
      height: 40px; /* Fixed height to help with centering */
    }

    /* Adjust "My Tickets" heading */
    .card.my-tickets h3 {
      margin-top: -10px; /* Move the "My Tickets" heading up */
    }

    /* Adjust "Devices" heading */
    .card.devices h3 {
      margin-top: -10px; /* Move the "Devices" heading up */
    }

    .card p {
      font-size: 18px; /* Increased size for paragraph text */
      color: #000;
      margin: 0;
      line-height: 1.5; /* Increased line height for readability */
    }

    .card.ticket-history {
      background-color: #c9abd1;
    }

    .card.my-tickets {
      background-color: #a4d3f4;
    }

    .card.devices {
      background-color: #fdf1dc;
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

<main class="content">
  <div class="card-container">
    <div class="card ticket-history" onclick="document.location='TicketHistory.php'">
      <h3>Ticket History</h3>
      <p>You can search tickets, view all tickets, or find the ones you want to work on.<br>You can also start new tickets.</p>
    </div>
    <div class="card my-tickets" onclick="document.location='myTickets.php'">
      <h3>My Tickets</h3>
      <p>See the tickets assigned to you.<br>Close the ticket once completed.</p>
    </div>
    <div class="card devices" onclick="document.location='DeviceMng.php'">
      <h3>Devices</h3>
      <p>View devices, look up any device,<br>Add device to the system,<br>Remove device from the system.</p>
    </div>
  </div>
</main>

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
