<?php

require_once("../database.php");
require_once("../models/userFunctions.php");

//destroying session in case of log out
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == "logout")
        session_start();
    session_unset();
    session_destroy();
}

//Something has been submitted through the form
if (!empty($_POST)) {
    //escaping special & space characters first for all input
    if (!empty($_POST['email'])) {
        $userEscapedEmail = htmlspecialchars(ltrim(rtrim($_POST['email'])));
    }

    if (!empty($_POST['password'])) {
        $userEscapedPassword = htmlspecialchars($_POST['password']);
    }

    $userEscapedLogin = "";

    $userInfo = retriveUserInfo($userEscapedLogin, $userEscapedEmail, $userEscapedPassword);

    //credentials are correct
    if (isset($userInfo['id'])) {

        (int)$userId = $userInfo['id'];
        $userEmail = $userInfo['email'];
        $userName = $userInfo['login'];

        session_start();
        $_SESSION['thisIsLoggedUser'] = true;

        $_SESSION['userSessionId'] = $userId;
        $_SESSION['userSessionEmail'] = $userEmail;
        $_SESSION['userSessionName'] = $userName;

        include("../views/userPersonalInfo.php");
        //Login Succesfull
    } else {
        //credentials are incorrect. Notifying user

        $credentialsAreIncorrectFlag = true;

        include("../views/userLogin.php");
    }


} else {
    //Nothing submitted yet - form just opened. Defining variables.
    session_start();

    //Migrating credentials from registration form
    if (isset($_SESSION['userSessionLogin'])) {
        $userEscapedLogin = $_SESSION['userSessionLogin'];
    }
    if (isset($_SESSION['userSessionEmail'])) {
        $userEscapedEmail = $_SESSION['userSessionEmail'];
    }

    //Redirecting authorized user to the personal info page
    if (isset($_SESSION['thisIsLoggedUser'])) {
        $userId = $_SESSION['userSessionId'];
        $userEmail = $_SESSION['userSessionEmail'];
        $userName = $_SESSION['userSessionName'];
        include("../views/userPersonalInfo.php");
    } else {
        //Session is not started for the user - opening login page
        if (!isset($userEscapedEmail)) {
            $userEscapedEmail = "";
        }
        if (!isset($userEscapedLogin)) {
            $userEscapedLogin = "";
        }
        include("../views/userLogin.php");
    }

}

?>
