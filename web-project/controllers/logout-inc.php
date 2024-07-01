<?php
//delogare in cel mai simplu mod
session_start();
session_unset();
session_destroy();
header("location: ../index.php"); 
exit();

//get,post,delete,update cel putin pt un ,restapi(create,read,uptade,delete,crud)macar pt 2 modele,pt useri si postari