<?php
class AppointmentModel {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function addAppointment($userEmail, $service, $time, $date, $message) {
        $query = "INSERT INTO appointment_table (user_email, service, time, date, message) VALUES (:user_email, :service, :time, :date, :message)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_email", $userEmail);
        $stmt->bindParam(":service", $service);
        $stmt->bindParam(":time", $time);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":message", $message);
        $stmt->execute();
    }

    public function appointmentExists($userEmail, $dateTime) {
        $query = "SELECT * FROM appointment_table WHERE user_email = :user_email AND time = :time AND date = :date";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_email", $userEmail);
        $stmt->bindParam(":time", $time);
        $stmt->bindParam(":date", $date);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Check if the time and within 30 minutes doesn't have 3 other appointments
    public function isTimeSlotAvailable($date, $time) {
        $startDateTime = date('Y-m-d H:i:s', strtotime("$date $time"));
        $endDateTime = date('Y-m-d H:i:s', strtotime("$startDateTime +30 minutes"));

        $query = "SELECT COUNT(*) as count FROM appointment_table WHERE datetime BETWEEN :start_datetime AND :end_datetime";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":start_datetime", $startDateTime);
        $stmt->bindParam(":end_datetime", $endDateTime);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result["count"] < 3;
    }



    // You can add more methods here for appointment-related operations
}
?>
