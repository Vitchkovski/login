<?php

require_once("database.php");
require_once("models/user-functions.php");


$link = "";


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

    


    if (!checkIfUserExist ($userEscapedLogin, $userEscapedEmail))
    //User does not exist. Creating user in the database
    {
        if (validateIfPasswordSecure($userEscapedPassword))
        //pass is complicated enough  
        {            
            addNewUserToDB($userEscapedLogin, $userEscapedEmail, $userEscapedPassword);
            
            //immideatly after login - retrieving id
            $userInfo = retriveUserInfo($userEscapedLogin, $userEscapedEmail, $userEscapedPassword);
            (int)$userId = $userInfo['id'];
                        
            $userCreatedFlag = true;
            
            include("views/user-login.php");
        }
        else
        //password is to short. Notifying user.
        {
            $passwordIsToShortFlag = true;
            
            include("views/user-login.php");
            
        }
    }
    else
    //User is already exist. Retrieving id.
    {
        $userInfo = retriveUserInfo($link, $userEscapedLogin, $userEscapedEmail, $userEscapedPassword);
        if (isset($user['id']))
        {
            (int)$userId = $userInfo['id'];
            
            $thisIsLoggedUserFlag = true;
            
            include("views/user-login.php");
            //Login Succesfull
        } 
        else 
        {
            //password is incorrect
            
            $passwordIsIncorrectFlag = true;

            include("views/user-login.php");
            //There is an existing user!
        }
    }
}

else
//Nothing submitted yet - form just opened. Defining variables.
{
    $id = "";
            
    $userEscapedEmail = "";
    $userEscapedLogin = "";
    include("views/user-login.php");
   
}

?>
