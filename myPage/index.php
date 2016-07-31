<?php

require_once("../database.php");
require_once("../models/userFunctions.php");
require_once("../models/dataFunctions.php");

session_start();


if (isset($_POST['addSaveCategory'])) {

    $categoryCounter = $_POST['categoryCounter'];

    $productName = $_POST['productName'];

    $productCategoriesArray = $_POST['productCategoriesArray'];


    $_GET['action'] = "add";
}

if (isset($_POST['addEditCategory'])) {

    $categoryCounter = $_POST['categoryCounter'];

    $productId = $_POST['product_id'];
    if (!empty($_POST['productCategoriesArray']))
        $productCategoriesArray = $_POST['productCategoriesArray'];
    else
        $productCategoriesArray = null;

    $_GET['action'] = "edit";
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


if (isset($_POST['updateProduct'])) {

    if (isset($_SESSION['thisIsLoggedUser'])) {

        $userId = $_SESSION['userSessionId'];

        //product picture submitted
        if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

            $pictureNameAfterUpload = uploadProductPicture($userId);

        }

        //Product Line to be updated
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


if (isset($_POST['saveProduct'])) {


    if (isset($_SESSION['thisIsLoggedUser'])) {

        $userId = $_SESSION['userSessionId'];
        $productName = $_POST['productName'];

        if ($productName != null || $productName != "") {

            //product picture submitted
            if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

                $pictureNameAfterUpload = uploadProductPicture($userId);

            }

            $productCategoriesArray = $_POST['productCategoriesArray'];


            //picture can be not submitted. In that case - setting picture name to NULL
            if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Error on load") {

                if (isset($pictureNameAfterUpload) && $pictureNameAfterUpload == "Error on load")
                    $imageIncorrectFlag = true;

                $pictureNameAfterUpload = null;

            }

            addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesArray);

            //incorrect image flag should be saved in session before refreshing
            $_SESSION['imageIncorrectFlag'] = false;
            if (isset($imageIncorrectFlag)) {
                $_SESSION['imageIncorrectFlag'] = $imageIncorrectFlag;
            }

            //include("../views/userPersonalPage.php");
            header("Location: ../login");
        } else {
            $_GET['action'] = "add";
            $incorrectProductNameFlag = true;
        }


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


    if (isset($_GET['action'])) {
        if ($_GET['action'] == "add") {


            //defining variables
            if (!isset($categoryCounter))
                $categoryCounter = 1;
            if (!isset($productName))
                $productName = "";
            if (!isset($productCategoriesArray[$categoryCounter]))
                $productCategoriesArray[$categoryCounter - 1] = null;

            include("../views/addProduct.php");

        }
        if ($_GET['action'] == "edit") {
            if (!isset($productId))
                $productId = $_GET['product_id'];
            if (!isset($categoryCounter))
                $categoryCounter = 1;
            if (!isset($productCategoriesArray[$categoryCounter]))
                $productCategoriesArray[$categoryCounter - 1] = null;

            $productInfo = retrieveProductInfo($userId, $productId);


            include("../views/editProduct.php");

        }
    } else
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
