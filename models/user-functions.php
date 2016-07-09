<?php

function user_new($link, $login, $email, $pass){
    $escaped_email = mysqli_real_escape_string( $link, $email);
    
    $escaped_login = mysqli_real_escape_string( $link, $login);
    
    $hashed_pass = hash( 'sha512', $pass );
    
        $insert = "insert into users (login, email, password) values ('%s', '%s', '%s')";
    
    $query = sprintf($insert, $escaped_login, $escaped_email, $hashed_pass);
    
    $result = mysqli_query($link, $query);
    
    if (!$result)
        die (mysqli_error($link));
    return true;
    
}

function user_check ($link, $login, $email) //checking if user with provided params is already exist
{
    $escaped_login = mysqli_real_escape_string( $link, $login);
    
    $query = sprintf("select * from users where login = '%s' and email = '%s'", $escaped_login, $email);
    $result = mysqli_query($link,$query);
    
    if (!$result)
        return 0;
    
    $n = mysqli_num_rows($result);
    
    return $n;
}

function user_login ($link, $login, $email, $pass)
{
    $escaped_email = mysqli_real_escape_string( $link, $email);
    
    $escaped_login = mysqli_real_escape_string( $link, $login);
    
    $hashed_pass = hash( 'sha512', $pass );
    
$query = sprintf("select * from users where login = '%s' and email = '%s' and password = '%s'", $escaped_login, $escaped_email, $hashed_pass);
    
    $result = mysqli_query($link,$query);

$user = mysqli_fetch_assoc($result);
    
    return $user;
}

function password_check($pass){
    if (strlen($pass) < 6) return false;
        else
        return true;
}
?>