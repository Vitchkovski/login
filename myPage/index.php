<?php

require_once("../database.php");
require_once("../models/userFunctions.php");
require_once("../models/dataFunctions.php");

session_start();


if (isset($_GET['action'])) {

    if ($_GET['action'] == "edit") {

        $editUserProductFlag = true;
        $productId = $_GET['product_id'];
    }


}

if (!empty($_POST['addCategoryFlag'])) {
    $editUserProductFlag = true;
    $productId = $_POST['product_id'];
}


if (!empty($_POST['deleteUserProduct']) && !empty($_POST['product_id'])) {

    if (isset($_SESSION['thisIsLoggedUser'])) {

        $userId = $_SESSION['userSessionId'];
        $productId = $_POST['product_id'];


        deleteProductFromUserList($userId, $productId);

        if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) {
            $_SESSION['imageIncorrectFlag'] = false;
        }

        header("Location: ../login");


    } else {
        header("Location: ../login");
    }


}

if (!empty($_POST['cancelEditModeFlag']) /*|| isset($_GET['action'])*/) {

    if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) {
        $_SESSION['imageIncorrectFlag'] = false;
    }

    header("Location: ../login");

}


if (!empty($_POST['updateUserProductString']) && !empty($_POST['product_id'])) {

    if (isset($_SESSION['thisIsLoggedUser'])) {

        $userId = $_SESSION['userSessionId'];

        //product picture submitted
        if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

            $pictureNameAfterUpload = uploadProductPicture($userId);

        }

        //Product Line tobe updated
        $productId = $_POST['product_id'];

        //Changes submitted
        $productName = $_POST['productName'];
        $productCategoriesArray = $_POST['productCategoriesArray'];

        //picture can be not submitted. In that case - setting picture name to the initial value
        if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Error on load") {

            if (isset($pictureNameAfterUpload) && $pictureNameAfterUpload == "Error on load")
                $imageIncorrectFlag = true;

            $pictureNameAfterUpload = $_POST['initialProductPictureName'];

            //empty value after submit to null for processing
            if ($pictureNameAfterUpload == "")
                $pictureNameAfterUpload = null;

        }

        var_dump($productCategoriesArray);
        echo "<- category";
        updateUserProductString($userId, $productId, $productName, $pictureNameAfterUpload, $productCategoriesArray);

        //incorrect image flag should be saved in session before refreshing
        $_SESSION['imageIncorrectFlag'] = false;
        if (isset($imageIncorrectFlag)) {
            $_SESSION['imageIncorrectFlag'] = $imageIncorrectFlag;
        }

        //header("Location: ../login");

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
        if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Error on load") {

            if (isset($pictureNameAfterUpload) && $pictureNameAfterUpload == "Error on load")
                $imageIncorrectFlag = true;

            $pictureNameAfterUpload = null;

        }

        addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesString);

        //incorrect image flag should be saved in session before refreshing
        $_SESSION['imageIncorrectFlag'] = false;
        if (isset($imageIncorrectFlag)) {
            $_SESSION['imageIncorrectFlag'] = $imageIncorrectFlag;
        }

        //include("../views/userPersonalPage.php");
        header("Location: ../login");

    } else {
        header("Location: ../login");
    }


}


//Redirecting authorized user to the personal info page
if (isset($_SESSION['thisIsLoggedUser'])) {

    $userId = $_SESSION['userSessionId'];
    $userEmail = $_SESSION['userSessionEmail'];
    $userName = $_SESSION['userSessionName'];


    //echo "User ID: ".$userId."<br>";
    $userProducts = retrieveUserProducts($userId);

    /*    echo "userProducts: ";
        var_dump($userProducts);
        echo "<br><br>";*/

    foreach ($userProducts as $uP):
        $productCategories[$uP->product_id] = retrieveProductCategories($uP->product_id);
    endforeach;

    /*   echo "productCategories: ";
       var_dump($productCategories);
       echo "<br><br>";*/


    if (isset($_GET['action']) && $_GET['action'] == "add") {

        include("../views/addProduct.php");

    }else
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
