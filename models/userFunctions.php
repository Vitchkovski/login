<?php

function addNewUserToDB($login, $email, $password)
{
    $db_functions = new DBfunctions();

    $escapedEmail = $db_functions->escapeString($email);
    $escapedLogin = $db_functions->escapeString($login);

    $hashedPassword = hash('sha512', $password);

    $queryToRun = sprintf("insert into users (login, email, password) values ('%s', '%s', '%s')", $escapedLogin, $escapedEmail, $hashedPassword);

    $sqlDataReturn = $db_functions->qryFire($queryToRun);

    return true;

}

function checkIfUserExist($login, $email)
//checking if user with provided params is already exist
{
    $db_functions = new DBfunctions();

    $escapedEmail = $db_functions->escapeString($email);
    $escapedLogin = $db_functions->escapeString($login);

    $queryToRun = sprintf("select * from users where login = '%s' or email = '%s'", $escapedLogin, $escapedEmail);

    $userInfo = $db_functions->qrySelect($queryToRun);

    if (!is_null($userInfo[0]))
        return true;

    return false;

}

function retriveUserInfo($login, $email, $password)
{
    $db_functions = new DBfunctions();

    $escapedEmail = $db_functions->escapeString($email);
    $escapedLogin = $db_functions->escapeString($login);
    $hashedPassword = hash('sha512', $password);

    $queryToRun = sprintf("select * from users where (login = '%s' or email = '%s') and password = '%s'", $escapedLogin, $escapedEmail, $hashedPassword);

    $userInfo = $db_functions->qrySelect($queryToRun);

    return $userInfo;
}

function retriveUserProducts($userId)
{
    $db_functions = new DBfunctions();

    /*  $escapedEmail = $db_functions->escapeString($email);
      $escapedLogin = $db_functions->escapeString($login);
      $hashedPassword = hash('sha512', $password);*/

    $queryToRun = sprintf("select * from user_products where user_id = '%s'", $userId);

    $userProducts = $db_functions->qrySelect($queryToRun);

    return $userProducts;
}

function validateIfPasswordSecure($password)
{
    if (strlen($password) < 6)
        return false;

    return true;
}

?>
