<?php
// Include the database configuration file
include_once 'config.php';  // Ensure this path is correct relative to your directory structure

// SQL queries to create tables
$sql = "
    -- Admin Users Table
    CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Normal Users Table
    CREATE TABLE IF NOT EXISTS normal_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Call Recordings Table
    CREATE TABLE IF NOT EXISTS call_recordings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        call_id VARCHAR(50) NOT NULL,
        recording_path VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES normal_users(id)
    );

    -- Campaigns Table
    CREATE TABLE IF NOT EXISTS campaigns (
        id INT AUTO_INCREMENT PRIMARY KEY,
        campaign_name VARCHAR(100) NOT NULL,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES normal_users(id)
    );

    -- Call Logs Table
    CREATE TABLE IF NOT EXISTS call_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        phone_number VARCHAR(15) NOT NULL,
        call_status VARCHAR(20) NOT NULL,
        call_duration INT,
        call_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES normal_users(id)
    );
";

// Execute the SQL query to create tables
if ($conn->multi_query($sql) === TRUE) {
    // Process all results from the multi_query
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());

    echo "Tables created successfully!<br>";
} else {
    echo "Error creating tables: " . $conn->error;
}

// Insert default admin user with username 'admin' and password 'admin123'
// Note: Password should be hashed for security reasons
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);  // Hash the password for security

$insert_admin = "INSERT IGNORE INTO admin_users (username, password) VALUES ('admin', '$admin_password')";

// Execute the insert query for the admin user
if ($conn->query($insert_admin) === TRUE) {
    echo "Default admin user created successfully!<br>";
} else {
    echo "Error creating default admin user: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
