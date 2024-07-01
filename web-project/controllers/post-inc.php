<?php

function getPostsCategory($conn, $category) {
    $sql = "SELECT * FROM posts WHERE postSubject = :category";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":category", $category);

    oci_execute($stmt);

    $resultData = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $resultData[] = $row;
    }

    return $resultData;
}
function getPostsUID() {
    // Logică pentru a obține postările utilizatorului
    return "Aceasta este funcția getPostsUID()";
}

function getPostsSearch($conn, $category) {
    // Folosim bind variabile pentru a preveni SQL injection
    $sql = "SELECT * FROM posts WHERE postName LIKE '%' || :category || '%'";
    $stmt = oci_parse($conn, $sql);

    // În legătură cu $category, ar trebui să luăm în considerare scăparea specială pentru a evita SQL Injection
    oci_bind_by_name($stmt, ":category", $category);

    // Executăm interogarea pregătită
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        throw new Exception("Database error: " . $error['message']);
    }

    $resultData = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $resultData[] = $row;
    }

    // Funcția pentru a căuta posturile în funcție de subiect
function searchPostsBySubject($conn, $subject) {
    // Escapăm și curățăm inputul pentru a preveni SQL injection
    $subject = mysqli_real_escape_string($conn, $subject);

    // Interogare SQL pentru a căuta posturile cu subiectul specificat
    $sql = "SELECT * FROM posts WHERE postSubject LIKE '%$subject%'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        // Gestionăm eroarea în caz de eșec la interogare
        return [];
    }

    $posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }

    return $posts;
}

    return $resultData;
}

?>
