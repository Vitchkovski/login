<?php

require_once("database.php");
require_once("models/userFunctions.php");

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
            
            include("views/userRegister.php");
        }
        else
        //password is to short. Notifying user.
        {
            $passwordIsToShortFlag = true;
            include("views/userRegister.php");
            
        }
    }
    else
    //User is already exist. Retrieving id.
    {
        $userIsAlreadyExistFlag = true;
        
        session_start(); 
        $_SESSION['userSessionEmail'] = $userEscapedEmail;
        $_SESSION['userSessionLogin'] = $userEscapedLogin;
        include("views/userRegister.php");
    }
}

else
//Nothing submitted yet - form just opened. Defining variables.
{
    $userEscapedEmail = "";
    $userEscapedLogin = "";
    include("views/userRegister.php");
   
}

?>
