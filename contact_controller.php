<?php

class ContactController {
    private $db;
    public function __construct() {
        // Constructor if needed
        global $db;
        $this->db = $db;
    }

    public function processContactForm($formData) {
        $name = $formData["name"];
        $email = $formData["email"];
        $message = $formData["message"];

        // You can now use these variables to perform actions such as sending emails, storing in a database, etc.
        // For now, let's just display a confirmation message
        echo "<script>alert('We are unable to take more appointments at this time. Please choose another time.');</script>";
        header("Location: contact.php");
        exit;
    }
}
?>
