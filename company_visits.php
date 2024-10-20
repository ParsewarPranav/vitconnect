<?php
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

$sql = "SELECT visit_date, visit_day, company_name FROM company_visits ORDER BY visit_date DESC";
$stmt = $pdo->query($sql);
$visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Visits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #162251;
            color: #fff;
        }
        h1 {
            text-align: center;
            color: #f8f9fa;
            margin-bottom: 40px;
        }
        .visit-card {
    background-color: #6a9ddc;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    color: white;
}
        .visit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.4);
        }
        .visit-card h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .visit-card p {
            font-size: 1rem;
            color: #ddd;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #0e043b; /* Changed color */
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
    <h1>Upcoming Company Visits</h1>
    <div class="container">
        <?php if (!empty($visits)): ?>
            <?php foreach ($visits as $visit): ?>
                <div class="visit-card">
                    <h2><?php echo htmlspecialchars($visit['company_name']); ?></h2>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($visit['visit_date']); ?></p>
                    <p><strong>Day:</strong> <?php echo htmlspecialchars($visit['visit_day']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-white">No upcoming visits found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
