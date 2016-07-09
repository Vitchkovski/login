<?php

require_once("database.php");
require_once("models/user-functions.php");



$link = db_connect();


if (!empty($_POST))
//Something has been submitted through the form
{

//escaping special & space characters first for all input
if (!empty($_POST['login']))
{
$userEscapedSubmittedLogin = htmlspecialchars(ltrim(rtrim($_POST['login'])));
}
if (!empty($_POST['email']))
{
$userEscapedSubmittedEmail = htmlspecialchars(ltrim(rtrim($_POST['email'])));
}
if (!empty($_POST['pass']))
{
$userEscapedSubmittedPassword = htmlspecialchars($_POST['pass']);
}

    


    if (user_check ($link, $userEscapedSubmittedLogin, $userEscapedSubmittedEmail) < 1)
    //User does not exist. Creating user in the database
    {
        if (password_check($userEscapedSubmittedPassword) == true)
        //pass is complicated enough  
        {            
            user_new($link, $userEscapedSubmittedLogin, $userEscapedSubmittedEmail, $userEscapedSubmittedPassword);
            //immideatly after login - retrieving id
            $user = user_login($link,  $userEscapedSubmittedLogin, $userEscapedSubmittedEmail, $userEscapedSubmittedPassword);
        
            (int)$id = $user['id'];
            $email = $user['email'];
            $login = $user['login'];
            
            $logged_user_flag = 0; 
        
            include("views/user-login.php");
        }
        else
        //password is to short. Notifying user.
        {
            $incorrect_pass = 1;
            $id = 0;
            $email = $userEscapedSubmittedEmail;
            $login = $userEscapedSubmittedLogin;
            include("views/user-login.php");
        }
    }
    else 
    //User is already exist. Retrieving id.
    {
        $user = user_login($link, $userEscapedSubmittedLogin, $userEscapedSubmittedEmail, $_POST['pass']);
        if (isset($user['id']))
        {
            (int)$id = $user['id'];
            $email = $user['email'];
            $login = $user['login'];
            $logged_user_flag = 1;
            include("views/user-login.php");
            //Login Succesfull
        } 
        else 
        {
            //password is incorrect - setting id to 0 (unexisting user)
            $id = 0;
            $email = $userEscapedSubmittedEmail;
            $login = $userEscapedSubmittedLogin;
            include("views/user-login.php");
            //There is an existing user!
        }
    }
}

else
{
    //default state. Defining variables.
    $id = "";
    $email = "";
    $login = "";
    include("views/user-login.php");
   
}

?>
