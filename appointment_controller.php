<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form has been submitted
    if (isset($_POST["name"]) && isset($_POST["email"])) {
        // Retrieve form data
        $name = $_POST["name"];
        $email = $_POST["email"];
        $phone = $_POST["phone-number"];
        $service = $_POST["option"];
        $time = $_POST["appointment-time"];
        $date = $_POST["appointment-date"];
        $message = $_POST["message"];
    }
}
// Assuming you have a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salon";

// Create a new PDO instance for database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form has been submitted
    if (isset($_POST["name"]) && isset($_POST["email"])) {
        // Retrieve form data
        $name = $_POST["name"];
        $email = $_POST["email"];
        $phone = $_POST["phone-number"];
        $service = $_POST["option"];
        $time = $_POST["appointment-time"];
        $date = $_POST["appointment-date"];
        $message = $_POST["message"];

        // Check if the user already exists in the database
        $userExists = false;
        $checkUserQuery = "SELECT * FROM user_table WHERE email = :email";
        $checkUserStmt = $conn->prepare($checkUserQuery);
        $checkUserStmt->bindParam(":email", $email);
        $checkUserStmt->execute();
        if ($checkUserStmt->rowCount() > 0) {
            $userExists = true;
        }

        if (!$userExists) {
            // Add user to the user table
            $addUserQuery = "INSERT INTO user_table (name, email, phone) VALUES (:name, :email, :phone)";
            $addUserStmt = $conn->prepare($addUserQuery);
            $addUserStmt->bindParam(":name", $name);
            $addUserStmt->bindParam(":email", $email);
            $addUserStmt->bindParam(":phone", $phone);
            $addUserStmt->execute();
        }

        // Add appointment to the appointment table
        $addAppointmentQuery = "INSERT INTO appointment_table (user_email, service, time, date, message) VALUES (:user_email, :service, :time, :date, :message)";
        $addAppointmentStmt = $conn->prepare($addAppointmentQuery);
        $addAppointmentStmt->bindParam(":user_email", $email);
        $addAppointmentStmt->bindParam(":service", $service);
        $addAppointmentStmt->bindParam(":time", $time);
        $addAppointmentStmt->bindParam(":date", $date);
        $addAppointmentStmt->bindParam(":message", $message);
        $addAppointmentStmt->execute();

        // Redirect the user back to index.php or another page after processing
        header("Location: index.php");
        exit;
    } else {
        // Handle form data validation errors if necessary
    }
}
?>

