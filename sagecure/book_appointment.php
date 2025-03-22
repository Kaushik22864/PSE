<?php
header('Content-Type: application/json'); // Ensure JSON response

$servername = "localhost";
$username = "root";
$password = "";
$database = "sagecure_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['patient_name']) && !empty($_POST['patient_email']) && !empty($_POST['appointment_date']) && !empty($_POST['appointment_time'])) {
        
        $name = $conn->real_escape_string($_POST['patient_name']);
        $email = $conn->real_escape_string($_POST['patient_email']);
        $date = $conn->real_escape_string($_POST['appointment_date']);
        $time = $conn->real_escape_string($_POST['appointment_time']);

        $sql = "INSERT INTO appointments (patient_name, patient_email, appointment_date, appointment_time) 
                VALUES ('$name', '$email', '$date', '$time')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true]); // JSON response
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Please fill in all fields!"]);
    }
}

$conn->close();
?>
