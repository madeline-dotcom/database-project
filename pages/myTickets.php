<?php
session_start();
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'employee') {
    header("Location: ../pages/Login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tickets</title>
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
    <img src="../images/ant.png" alt="Logo" class="logo">
    <div class="company-name">ANT IT Company</div>
    <div class="user-info">
        <span>My Tickets</span>
        <button class="home-button" onclick="document.location='employee.php'">Home</button>
    </div>
</div>

<div class="card-container">
    <div class="ticket-card">
        <h2>My Tickets</h2>
        <form action="../php/processTickets.php" method="post">
            <label for="employeeID">Employee ID:</label>
            <input type="text" id="employeeID" name="employeeID" required>
            <input type="submit" value="Search">
        </form>
    </div>

    <div class="ticket-card">
        <h2>Close Ticket</h2>
        <form action="../php/closeTicket.php" method="post">
            <label for="ticketNum">Ticket Number:</label>
            <input type="text" id="ticketNum" name="ticketNum" required>
            <input type="submit" value="Close">
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
