<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'normal') {
    header("Location: ../index.php"); // Redirect to normal user login page
    exit();
}

// Include the database configuration file
include_once '../db/config.php'; // Ensure this path is correct

// Fetch campaigns or any other required data here
// Example: 
// $campaigns = getCampaigns($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normal User Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            flex-shrink: 0; /* Prevent shrinking */
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar nav ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 10px 0;
        }
        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar nav ul li a:hover {
            background-color: #555;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center align items */
            justify-content: center; /* Center vertically */
        }
        header {
            margin-bottom: 20px;
            text-align: center; /* Center the header text */
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1200px; /* Max width for larger screens */
            width: 100%; /* Full width for smaller screens */
            padding: 20px; /* Padding around the grid */
        }
        .stat-card {
            background: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            transition: background 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }
        .stat-card:hover {
            background: #0056b3;
        }
        .stat-card h3 {
            margin: 10px 0;
        }
        .stat-card i {
            font-size: 30px;
            margin-bottom: 10px; /* Space between icon and text */
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: absolute;
                z-index: 1;
                display: none;
            }
            .sidebar.active {
                display: block;
            }
            header {
                margin-top: 20px; /* Add margin for smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Normal User Panel</h2>
            <nav>
                <ul>
                    <li><a href="../db/create_campaign.php"><i class="fas fa-plus"></i> Create New Campaign</a></li>
                    <li><a href="past_campaigns.php"><i class="fas fa-history"></i> See Past Campaign Details</a></li>
                    <li><a href="current_campaigns.php"><i class="fas fa-tasks"></i> Current Campaign Status</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <header>
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            </header>
            
            <section class="stats-container">
                <div class="stat-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Total Campaigns</h3>
                    <p>10</p> <!-- Fetch and display the actual count from the database -->
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Successful Calls</h3>
                    <p>150</p> <!-- Fetch and display the actual count from the database -->
                </div>
                <div class="stat-card">
                    <i class="fas fa-times-circle"></i>
                    <h3>Failed Calls</h3>
                    <p>5</p> <!-- Fetch and display the actual count from the database -->
                </div>
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <h3>Average Call Duration</h3>
                    <p>2 mins</p> <!-- Fetch and display the actual duration from the database -->
                </div>
            </section>

            <section>
                <h2>Your Campaigns</h2>
                <p>Details about current campaigns can be displayed here.</p>
            </section>
        </div>
    </div>
</body>
</html>
