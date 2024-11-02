<?php
include 'database.php';

try {
    // Establish a connection to the database using PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Retrieve search term from GET request, if provided
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
    <title>TimeOff - Leave Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/lucide-icons@0.263.1/dist/esm/icons/search.js" rel="stylesheet">
    <style>
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        main{
            width: 90%;
        }
        nav a:hover{
            box-shadow: 0 0 10px 0 rgba(0,0,0,0.7);
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="Timeoff[1].jpg" alt="TimeOff Logo" class="w-10 h-10">
                    <span class="text-white text-2xl font-semibold">TimeOff</span>
                </div>
                <div class="flex space-x-4">
                    <a style="background-color:white;color:green;border-radius:10px;" href="filterByNameOrID.php" class="text-white hover:bg-black-600 px-3 py-2 rounded-md">
                        Search Employee Leave Records
                    </a>
                    <a  style="background-color:white;color:green;border-radius:10px;" href="filterByMonthAndName.php" class="text-white hover:bg-green-600 px-3 py-2 rounded-md">
                        View Monthly Leave Summary
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-6">Employee Leave Records</h2>
                
                <!-- Search Form -->
                <form method="GET" action="" class="mb-6 form">
                    <div class="relative max-w-md">
                        <input 
                            style="width:350px;"
                            type="text" 
                            name="search" 
                            placeholder="Search Employee by ID or Name"
                            value="<?php echo htmlspecialchars($search); ?>"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="search-icon h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <button style="border-radius:0 20px 20px 0px;" type="submit" class="absolute right-0 top-0 h-full px-4 bg-green-500 text-white rounded-r-md hover:bg-green-600 focus:outline-none">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Employee ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Employee Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Leave Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Start Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">End Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (count($filteredResults) > 0): ?>
                                <?php foreach ($filteredResults as $row): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($row["employee_id"]); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($row["employee_name"]); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <?php echo htmlspecialchars($row["leave_type"]); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($row["start_date"]); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($row["end_date"]); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No results found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>