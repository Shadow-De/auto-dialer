<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'normal') {
    header("Location: ../index.php"); // Redirect to normal user login page
    exit();
}

// Include the database configuration file
include_once 'config.php'; // Ensure this path is correct

// Initialize variables for error messages and success
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campaign_name = trim($_POST['campaign_name']);
    $call_type = $_POST['call_type'];
    $queue_or_extension = $_POST['queue_or_extension'];
    
    // Validate input
    if (empty($campaign_name) || empty($call_type) || empty($queue_or_extension)) {
        $error_message = "All fields are required.";
    } else {
        // Insert the new campaign into the database
        $stmt = $conn->prepare("INSERT INTO campaigns (campaign_name, created_by, queue_or_extension) VALUES (?, ?, ?)");
        
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $created_by = $_SESSION['user_id']; // Assuming user ID is stored in the session
        $stmt->bind_param("sis", $campaign_name, $created_by, $queue_or_extension);

        if ($stmt->execute()) {
            $success_message = "Campaign created successfully!";
        } else {
            $error_message = "Error creating campaign: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch available queues and extensions if needed (optional)
// $queues = getQueues($conn);
// $extensions = getExtensions($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Campaign</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Create New Campaign</h1>
        <?php if ($error_message): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php elseif ($success_message): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="campaign_name">Campaign Name:</label>
            <input type="text" name="campaign_name" required>

            <label for="call_type">Call Type:</label>
            <select name="call_type" required>
                <option value="queue">Queue</option>
                <option value="extension">Extension</option>
            </select>

            <label for="queue_or_extension">Queue Number / Extension Number:</label>
            <input type="text" name="queue_or_extension" required>

            <button type="submit">Create Campaign</button>
        </form>
        <a href="normal_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
