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


function addProductToUserList($userId, $productName, $productCategories)
{
    $db_functions = new DBfunctions();

    $escapedProductName = $db_functions->escapeString($productName);
    $escapedProductCategories = $db_functions->escapeString($productCategories);

    //if non-existing product was submitted we must create a record for it in corresponding table
    $queryToRun = sprintf('select * from user_products where user_id = "%s" and product_name = "%s"', $userId, $escapedProductName);
    $userProductInfo = $db_functions->qrySelect($queryToRun);

    if (is_null($userProductInfo[0])) {
        $queryToRun = sprintf('insert into user_products (user_id, product_name) 
                               values ("%s", "%s")', $userId, $escapedProductName);
        $sqlDataReturn = $db_functions->qryFire($queryToRun);
    }


    //if non-existing category was submitted we must create a record for it in corresponding table
    $queryToRun = sprintf('select * from user_categories where user_id = "%s" and category_name = "%s"', $userId, $escapedProductCategories);
    $userCategoryInfo = $db_functions->qrySelect($queryToRun);

    if (is_null($userCategoryInfo[0])) {
        $queryToRun = sprintf('insert into user_categories (user_id, category_name, from_date) 
                               values ("%s", "%s", now())', $userId, $escapedProductCategories);
        $sqlDataReturn = $db_functions->qryFire($queryToRun);
    }

    $queryToRun = sprintf('insert into user_product_categories (user_id, 
									product_id, 
									category1, 
									from_date) 
values ("%1$s", 
	   (select product_id from user_products up where up.product_name = "%2$s" and up.user_id = "%1$s"), 
	   (select category_id from user_categories uc where uc.user_id = "%1$s" and uc.category_name = "%3$s"), 
	   now())', $userId, $escapedProductName, $escapedProductCategories);

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

    $queryToRun = sprintf("select up.product_id,
                                  up.product_name, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category1) category_name1, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category2) category_name2, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category3) category_name3, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category4) category_name4, 
                                  (select category_name from user_categories uc where uc.category_id = upc.category5) category_name5, 
                                  upc.from_date 
                                  from user_product_categories upc, 
                                       user_products up 
                                  where upc.user_id = '%s'  
                                        and up.product_id = upc.product_id
                                        and up.user_id = upc.user_id
                                  order by upc.from_date desc", $userId);

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
