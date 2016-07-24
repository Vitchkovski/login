<?php

require_once("../database.php");
require_once("../models/userFunctions.php");
require_once("../models/dataFunctions.php");

session_start();

if (!empty($_POST['deleteUserProduct']) && !empty($_POST['line_id'])) {

    if (isset($_SESSION['thisIsLoggedUser'])) {


        $userId = $_SESSION['userSessionId'];
        $productId = $_POST['product_id'];
        $productLineId = $_POST['line_id'];

        deleteProductFromUserList($userId, $productId, $productLineId);

    } else {
        header("Location: ../login");
    }


}

if (!empty($_POST['cancelEditModeFlag'])) {

    header("Location: ../login");

}


if (!empty($_POST['updateUserProductString']) && !empty($_POST['line_id'])) {

    if (isset($_SESSION['thisIsLoggedUser'])) {


        $userId = $_SESSION['userSessionId'];

        //product picture submitted
        if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

            $pictureNameAfterUpload = $userId . "-" . time() . ".png";
            uploadProductPicture($userId, $pictureNameAfterUpload);

        }

        //Product Line tobe updated
        $productLineId = $_POST['line_id'];

        //Changes submitted
        $productName = $_POST['productName'];
        $productCategoriesString = $_POST['productCategoriesString'];


        //picture can be not submitted. In that case - setting picture name to the initial value
        if (!isset ($pictureNameAfterUpload))
            $pictureNameAfterUpload = $_POST['initialProductPictureName'];


        updateUserProductString($userId, $productLineId, $productName, $pictureNameAfterUpload, $productCategoriesString);

    } else {
        header("Location: ../login");
    }


}


if (!empty($_POST['newUserProductSubmitted'])) {

    if (isset($_SESSION['thisIsLoggedUser'])) {


        $userId = $_SESSION['userSessionId'];
        $productName = $_POST['productName'];


        //product picture submitted
        if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

            $pictureNameAfterUpload = uploadProductPicture($userId);

        }


        $productCategoriesString = $_POST['productCategoriesString'];

        //picture can be not submitted. In that case - setting picture name to NULL
        if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Type is not allowed" || $pictureNameAfterUpload == "File size is too big" || $pictureNameAfterUpload == "Error on load") {

            if ($pictureNameAfterUpload == "Type is not allowed" || $pictureNameAfterUpload == "File size is too big" || $pictureNameAfterUpload == "Error on load")
                $imageIncorrectFlag = true;

            $pictureNameAfterUpload = null;
        }

        addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesString);

    } else {
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
