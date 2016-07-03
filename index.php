<?php

require_once("database.php");
require_once("models/user-functions.php");



$link = db_connect();




    if (!empty($_POST)){
        
        if (user_check ($link, $_POST['login'], $_POST['email']) < 1){
        //User does not exist. Creating user...
        
            
        if (password_check($_POST['pass']) == true) //pass complicated enough
        {            
        user_new($link, $_POST['login'], $_POST['email'], $_POST['pass']);
        
        //immideatly after login - retrieving id
        $user = user_login($link, $_POST['login'], $_POST['email'], $_POST['pass']);
        
        (int)$id = $user['id'];
            $email = $user['email'];
            $login = $user['login'];
            $logged_user_flag = 0; //This is a new user
        
        include("views/user-login.php");
        }
            else 
              $incorrect_pass = 1;
            $id = 0;
            $email = $_POST['email'];
            $login = $_POST['login'];
             include("views/user-login.php");
        }else {
            //User exist. Attempting to login...
            $user = user_login($link, $_POST['login'], $_POST['email'], $_POST['pass']);
            if (isset($user['id'])){
                (int)$id = $user['id'];
                $email = $user['email'];
                $login = $user['login'];
                $logged_user_flag = 1;
                include("views/user-login.php");
                //Login Succesfull
            } else {
                //password is incorrect - setting id to 0 (unexisting user)
            $id = 0;
            $email = $_POST['email'];
            $login = $_POST['login'];
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