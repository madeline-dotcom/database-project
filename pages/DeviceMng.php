<?php
session_start();
// Ensure the user is an admin or employee
if (!isset($_SESSION['usertype']) || (strtolower($_SESSION['usertype']) !== 'admin' && strtolower($_SESSION['usertype']) !== 'employee')) {
    header("Location: ../pages/Login.html");
    exit();
}
// Determine home URL based on user type
$homeUrl = '#'; // default
switch (strtolower($_SESSION['usertype'])) {
    case 'admin':
        $homeUrl = 'adminPage.php';
        break;
    case 'employee':
        $homeUrl = 'employee.php';
        break;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Device Management</title>
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

    .company-name {
      font-size: 26px;
      font-weight: bold;
      color: #000;
    }

    .logo {
      width: 40px;
      margin-right: 20px;
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

    .card-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px;
      gap: 40px;
    }

    .ticket-card {
      background-color: white;
      color: #000;
      width: 500px;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border: 1px solid #000;
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

    input[type="text"],
    input[type="number"],
    select,
    input[type="date"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #000;
      background-color: #f9f9f9;
    }

    input[type="checkbox"] {
      margin-top: 10px;
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
      <span>Device Management</span>
      <button class="home-button" onclick="location.href='<?php echo $homeUrl; ?>'">Home</button>
    </div>
  </div>

  <div class="card-container">

    <!-- View Devices -->
    <div class="ticket-card">
      <h2>View Devices</h2>
      <form action="../php/viewDevice.php" method="post">
        <label for="serial">Serial Num:</label>
        <input type="number" id="serial" name="serial">

        <label for="clientID">Client ID:</label>
        <input type="number" id="clientID" name="clientID">

        <label for="date">Purchase Date:</label>
        <input type="date" id="date" name="date">

        <input type="checkbox" id="notRecent" name="notRecent" value="1">
        <label for="notRecent">Not Worked on Recently (Past Month)</label>

        <input type="submit" value="Search">
      </form>
    </div>

    <!-- Add Device -->
    <div class="ticket-card">
      <h2>Add Device</h2>
      <form action="../php/newDevice.php" method="post">
        <label for="serial">Serial Num:</label>
        <input type="number" id="serial" name="serial" required>

        <label for="clientID">Client ID:</label>
        <input type="number" id="clientID" name="clientID" required>

        <label for="date">Purchase Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="type">Device Type:</label>
        <select id="type" name="type" required>
          <option value="">--Select--</option>
          <option value="Computer">Computer</option>
          <option value="Printer">Printer</option>
          <option value="Server">Server</option>
        </select>

        <input type="submit" value="Add">
      </form>
    </div>

    <!-- Remove Device -->
    <div class="ticket-card">
      <h2>Remove Device</h2>
      <form action="../php/removeDevice.php" method="post">
        <label for="serial">Serial Num:</label>
        <input type="number" id="serial" name="serial" required>
        <input type="submit" value="Remove">
      </form>
    </div>

  </div>

  <button class="logout-button" onclick="document.location='../php/logout.php'">LOGOUT</button>

  <script>
    // Force reload from server when page is restored via back/forward navigation
    window.addEventListener('pageshow', function (event) {
      if (event.persisted) {
        window.location.reload();
      }
    });
  </script>

</body>
</html>
