<?php

include_once 'dbh-inc.php'; // Asigură-te că includi fișierul de conexiune la baza de date

function addPost($conn, $userID, $postName, $postSubject) {
    $sql = "INSERT INTO posts (usersID, postID, postName, postSubject) 
            VALUES (:userID, posts_seq.NEXTVAL, :postName, :postSubject)";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":userID", $userID);
    oci_bind_by_name($stmt, ":postName", $postName);
    oci_bind_by_name($stmt, ":postSubject", $postSubject);

    if (oci_execute($stmt)) {
        oci_commit($conn); // Commit tranzacția în baza de date
        return true; // Postare adăugată cu succes
    } else {
        $e = oci_error($stmt);
        return "Error: " . $e['message'];
    }

    oci_free_statement($stmt);
}
?>
