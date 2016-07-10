<?php

require_once("../database.php");
require_once("../models/user-functions.php");

if (!empty($_GET['login']))
{
$userEscapedLogin = htmlspecialchars(ltrim(rtrim($_GET['login'])));
}
if (!empty($_GET['email']))
{
$userEscapedEmail = htmlspecialchars(ltrim(rtrim($_GET['email'])));
}

//$link = "";


if (!empty($_POST))
//Something has been submitted through the form
{

//escaping special & space characters first for all input
if (!empty($_POST['login']))
{
$userEscapedLogin = htmlspecialchars(ltrim(rtrim($_POST['login'])));
}
if (!empty($_POST['email']))
{
$userEscapedEmail = htmlspecialchars(ltrim(rtrim($_POST['email'])));
}
if (!empty($_POST['password']))
{
$userEscapedPassword = htmlspecialchars($_POST['password']);
}

    


        $userInfo = retriveUserInfo($userEscapedLogin, $userEscapedEmail, $userEscapedPassword);
        if (isset($userInfo['id']))
        {
            (int)$userId = $userInfo['id'];
            $userEmail = $userInfo['email'];
            $userName = $userInfo['login'];
            
            //$thisIsLoggedUserFlag = true;
            
            include("../views/userPersonalInfo.php");
            //Login Succesfull
        } 
        else 
        {
            //credentials are incorrect
            
            $credentialsAreIncorrectFlag = true;

            include("../views/userLogin.php");
            //There is an existing user!
        }
    

}

else
//Nothing submitted yet - form just opened. Defining variables.
{
    //$id = "";
    if (!isset($userEscapedEmail)) 
    {
    $userEscapedEmail = "";
    }
    if (!isset($userEscapedLogin)) 
    {
    $userEscapedLogin = "";
    }
   
    include("../views/userLogin.php");
   
}

?>
