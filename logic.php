<?php
// --- DATABASE CONNECTION ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "servo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . ". Please make sure the database 'servo' exists.");
}

// --- CHECK IF TABLES EXIST AND CREATE THEM IF NOT ---
$tableNameAngles = 'angles';
if ($conn->query("SHOW TABLES LIKE '$tableNameAngles'")->num_rows == 0) {
    $sqlCreateTableAngles = "CREATE TABLE angles (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        servo1 INT(3) NOT NULL DEFAULT 90,
        servo2 INT(3) NOT NULL DEFAULT 90,
        servo3 INT(3) NOT NULL DEFAULT 90,
        servo4 INT(3) NOT NULL DEFAULT 90,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if (!$conn->query($sqlCreateTableAngles)) {
        die("Error creating table 'angles': " . $conn->error);
    }
}

// --- INITIALIZE SLIDER VALUES ---
// Default to 90 degrees for all servos
$servo1_angle = 90;
$servo2_angle = 90;
$servo3_angle = 90;
$servo4_angle = 90;
$feedback_message = '';


// --- LOGIC TO HANDLE ACTIONS ---

// 1. Handle REMOVE request (from the table)
if (isset($_GET['remove_id'])) {
    $id_to_remove = (int)$_GET['remove_id'];
    $stmt = $conn->prepare("DELETE FROM angles WHERE id = ?");
    $stmt->bind_param("i", $id_to_remove);
    $stmt->execute();
    header("Location: index.php?status=removed"); // Redirect to clean URL
    exit();
}

// 2. Handle LOAD request (from the table)
if (isset($_GET['load_id'])) {
    $id_to_load = (int)$_GET['load_id'];
    $stmt = $conn->prepare("SELECT servo1, servo2, servo3, servo4 FROM angles WHERE id = ?");
    $stmt->bind_param("i", $id_to_load);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $servo1_angle = $row['servo1'];
        $servo2_angle = $row['servo2'];
        $servo3_angle = $row['servo3'];
        $servo4_angle = $row['servo4'];
        $feedback_message = "Position ID $id_to_load loaded into sliders.";
    }
}

// 3. Handle FORM SUBMISSIONS (from the sliders panel)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get current angles from the form post
    $servo1_angle = (int)$_POST['servo1'];
    $servo2_angle = (int)$_POST['servo2'];
    $servo3_angle = (int)$_POST['servo3'];
    $servo4_angle = (int)$_POST['servo4'];

    // a. Handle RESET button
    if (isset($_POST['reset'])) {
        $servo1_angle = 90;
        $servo2_angle = 90;
        $servo3_angle = 90;
        $servo4_angle = 90;
        $feedback_message = "Sliders have been reset to 90 degrees.";
    }

    // b. Handle SAVE POSITION button
    if (isset($_POST['save_position'])) {
        $stmt = $conn->prepare("INSERT INTO angles (servo1, servo2, servo3, servo4) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $servo1_angle, $servo2_angle, $servo3_angle, $servo4_angle);
        $stmt->execute();
        header("Location: index.php?status=saved"); // Redirect to prevent re-submission
        exit();
    }

    // c. Handle SUBMIT TO ESP button
    if (isset($_POST['submit_to_esp'])) {
        $angles_data = "{$servo1_angle},{$servo2_angle},{$servo3_angle},{$servo4_angle}";
        // Create/overwrite the angles.php file with the comma-separated values
        if (file_put_contents('angles.php', $angles_data) !== false) {
             $feedback_message = "Success! File 'angles.php' created/updated with values: " . htmlspecialchars($angles_data);
        } else {
             $feedback_message = "Error: Could not write to file 'angles.php'. Check folder permissions.";
        }
    }
}

// --- FETCH ALL SAVED POSITIONS FROM DATABASE TO DISPLAY ---
$saved_positions = [];
$sql = "SELECT id, servo1, servo2, servo3, servo4 FROM angles ORDER BY id ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $saved_positions[] = $row;
    }
}

// Close the connection at the end of the script
$conn->close();
?>