<?php
session_start();
require_once('model/UserModel.php');
require_once('view/IndexView.php');
require_once('model/dbh-inc.php');

class IndexController {
  private $userModel; // Obiectul model de utilizator

  // Constructorul clasei IndexController inițializează UserModel
  public function __construct() {
    global $conn;
    $this->userModel = new UserModel($conn);
  }

  // Metoda principală care randează view-ul
  public function index() {
    $showUserIcon = $this->userModel->getUserStatus(); // Obține starea utilizatorului
    $indexView = new IndexView(); // Crează un nou view
    $indexView->render($showUserIcon); // Trimite datele la view pentru a fi randat
  }
}

//nu mergea afisata pagina pentru ca nu era apelata metoda index(nu era creata o instanta)
$controller = new IndexController();
$controller->index();

