<?php

function addNewUserToDB ($link, $login, $email, $password)
{
    $escapedEmail = mysqli_real_escape_string( $link, $email);
    $escapedLogin = mysqli_real_escape_string( $link, $login);
    $hashedPassword = hash( 'sha512', $password );
    
    $queryToRun = sprintf("insert into users (login, email, password) values ('%s', '%s', '%s')", $escapedLogin, $escapedEmail, $hashedPassword);
    $sqlResult = mysqli_query($link, $queryToRun);
    
    if (!$sqlResult)
        die (mysqli_error($link));
    
    return true;
    
}

function checkIfUserExist ($link, $login, $email)
//checking if user with provided params is already exist
{
    $escapedLogin = mysqli_real_escape_string( $link, $login);
    $escapedEmail = mysqli_real_escape_string( $link, $email);
    
    $queryToRun = sprintf("select * from users where login = '%s' and email = '%s'", $escapedLogin, $escapedEmail);
    $sqlResult = mysqli_query($link,$queryToRun);
    $numberOfRows = mysqli_num_rows($sqlResult);
    
    if ($numberOfRows == 0)
        return false;
    
    return true;
    
}

function retriveUserInfo ($link, $login, $email, $password)
{
    $escapedEmail = mysqli_real_escape_string( $link, $email);
    $escapedLogin = mysqli_real_escape_string( $link, $login);
    $hashedPassword = hash( 'sha512', $password );
    
    $queryToRun = sprintf("select * from users where login = '%s' and email = '%s' and password = '%s'", $escapedLogin, $escapedEmail, $hashedPassword);
    $sqlResult = mysqli_query($link,$queryToRun);

    $userInfo = mysqli_fetch_assoc($sqlResult);
    
    return $userInfo;
}

function validateIfPasswordSecure($password){
    if (strlen($password) < 6) 
        return false;
    
    return true;
}
?>
