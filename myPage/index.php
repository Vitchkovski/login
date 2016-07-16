<?php

require_once("../database.php");
require_once("../models/userFunctions.php");

session_start();


//Redirecting authorized user to the personal info page
if (isset($_SESSION['thisIsLoggedUser'])) {
    $userId = $_SESSION['userSessionId'];
    $userEmail = $_SESSION['userSessionEmail'];
    $userName = $_SESSION['userSessionName'];

    $userProducts = retriveUserProducts($userId);

    include("../views/userPersonalPage.php");
} else {
    //Session is not started for the user - opening login page
    if (!isset($userEscapedEmail)) {
        $userEscapedEmail = "";
    }
    if (!isset($userEscapedLogin)) {
        $userEscapedLogin = "";
    }
    header("Location: ../login");
}


?>
