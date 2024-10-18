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
// For example: 
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
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Normal User Panel</h2>
            <nav>
                <ul>
                    <li><a href="create_campaign.php"><i class="fas fa-plus"></i> Create New Campaign</a></li>
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
            <section>
                <!-- Here you can display campaign data or any relevant information -->
                <h2>Your Campaigns</h2>
                <p>Details about current campaigns can be displayed here.</p>
            </section>
        </div>
    </div>
</body>
</html>
