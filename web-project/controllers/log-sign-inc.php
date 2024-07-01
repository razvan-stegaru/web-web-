<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../model/dbh-inc.php';

function emptyInputSignup($firstName, $lastName, $email, $password, $cpassword){
    // Verifică dacă există câmpuri goale în formularul de înregistrare
    $result = false;
    if(empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($cpassword)){
        $result = true;
    }
    return $result;
}

function invalidUid($firstName){
    // Verifică dacă numele de utilizator conține caractere nepermise
    $result = false;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $firstName)){
        $result = true;
    }
    return $result; 
}

function pwdMatch($password, $cpassword){
    // Verifică dacă parolele introduse se potrivesc
    $result = false;
    if ($password !== $cpassword){
        $result = true;
    }
    return $result;     
}

function uidExists($conn, $email){
    // Verifică dacă există deja un utilizator cu adresa de email specificată, de revenit aici
    $sql = "SELECT * FROM users WHERE usersEmail = :email";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":email", $email);
    oci_execute($stmt);
    $row = oci_fetch_array($stmt, OCI_ASSOC);
    oci_free_statement($stmt);
    return $row;
}

function createUser($conn, $firstName, $lastName, $email, $password){
    // Debugging: Verifică dacă funcția este apelată
    echo "Entering createUser function<br>";

    // Hash parola înainte de a o salva în baza de date
    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

    // Creează un nou utilizator în baza de date
    $sql = "INSERT INTO users (usersfirstName, userslastName, usersEmail, usersPwd) VALUES (:firstName, :lastName, :email, :password)";
    $stmt = oci_parse($conn, $sql);
    
    if (!$stmt) {
        $e = oci_error($conn);
        echo "Failed to prepare statement: " . $e['message'];
        return;
    }
    
    oci_bind_by_name($stmt, ":firstName", $firstName);
    oci_bind_by_name($stmt, ":lastName", $lastName);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":password", $hashedPwd);
    
    // Debugging: Verifică dacă binding-ul parametrilor a reușit
    echo "Parameters bound successfully<br>";
    
    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        echo "Failed to execute statement: " . $e['message'];
        return;
    }
    
    // Debugging: Verifică dacă inserarea a reușit
    echo "Statement executed successfully<br>";

    oci_free_statement($stmt);
    oci_commit($conn);
    
    // Debugging: Verifică dacă commit-ul a reușit
    echo "Commit successful<br>";
    
    header("location: ../view/login.php?error=none");
    exit(); 
}

function emptyInputLogin($email, $pwd){
    // Verifică dacă există câmpuri goale în formularul de login
    $result = false;
    if (empty($email) || empty($pwd)){
        $result = true;
    }
    return $result;
}

function loginUser($conn, $email, $pwd){
    // Debugging: Verifică dacă funcția este apelată
    echo "Entering loginUser function<br>";

    // Verifică dacă utilizatorul există și parolele se potrivesc
    $sql = "SELECT * FROM users WHERE usersEmail = :email";
    $stmt = oci_parse($conn, $sql);
    
    if (!$stmt) {
        $e = oci_error($conn);
        echo "Failed to prepare statement: " . $e['message'] . "<br>";
        return;
    }

    oci_bind_by_name($stmt, ":email", $email);

    // Debugging: Verifică dacă binding-ul parametrilor a reușit
    echo "Parameters bound successfully<br>";

    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        echo "Failed to execute statement: " . $e['message'] . "<br>";
        return;
    }

    // Debugging: Verifică dacă executarea a reușit
    echo "Statement executed successfully<br>";

    $row = oci_fetch_array($stmt, OCI_ASSOC);
    oci_free_statement($stmt);

    if ($row) {
        // Debugging: Utilizator găsit
        echo "User found<br>";

        $pwdHashed = $row['USERSPWD'];

        // Debugging: Verifică parola hash
        echo "Checking password<br>";

        if (password_verify($pwd, $pwdHashed)) {
            // Debugging: Parola corectă
            echo "Login successful<br>";

            session_start();
            $_SESSION["userID"] = $row["USERSID"];
            $_SESSION["userEmail"] = $row["USERSEMAIL"];
            header("location: ../index.php");
            exit();
        } else {
            // Debugging: Parola incorectă
            echo "Wrong password<br>";
            header("location: ../view/login.php?error=wrongpassword");
            exit();
        }
    } else {
        // Debugging: Utilizator negăsit
        echo "User not found<br>";
        header("location: ../view/login.php?error=usernotfound");
        exit();
    }
}


if (isset($_POST['signup'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Debugging: Verifică datele primite din formular
    echo "First Name: $firstName<br>";
    echo "Last Name: $lastName<br>";
    echo "Email: $email<br>";

    createUser($conn, $firstName, $lastName, $email, $password);
}
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pwd = $_POST['password'];

    // Debugging: Verifică datele primite din formular
    echo "Email: $email<br>";

    loginUser($conn, $email, $pwd);
}
if (isset($_POST['submit'])){  
    // Verifică dacă a fost trimis formularul de înregistrare

    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);

    require_once '../model/dbh-inc.php';

    if (emptyInputSignup($firstName, $lastName, $email, $password, $cpassword) !== false) {
        header("location: ../view/signin.php?error=forgot.to.complete.something");
        exit();
    }
    if (invalidUid($firstName) !== false) {
        header("location: ../view/signin.php?error=invalidUid");
        exit(); 
    }   
    if (pwdMatch($password, $cpassword) !== false) {
        header("location: ../view/signin.php?error=pwddontmatch");
        exit(); 
    }

    // Verificare dacă utilizatorul există deja
    if (uidExists($conn, $email)) {
        header("location: ../view/signin.php?error=userexists");
        exit(); 
    }

    // Crearea utilizatorului
    createUser($conn, $firstName, $lastName, $email, $password);
}
