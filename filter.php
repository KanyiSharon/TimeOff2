<?php
include 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Fetch all results initially
    $sql = "SELECT employee_id, employee_name, leave_type, start_date, end_date FROM leaverequests";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $allResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Apply filter if search term is provided
    if ($search) {
        $sql .= " WHERE employee_id LIKE :search OR employee_name LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->execute();
        $filteredResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $filteredResults = $allResults;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $allResults = [];
    $filteredResults = [];
}

$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Page</title>
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
        }

        .navbar {
            background-color: #1a3a1a;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 40px;
            height: 40px;
        }

        .brand-name {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #a8c9a1;
        }

        .content-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: linear-gradient(135deg, #a8c9a1 0%, #86a886 100%);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1a3a1a;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 40%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(26, 58, 26, 0.1);
        }

        th {
            background-color: #1a3a1a;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.95);
        }

        .empty-message {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }

            .nav-links {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .content-container {
                margin: 2rem 1rem;
                padding: 1rem;
            }

            th, td {
                padding: 0.8rem;
                font-size: 0.9rem;
            }
        }

        div[style="overflow-x:auto;"] {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>

</head>
<body>
<nav class="navbar">
        <div class="logo-container">
            <img src="Timeoff[1].jpg" alt="TimeOff Logo" class="logo">
            <span class="brand-name">TimeOff</span>
        </div>
        <div class="nav-links">
            <a href="filterRequests.php">Filter requests</a>
            <a href="LeaveRequest.php">Total leaves in a month</a>
            <a href="#history">Leave History</a>
        </div>
    </nav>
    <main>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search Employee by ID or Name">
            <button type="submit"><i class="bi bi-search"></i>Search</button>
        </form>
        <div style="overflow-x:auto;">
            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
                <?php
                if (count($filteredResults) > 0) {
                    foreach ($filteredResults as $row) {
                        echo "<tr><td>" . $row["employee_id"] . "</td><td>" . $row["employee_name"] . "</td><td>" . $row["leave_type"] . "</td><td>" . $row["start_date"] . "</td><td>" . $row["end_date"] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No results found</td></tr>";
                }
                ?>
            </table>
        </div>
    </main>
</body>
</html>