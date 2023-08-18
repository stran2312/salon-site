<?php

require_once 'model/UserModel.php';
require_once 'model/AppointmentModel.php';

class AppointmentController {
    private $userModel;
    private $appointmentModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->appointmentModel = new AppointmentModel();
    }

    public function processAppointmentForm($formData) {
        if (isset($formData["name"]) && isset($formData["email"])) {
            // Retrieve form data
            $name = $formData["name"];
            $email = $formData["email"];
            $phone = $formData["phone-number"];
            $service = $formData["option"];
            $time = $formData["appointment-time"];
            $date = $formData["appointment-date"];
            $message = $formData["message"];
    
            // Check if the user already exists in the database - if not exists, add user to the database
            $userData = $userModel->getUserByNameAndEmail($name, $email);
            if (empty($userData)) {
                $userModel->addUser($name, $email, $phone);
            }
    
            // Check for duplicate appointment for the same customer
            $appointmentData = $appointmentModel->appointmentExists($email, $date . ' ' . $time);  
            //if no duplicate 
            if ($appointmentData) { 
                // check if there are no more than 3 appointments in the time slot 
                if($appointmentData->isTimeSlotAvailable($date, $time)){
                    $appointmentData->addAppointment($userEmail, $service, $time, $date, $message);
                } else {
                    echo "<script>alert('We are unable to take more appointments at this time. Please choose another time.');</script>";
                    header("Location: index.php");
                    exit;
                }
            // if appointment already exists
            } else {
                echo "<script>alert('Duplicate appointment for the same customer.');</script>";
                header("Location: index.php");
                exit;
            }
    
        } else {
            // Handle form data validation errors if necessary
            echo "<script>alert('Name and email are not valid. Please try again.');</script>";
            header("Location: index.php");
            exit;
        }
    }

    // Other methods
}



?>



