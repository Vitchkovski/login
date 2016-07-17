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


function deleteProductFromUserList($userId, $productId, $fromDate)
{
    $db_functions = new DBfunctions();


    $queryToRun = sprintf("delete from user_product_categories where user_id = '%s' and product_id = '%s' and from_date = '%s' limit 1", $userId, $productId, $fromDate);

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

    $queryToRun = sprintf("select p.product_id,
                                  p.product_name, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category1) category_name1, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category2) category_name2, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category3) category_name3, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category4) category_name4, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category5) category_name5, 
                                  upc.from_date 
                                  from user_product_categories upc, 
                                       products p 
                                  where upc.user_id = '%s'  
                                        and p.product_id = upc.product_id", $userId);

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
