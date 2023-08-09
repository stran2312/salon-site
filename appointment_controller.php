<?php
// ... Database connection and other code ...
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

        // Check for duplicate appointment for the same customer
        $appointmentExists = false;
        $checkAppointmentQuery = "SELECT * FROM appointment_table WHERE user_email = :user_email AND time = :time AND date = :date";
        $checkAppointmentStmt = $conn->prepare($checkAppointmentQuery);
        $checkAppointmentStmt->bindParam(":user_email", $email);
        $checkAppointmentStmt->bindParam(":time", $time);
        $checkAppointmentStmt->bindParam(":date", $date);
        $checkAppointmentStmt->execute();
        if ($checkAppointmentStmt->rowCount() > 0) {
            $appointmentExists = true;
        }

        // Check if the chosen time has reached the appointment limit
        $maxAppointments = 3; // Change this to your desired limit
        $checkAppointmentCountQuery = "SELECT COUNT(*) AS appointment_count FROM appointment_table WHERE time = :time AND date = :date";
        $checkAppointmentCountStmt = $conn->prepare($checkAppointmentCountQuery);
        $checkAppointmentCountStmt->bindParam(":time", $time);
        $checkAppointmentCountStmt->bindParam(":date", $date);
        $checkAppointmentCountStmt->execute();
        $appointmentCount = $checkAppointmentCountStmt->fetchColumn();
        if ($appointmentCount >= $maxAppointments) {
            // Display a pop-up message for appointment limit
            echo "<script>alert('We are unable to take more appointments at this time. Please choose another time.');</script>";
        } elseif ($userExists && $appointmentExists) {
            // Display a pop-up message for duplicate appointment
            echo "<script>alert('Duplicate appointment for the same customer.');</script>";
        } else {
            // Add user to the user table if not already exists
            if (!$userExists) {
                // Add user 
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
        }
    } else {
        // Handle form data validation errors if necessary
    }
}
?>



