
<?php
session_start();
include 'template.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Set the number of records per page
    $recordsPerPage = 10;

    // Get the current page from the URL, default to 1 if not set
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate the offset for the query (page - 1) * records per page
    $offset = ($page - 1) * $recordsPerPage;

    // Get the total count of employees to calculate the total number of pages
    $totalCountQuery = "SELECT COUNT(*) AS total FROM Client";
    $totalCountResult = $conn->query($totalCountQuery);

    if (!$totalCountResult) {
        echo json_encode(['error' => 'Error fetching total client count: ' . $conn->error]);
        exit;
    }

    $totalCountRow = $totalCountResult->fetch_assoc();
    $totalCount = $totalCountRow['total'];

    // Calculate the total number of pages
    $totalPages = ceil($totalCount / $recordsPerPage);

    // Prepare the query to get the employees for the current page
    $sql = "SELECT ClientID, Name, Location FROM Client LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $recordsPerPage, $offset); // bind parameters for LIMIT and OFFSET

        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $clients = [];

            // Fetch all rows
            while ($row = $result->fetch_assoc()) {
                $clients[] = $row;
            }

            // Return data as JSON
            echo json_encode([
                'data' => $clients,
                'totalCount' => $totalCount,
                'totalPages' => $totalPages
            ]);
        } else {
            echo json_encode(['error' => 'Error executing query: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['error' => 'Error preparing the query: ' . $conn->error]);
    }

    // Close the connection
    $conn->close();
}
?>