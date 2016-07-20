<?php

require_once("../database.php");
require_once("../models/userFunctions.php");
require_once("../models/dataFunctions.php");

session_start();



if (!empty($_POST['deleteUserProduct']) && !empty($_POST['line_id'])) {

    if (isset($_SESSION['thisIsLoggedUser'])){


        $userId = $_SESSION['userSessionId'];
        $productId =  $_POST['product_id'];
        $productLineId =  $_POST['line_id'];

        deleteProductFromUserList($userId, $productId, $productLineId);

    }
    else {
        header("Location: ../login");
    }



}

if (!empty($_POST['cancelEditModeFlag'])) {

    header("Location: ../login");

}



if (!empty($_POST['updateUserProductString']) && !empty($_POST['line_id'])) {

    if (isset($_SESSION['thisIsLoggedUser'])){


        $userId = $_SESSION['userSessionId'];

        //Product Line tobe updated
        $productLineId =  $_POST['line_id'];

        //Changes submitted
        $productName =  $_POST['productName'];
        $productCategoriesString = $_POST['productCategoriesString'];


        updateUserProductString($userId, $productLineId, $productName, $productCategoriesString);

    }
    else {
        header("Location: ../login");
    }



}



if (!empty($_POST['newUserProductSubmitted'])) {

    if (isset($_SESSION['thisIsLoggedUser'])){


        $userId = $_SESSION['userSessionId'];
        $productName =  $_POST['productName'];

        $productCategoriesString = $_POST['productCategoriesString'];


        addProductToUserList($userId, $productName, $productCategoriesString);

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

    $userProducts = retrieveUserProducts($userId);

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
