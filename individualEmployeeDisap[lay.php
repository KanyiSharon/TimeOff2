<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        main {
            width: 90%;
            margin: auto;
            padding-top: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        th, td {
            padding: 1rem;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        nav a:hover {
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navigation Bar -->
    <nav class="bg-green-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="Timeoff[1].jpg" alt="TimeOff Logo" class="w-10 h-10">
                    <span class="text-white text-2xl font-semibold">TimeOff</span>
                </div>
                <div class="flex space-x-4">
                    <a href="filterByNameOrID.php" class="text-green-600 bg-white hover:bg-gray-200 px-3 py-2 rounded-md">Search Employee Leave Records</a>
                    <a href="filterByMonthAndName.php" class="text-green-600 bg-white hover:bg-gray-200 px-3 py-2 rounded-md">View Monthly Leave Summary</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Leave History</h2>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table>
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-gray-700 font-semibold">Leave Type</th>
                        <th class="text-gray-700 font-semibold">Start Date</th>
                        <th class="text-gray-700 font-semibold">End Date</th>
                        <th class="text-gray-700 font-semibold">Approval Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($results)): ?>
                        <?php foreach ($results as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['leave_type']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['start_date']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['end_date']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['approval_status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">No leave history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
