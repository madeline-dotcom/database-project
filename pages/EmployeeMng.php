<?php
session_start();
// Ensure the user is an admin, or redirect them to the login page
if (!isset($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'admin') {
    header("Location: ../pages/Login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Employee Management</title>
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
      flex-direction: column;
      align-items: center;
      gap: 40px;
      padding: 40px;
    }

    .form-card {
      background-color: white;
      color: #000;
      width: 500px;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border: 1px solid #000;
    }

    .form-card h2 {
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
    input[type="number"] {
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
      .form-card {
        width: 90%;
      }
    }

    .employee-table-wrapper {
      max-height: 400px;
      overflow-y: auto;
      margin-top: 20px;
      border: 1px solid #000;
      border-radius: 8px;
      background-color: white;
    }

    .employee-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 16px;
    }

    .employee-table thead {
      background-color: #c9abd1;
      color: #000;
      position: sticky;
      top: 0;
    }

    .employee-table th,
    .employee-table td {
      padding: 12px 16px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    .employee-table tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .employee-table tbody tr:hover {
      background-color: #e9e9ff;
    }

    .employee-list-section {
      width: 40%;
      float: left;
      padding: 20px;
    }

    #employeeTable table {
      width: 100%;
      border-collapse: collapse;
    }

    #employeeTable th,
    #employeeTable td {
      padding: 12px 16px;
      border: 1px solid #ccc;
      text-align: left;
    }

    #employeeTable tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    #paginationControls {
      margin-top: 10px;
      text-align: left;
    }

    .pagination-button {
      margin-right: 10px;
      padding: 6px 12px;
      background-color: #c9abd1;
      border: 1px solid #000;
      cursor: pointer;
      font-weight: bold;
    }

    .pagination-button:hover {
      background-color: #b89bc3;
    }

    .main-content {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 40px;
      width: 100%;
      max-width: 1200px;
    }

    .employee-list-section {
      flex: 1;
      padding: 20px;
    }

    .forms-section {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 40px;
    }
  </style>
</head>

<body>

  <div class="top-bar">
    <img src="../images/ant.png" alt="Logo" class="logo">
    <div class="company-name">ANT IT Company</div>
    <div class="user-info">
      <span>Employee Management</span>
      <button class="home-button" onclick="document.location='adminPage.php'">Home</button>
    </div>
  </div>

  <div class="card-container">
    <div class="main-content">

      <div class="employee-list-section">
        <div class="form-card">
        <h2>Employee List</h2>
        <div id="employeeTable"></div>
        <div id="paginationControls"></div>
      </div>
      </div>

      <script>
        let currentPage = 1;
        const recordsPerPage = 10;

        function loadEmployees(page = 1) {
          fetch(`../php/viewAllEmployees.php?page=${page}`)
            .then(response => response.json())
            .then(data => {
              if (data.error) {
                console.error(data.error);
                document.getElementById('employeeTable').innerHTML = '<p>Error loading data.</p>';
                return;
              }

              const employees = data.data;
              const totalCount = data.totalCount;
              const totalPages = data.totalPages;

              // Update the table with the employee data
              const tableHtml = employees.map(employee => `
        <tr>
          <td>${employee.Name}</td>
          <td>${employee.EmployeeID}</td>
        </tr>
      `).join('');
              document.getElementById('employeeTable').innerHTML = `
        <table class="employee-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Employee ID</th>
            </tr>
          </thead>
          <tbody>${tableHtml}</tbody>
        </table>
      `;

              // Update pagination controls
              let paginationHtml = '';
              for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<button class="pagination-button" onclick="loadEmployees(${i})">${i}</button>`;
              }
              document.getElementById('paginationControls').innerHTML = paginationHtml;
            })
            .catch(error => {
              console.error('Error loading employee table:', error);
              document.getElementById('employeeTable').innerHTML = '<p>Error loading data.</p>';
            });
        }

        // Load employees on page load
        window.onload = function () {
          loadEmployees(currentPage);
        };

      </script>



      <div class="forms-section">
        <!-- Add Employee Form (Top) -->
        <div class="form-card">
          <h2>Add Employee</h2>
          <form id="addEmployeeForm" action="../php/newEmployee.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="employeeID">Employee ID:</label>
            <input type="number" id="employeeID" name="employeeID" required>

            <input type="submit" value="Submit">
          </form>
        </div>

        <script>
          document.getElementById('addEmployeeForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const form = e.target;
            const formData = new FormData(form);

            fetch('../php/newEmployee.php', {
              method: 'POST',
              body: formData
            })
              .then(response => response.json())
              .then(data => {
                if (data.status === 'success') {
                  alert(data.message);
                  form.reset(); // Optionally reset the form
                  loadEmployees();
                } else {
                  alert(data.message || 'Error adding employee.');
                }
              })
              .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
              });
          });
        </script>

        <div class="form-card">
          <h2>Remove Employee</h2>
          <form id="removeEmployeeForm" action="../php/removeEmployee.php" method="post">
            <label for="employeeID">Employee ID:</label>
            <input type="text" id="employeeID" name="employeeID" required>

            <input type="submit" value="Submit">
          </form>
        </div>
      </div>


      <script>
        document.getElementById('removeEmployeeForm').addEventListener('submit', function (e) {
          e.preventDefault();

          const form = e.target;
          const formData = new FormData(form);

          fetch('../php/removeEmployee.php', {
            method: 'POST',
            body: formData
          })
            .then(response => response.json())
            .then(data => {
              alert(data.message);
              if (data.status === 'success') {
                form.reset();
                loadEmployees();
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('An unexpected error occurred.');
            });
        });
      </script>

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