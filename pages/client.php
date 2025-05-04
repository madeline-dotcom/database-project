<?php
session_start();
include '../php/template.php';
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

    .welcome-message {
      margin-left: auto;
      font-size: 18px;
      font-weight: bold;
      color: #000;
    }

    .card-container {
      display: flex;
      justify-content: center;
      gap: 60px;
      margin-top: 80px;
    }

    .card-link {
      text-decoration: none;
      color: inherit;
      display: inline-block;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-link:hover .card {
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.25);
      transform: translateY(-5px);
      cursor: pointer;
    }

    .card {
      width: 400px;
      height: 500px;
      border: 1px solid #000;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .card-top {
      height: 260px;
      padding: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card-top img {
      width: 160px;
      height: auto;
    }

    .card-bottom {
      height: 240px;
      padding: 30px;
      border-top: 1px solid #000;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .card-bottom h3 {
      margin: 0 0 15px 0;
      font-size: 24px;
      color: #000;
      text-decoration: underline;
    }

    .card-bottom p {
      margin: 0;
      font-size: 16px;
      color: #000;
      line-height: 1.4;
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

    .welcome-heading {
      text-align: center;
      margin-top: 40px;
      font-size: 32px;
      color: #000;
      font-weight: bold;
    }

  </style>
</head>
<body>

<div class="top-bar">
  <img src="../images/ant.png" alt="Logo" class="logo">
  <div class="company-name">ANT IT Company</div>
  <div class="welcome-message">
    ClientID: <?php 
        // Get the userId from the users table based on the username
        $stmt = $conn->prepare("SELECT userID FROM users WHERE username = ?");
        if (!$stmt) {
            echo "Database error: " . $conn->error;
            exit;
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($clientID);
        $stmt->fetch();
        $stmt->close();
        // Store the clientID in the session
        $_SESSION['clientID'] = $clientID;
        // Display the clientID
        echo htmlspecialchars($clientID);
    ?>
  </div>
</div>
<h1 class="welcome-heading">Welcome, <?php echo htmlspecialchars($username); ?></h1>
<div class="card-container">
  <a href="TicketSubmission.php" class="card-link">
    <div class="card submit">
      <div class="card-top">
        <img src="../images/ticketSubmission.png" alt="Submit Ticket Icon">
      </div>
      <div class="card-bottom">
        <h3>Submit a Ticket</h3>
        <p>File a ticket by entering your client ID, device type, and serial number. Tickets will be marked as open.</p>
      </div>
    </div>
  </a>

  <a href="myTicketsClient.php" class="card-link">
    <div class="card mytickets">
      <div class="card-top">
        <img src="../images/myTicket.png" alt="My Tickets Icon">
      </div>
      <div class="card-bottom">
        <h3>My Tickets</h3>
        <p>Enter your client ID to view the status and details of all your submitted tickets.</p>
      </div>
    </div>
  </a>
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
