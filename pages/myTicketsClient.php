<?php
session_start();
// Ensure the user is a client
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'client') {
    header("Location: ../pages/Login.html");
    exit();
}
$clientID = $_SESSION['clientID'] ?? null;

require_once '../php/template.php';

$message = "";
$error = "";
$rows = [];

if ($clientID) {
    $sql = "SELECT TicketNum, DeviceType, Status FROM Ticket WHERE ClientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $clientID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        } else {
            $error = "No tickets found for your account.";
        }
    } else {
        $error = "Error retrieving tickets.";
    }

    $stmt->close();
} else {
    $error = "Client ID not found. Please log in again.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Tickets (Client)</title>
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

    .ticket-card {
      background-color: white;
      color: #000;
      width: 500px;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border: 1px solid #000;
      margin: 80px auto;
    }

    .ticket-card h2 {
      text-align: center;
      font-size: 24px;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"] {
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid #000;
    }

    th, td {
      padding: 12px;
      text-align: center;
      color: #000;
    }

    th {
      background-color: #a4d3f4;
    }

    .message {
      text-align: center;
      padding: 15px;
      border-radius: 6px;
      font-weight: bold;
      margin-top: 20px;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }


    @media (max-width: 1100px) {
      .ticket-card {
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
    <span>My Tickets</span>
    <button class="home-button" onclick="document.location='client.php'">Home</button>
  </div>
</div>

<div class="ticket-card">
  <h2>My Tickets</h2>

  <?php if (!empty($error)): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
  <?php elseif (!empty($rows)): ?>
    <table>
      <tr>
        <th>Ticket Number</th>
        <th>Device Type</th>
        <th>Status</th>
      </tr>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['TicketNum']) ?></td>
          <td><?= htmlspecialchars($row['DeviceType']) ?></td>
          <td><?= htmlspecialchars($row['Status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <div class="message">No tickets to display.</div>
  <?php endif; ?>
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
