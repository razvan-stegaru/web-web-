<?php

include_once '../model/dbh-inc.php'; // Asigură-te că includi fișierul de conexiune la baza de date
include_once '../model/PostModel.php'; // Asigură-te că includi fișierul modelului pentru post

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postName']) && isset($_POST['postSubject'])) {
    $userID = $_SESSION['userID'];
    $postName = $_POST['postName'];
    $postSubject = $_POST['postSubject'];

    // Adaugă postul utilizând funcția din model
    $result = addPost($conn, $userID, $postName, $postSubject);

    if ($result === true) {
        // Redirect către pagina de succes sau mesajul de succes
        header("Location: ../accountdetails.php");
        exit();
    } else {
        // Afisează mesajul de eroare
        echo "Error: " . $result;
    }
}
?>
