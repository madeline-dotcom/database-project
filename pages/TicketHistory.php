<?php
session_start();
// Ensure the user is an admin, or redirect them to the login page
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
    <title>Ticket History</title>
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
            justify-content: center;
            flex-wrap: wrap;
            gap: 40px;
            padding: 40px;
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

        input[type="text"], select {
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
            .card-container {
                flex-direction: column;
                align-items: center;
            }

            .ticket-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="../images/ant.png" alt="Logo" style="width: 40px; margin-right: 20px;">
    <div class="company-name">ANT IT Company</div>
    <div class="user-info">
        <span>Ticket History</span>
        <button class="home-button" onclick="location.href='<?php echo $homeUrl; ?>'">Home</button>
    </div>
</div>

<div class="card-container">
    <div class="ticket-card">
        <h2>Search Tickets</h2>
        <form action="../php/searchTicket.php" method="post">
            <label for="ticketNum">Ticket Number:</label>
            <input type="text" id="ticketNum" name="ticketNum">

            <label for="employeeID">Employee ID:</label>
            <input type="text" id="employeeID" name="employeeID">

            <label for="serialNum">Serial Number:</label>
            <input type="text" id="serialNum" name="serialNum">

            <label for="clientID">Client ID:</label>
            <input type="text" id="clientID" name="clientID">

            <label for="deviceType">Device Type:</label>
            <select id="deviceType" name="deviceType">
                <option value="">--Select--</option>
                <option value="Computer">Computer</option>
                <option value="Printer">Printer</option>
                <option value="Server">Server</option>
            </select>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="">--Select--</option>
                <option value="Open">Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Closed">Closed</option>
            </select>

            <input type="submit" value="Search">
        </form>
    </div>

    <div class="ticket-card">
        <h2>Claim Ticket</h2>
        <form action="../php/claimTicket.php" method="post">
            <label for="ticketNum">Ticket Number:</label>
            <input type="text" id="ticketNum" name="ticketNum" required>

            <label for="employeeID">Employee ID:</label>
            <input type="text" id="employeeID" name="employeeID" required>

            <input type="submit" value="Claim">
        </form>
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
