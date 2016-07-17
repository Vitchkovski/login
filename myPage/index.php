<?php

require_once("../database.php");
require_once("../models/userFunctions.php");

session_start();



if (!empty($_POST['delete_flag']) && !empty($_POST['product_id'])) {
    $deleted = 2;
    if (isset($_SESSION['thisIsLoggedUser'])){
        $deleted = 3;

        $userId = $_SESSION['userSessionId'];
        $productId =  $_POST['product_id'];

        deleteProductFromUserList($userId, $productId);
        $deleted = 1;



    }
    else {
        header("Location: ../login");
    }



}





//Redirecting authorized user to the personal info page
if (isset($_SESSION['thisIsLoggedUser'])) {
    $userId = $_SESSION['userSessionId'];
    $userEmail = $_SESSION['userSessionEmail'];
    $userName = $_SESSION['userSessionName'];

    if (isset($deleted)){
        echo $deleted;
    }

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
