<?php

function addNewUserToDB ($login, $email, $password)
{
    $db = new DB();
    
    $escapedEmail = $db->escapeString($email);
    $escapedLogin = $db->escapeString($login);
    
    $hashedPassword = hash( 'sha512', $password );
    
    

	//$sql = "DELETE FROM `blogs` WHERE `id` = $data->id";
    $queryToRun = sprintf("insert into users (login, email, password) values ('%s', '%s', '%s')", $escapedLogin, $escapedEmail, $hashedPassword);
    
	$data = $db->qryFire($queryToRun);
    
    
    //$sqlResult = mysqli_query($link, $queryToRun);
    
    //if (!$data)
    //    die (mysqli_error($link));
    
    return true;
    
}

function checkIfUserExist ($login, $email)
//checking if user with provided params is already exist
{
    $db = new DB();
    
    $escapedEmail = $db->escapeString($email);
    $escapedLogin = $db->escapeString($login);
    
    $queryToRun = sprintf("select * from users where login = '%s' and email = '%s'", $escapedLogin, $escapedEmail);
    
    //$userInfo = array();
    $userInfo = $db->qrySelect($queryToRun);
    
    //$numberOfRows = count($data);
    
    //$numberOfRows = mysqli_num_rows($sqlResult);
    
    
    if (!is_null($userInfo['id']))
        return true;
    
    return false;
    
}

function retriveUserInfo ($login, $email, $password)
{
    $db = new DB();
    
    $escapedEmail = $db->escapeString($email);
    $escapedLogin = $db->escapeString($login);
    $hashedPassword = hash( 'sha512', $password );
    
    $queryToRun = sprintf("select * from users where login = '%s' and email = '%s' and password = '%s'", $escapedLogin, $escapedEmail, $hashedPassword);
    
    $userInfo = $db->qrySelect($queryToRun);
    
    return $userInfo;
}

function validateIfPasswordSecure($password){
    if (strlen($password) < 6) 
        return false;
    
    return true;
}
?>
