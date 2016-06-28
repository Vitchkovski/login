<?php

require_once("database.php");
require_once("models/user-functions.php");

//echo "index php";

$link = db_connect();
//$articles = articles_all($link);

//if (isset($_GET['action']))
//    $action = $_GET['action'];
//else
 //   $action="";

//if ($action == "add"){
//    (int)$id = $_GET['id'];
    
//}

    if (!empty($_POST)){
        //echo user_check ($link, $_POST['login'], $_POST['email']);
        if (user_check ($link, $_POST['login'], $_POST['email']) < 1){
        //User does not exist. Creating user...
        user_new($link, $_POST['login'], $_POST['email'], $_POST['pass']);
        //header("Location: index.php");
        $user = user_login($link, $_POST['login'], $_POST['email'], $_POST['pass']);
        //include("views/user-login.php");
        (int)$id = $user['id'];
            $email = $user['email'];
            $login = $user['login'];
            $logged_user_flag = 0; //This is a new user
        //header("Location: index.php?id=$id");
        include("views/user-login.php");
        //echo $id;
        }else {
            //User exist. Attempting to login...
            $user = user_login($link, $_POST['login'], $_POST['email'], $_POST['pass']);
            if (isset($user['id'])){
                (int)$id = $user['id'];
                $email = $user['email'];
                $login = $user['login'];
                $logged_user_flag = 1;
                include("views/user-login.php");
                //echo "Login Succesfull";
            } else {
            $id = 0;
            $email = $_POST['email'];
            $login = $_POST['login'];
            include("views/user-login.php");
            //echo "There is an existing user!";
              }
            }
        }

else
{
    $id = "";
     $email = "";
    $login = "";
    include("views/user-login.php");
    //echo "here";
}

?>