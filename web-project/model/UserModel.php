<?php
class UserModel {
  private $conn; // Conexiunea la baza de date

  // Constructorul clasei UserModel care primește conexiunea la baza de date ca argument
  public function __construct($dbConn) {
    $this->conn = $dbConn;
  }

  // Metodă care returnează dacă un utilizator este autentificat sau nu
  public function getUserStatus() {
    if(isset($_SESSION["userID"])) {
        return true;
    } else {
        return false;
    }
  }

  // Metodă care returnează un utilizator în funcție de ID-ul său
  public function getUserById($id) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE usersID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
}
