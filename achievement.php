<?php
// Database connection
$host = 'localhost';
$dbname = 'connectu';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

// Fetch placement data
$sql = "SELECT * FROM placement_data ORDER BY academic_year";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$placements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Achievements</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin:0;
            padding:0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px !important;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #100247;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #0e043b; 
    color: white;
    position: sticky;
    top: 0;
    z-index: 1000;
}
.brand-title{
    color:blue;
}

.navbar .navbar-brand {
    font-size: 24px;
    font-weight: bold;
    color: rgb(255, 139, 15);
    text-decoration: none;
}

.navbar-links a {
    color: #ffffff; /* Changed color */
    text-decoration: none;
    margin-left: 15px;
    font-size: 16px;
}


.navbar-links a:hover {
    color: #050505; /* Changed color */
}
    </style>
</head>
<body>
<nav class="navbar">
        <a class="navbar-brand d-flex align-items-center" href="student-index.html">
            <div class="logo d-flex align-items-center justify-content-center text-white">
                <span class="brand-title">VIT-Connect.in</span>
            </div>
        </a>
    </nav>

<h1>Placement Achievements</h1>

<table>
    <thead>
        <tr>
            <th>Academic Year</th>
            <th>Branch</th>
            <th>Students Placed</th>
            <th>Highest Package (LPA)</th>
            <th>Average Package (LPA)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($placements as $placement): ?>
            <tr>
                <td><?php echo htmlspecialchars($placement['academic_year']); ?></td>
                <td><?php echo htmlspecialchars($placement['branch']); ?></td>
                <td><?php echo htmlspecialchars($placement['students_placed']); ?></td>
                <td><?php echo htmlspecialchars($placement['highest_package']); ?></td>
                <td><?php echo htmlspecialchars($placement['average_package']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
