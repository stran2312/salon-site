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


        // Process the form data, save to database, or perform any other required actions
        // For example, you can send an email notification, store the data in a database, etc.

        // After processing, you can redirect the user back to index.php or another page
        // For example:
        // header("Location: index.php");
        // exit;
    } else {
        // Handle form data validation errors if necessary
    }
}
?>
