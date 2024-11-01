<?php
include 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=timeoff", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "CONNECTION FAILED: " . $e->getMessage();
}

// CHECKS IF MONTH AND NAME ARE AVAILABLE
$month = isset($_POST['month']) ? (INT)$_POST['month'] : 0;
$employee_name = isset($_POST['employee_name']) ? trim($_POST['employee_name']) : '';

try {
    $sql = "SELECT * FROM leaverequests WHERE 1=1";
    if ($month > 0 && $month <= 12) {
        $sql .= " AND month(start_date) = :month";
    }
    if (!empty($employee_name)) {
        $sql .= " AND employee_name LIKE :employee_name";
    }

    $stmt = $conn->prepare($sql);
    
    if ($month > 0 && $month <= 12) {
        $stmt->bindParam(':month', $month);
    }
    if (!empty($employee_name)) {
        $name_param = "%$employee_name%"; // Use wildcard for LIKE search
        $stmt->bindParam(':employee_name', $name_param);
    }
    
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Your existing styles here */
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
            display: flex;
            justify-content: space-between; /* Align tables side by side */
        }

        table {
            width: 48%; /* Adjust width to fit both tables */
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
            overflow: hidden;
            margin-right: 2%; /* Space between tables */
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
        <form method="POST">
            <label style="display:inline; font-size: 1rem; font-weight: bold;" for="month">Select a month:</label>
            <select name="month" id="month">
                <option value="0" <?= $month === 0 ? 'selected' : '' ?>>All Months</option>
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    $selected = ($i == $month) ? 'selected' : ''; // Set selected if it matches the month
                    echo "<option value=\"$i\" $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                }
                ?>
            </select>
            <label style="display:inline; font-size: 1rem; font-weight: bold;" for="employee_name">Employee Name:</label>
            <input type="text" name="employee_name" id="employee_name" value="<?= htmlspecialchars($employee_name) ?>">
            <button type="submit">Filter</button>
        </form>

        <div class="table-container">
            <table>
                <tr>
                    <th>Employee Id</th>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
                <?php
                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                        echo "<tr><td>" . $row['employee_id'] . "</td>";
                        echo "<td>" . $row['employee_name'] . "</td>";
                        echo "<td>" . $row['leave_type'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['end_date'] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='empty-message'>No records found for this month</td></tr>";
                }
                ?>
            </table>

            <table>
                <tr>
                    <th>Filtered Results</th>
                </tr>
                <?php
                // If no results, display a message
                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                        echo "<tr><td>" . $row['employee_name'] . " - " . $row['leave_type'] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td class='empty-message'>No records found</td></tr>";
                }
                ?>
            </table>
        </div>
    </main>
</body>
</html>
