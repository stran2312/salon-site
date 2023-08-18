<?php
class UserModel {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }
    // Add user to the database
    public function addUser($name, $email, $phone) {
        $query = "INSERT INTO user_table (name, email, phone) VALUES (:name, :email, :phone)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->execute();
    }

    // Get User from name and email
    public function getUserByNameAndEmail($name, $email) {
        $query = "SELECT * FROM user_table WHERE name = :name AND email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // Return user data as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // You can add more methods here for user-related operations
}
?>
