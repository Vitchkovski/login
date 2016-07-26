<?php

function addNewUserToDB($login, $email, $password)
{
    $db_functions = new DBfunctions();

    $escapedEmail = $db_functions->escapeString($email);
    $escapedLogin = $db_functions->escapeString($login);

    $hashedPassword = hash('sha512', $password);

    $queryToRun = sprintf("insert into users (login, email, password) values ('%s', '%s', '%s')", $escapedLogin,
        $escapedEmail,
        $hashedPassword);


    $db_functions->qryFire($queryToRun);
    $db_functions->closeDbConnection();


    return true;

}


function deleteProductFromUserList($userId, $productId)
{
    $db_functions = new DBfunctions();


    $queryToRun = sprintf("delete from user_products where user_id = '%s' 
                                                                 and product_id = '%s'", $userId,
        $productId);


    $db_functions->qryFire($queryToRun);

    $db_functions->closeDbConnection();


    return true;

}


function addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesString)
{
    $db_functions = new DBfunctions();

    $escapedProductCategoriesString = $db_functions->escapeString(ltrim(rtrim($productCategoriesString)));

    //Multiple delimiters can be used for the categories. Defining them and converting categories string to an array
    $limitedCategoriesArray = categoryStringToArray($escapedProductCategoriesString);

    echo "limitedCategoriesArray <br>";
    var_dump($limitedCategoriesArray);
    echo "<br>";

    $escapedProductName = $db_functions->escapeString(ltrim(rtrim($productName)));

    //mysql treating null php values as "" value and not NULL. Thus defining correct value before insert
    if (is_null($pictureNameAfterUpload))
        $pictureNameAfterUpload = "null";


    $queryToRun = sprintf('insert into user_products (user_id, product_name, product_img_name) 
                               values ("%1$s", "%2$s", if ("%3$s" = "null", null, "%3$s"))', $userId,
        $escapedProductName,
        $pictureNameAfterUpload);

    $db_functions->qryFire($queryToRun);

    $lastCreatedProductID = $db_functions->getLastCreatedID();

    echo "Last ID: " . $lastCreatedProductID;
    echo "Test";

    //if non-existing category was submitted we must create a record for it in corresponding table
    foreach ($limitedCategoriesArray as $lCA) {
        if (!is_null($lCA)) {
            $queryToRun = sprintf("select * from user_categories where user_id = '%s' 
                                                                   and category_name = '%s'", $userId, $lCA);


            $userCategoryInfo = $db_functions->qrySelect($queryToRun);

            /*echo "userCategoryInfo <br>";
            var_dump($userCategoryInfo);
            echo "<br>";*/

            if (is_null($userCategoryInfo[0])) {

                $queryToRun = sprintf("insert into user_categories (user_id, category_name, from_date) 
                               values ('%s', '%s', now())", $userId, $lCA);

                $db_functions->qryFire($queryToRun);
            }
        }
    }


    //link between user_products and user_categories must be created
    foreach ($limitedCategoriesArray as $lCA) {

        $queryToRun = sprintf('insert into product_categories (product_id, category_id) 
                               values ("%1$s", (select category_id from user_categories 
                                                                  where user_id = "%2$s"
                                                                  and category_name = "%3$s"))', $lastCreatedProductID, $userId, $lCA);

        $db_functions->qryFire($queryToRun);

    }


    $db_functions->closeDbConnection();


    return true;
}

//updating user products list string
function updateUserProductString($userId, $productLineId, $productName, $pictureNameAfterUpload, $productCategoriesString)
{

    $db_functions = new DBfunctions();

    $escapedProductCategoriesString = $db_functions->escapeString(ltrim(rtrim($productCategoriesString)));

    //Multiple delimiters can be used for the categories. Defining them and converting categories string to an array
    $limitedCategoriesArray = categoryStringToArray($escapedProductCategoriesString);

    /*echo "limitedCategoriesArray <br>";
    var_dump($limitedCategoriesArray);
    echo "<br>";*/

    $escapedProductName = $db_functions->escapeString(ltrim(rtrim($productName)));


    //if non-existing product was submitted we must create a record for it in corresponding table
    $queryToRun = sprintf("select * from user_products where user_id = '%s' 
                                                         and product_name = '%s'", $userId, $escapedProductName);


    $userProductInfo = $db_functions->qrySelect($queryToRun);

    /*echo "userProductInfo <br>";
    var_dump($userProductInfo);
    echo "<br>";*/

    if (is_null($userProductInfo[0])) {
        $queryToRun = sprintf("insert into user_products (user_id, product_name) 
                               values ('%s', '%s')", $userId, $escapedProductName);

        $db_functions->qryFire($queryToRun);
    }


    //if non-existing category was submitted we must create a record for it in corresponding table
    foreach ($limitedCategoriesArray as $lCA) {
        if (!is_null($lCA)) {
            $queryToRun = sprintf("select * from user_categories where user_id = '%s' 
                                                                   and category_name = '%s'", $userId, $lCA);


            $userCategoryInfo = $db_functions->qrySelect($queryToRun);

            /*echo "userCategoryInfo <br>";
            var_dump($userCategoryInfo);
            echo "<br>";*/

            if (is_null($userCategoryInfo[0])) {

                $queryToRun = sprintf("insert into user_categories (user_id, category_name, from_date) 
                               values ('%s', '%s', now())", $userId, $lCA);

                $db_functions->qryFire($queryToRun);
            }
        }
    }
    //mysql treating null php values as "" value and not NULL. Thus defining correct value before update

    if (is_null($pictureNameAfterUpload)) {
        $pictureNameAfterUpload = "null";
    }


    //updating existing record in user's product list
    $queryToRun = sprintf('update user_product_categories 
                           set product_id = (select product_id from user_products up where up.product_name = "%2$s" 
                                                                                       and up.user_id = "%1$s"), 
							   category1 = (select category_id from user_categories uc where uc.user_id = "%1$s" 
							                                                             and uc.category_name = "%3$s"),
							   category2 = (select category_id from user_categories uc where uc.user_id = "%1$s" 
							                                                             and uc.category_name = "%4$s"),
							   category3 = (select category_id from user_categories uc where uc.user_id = "%1$s" 
							                                                             and uc.category_name = "%5$s"),
							   category4 = (select category_id from user_categories uc where uc.user_id = "%1$s" 
							                                                             and uc.category_name = "%6$s"),
							   category5 = (select category_id from user_categories uc where uc.user_id = "%1$s" 
							                                                             and uc.category_name = "%7$s"),
							   product_img_name = (if ("%9$s" = "null", null, "%9$s"))
						   where line_id = "%8$s"', $userId,
        $escapedProductName,
        $limitedCategoriesArray[0],
        $limitedCategoriesArray[1],
        $limitedCategoriesArray[2],
        $limitedCategoriesArray[3],
        $limitedCategoriesArray[4],
        $productLineId,
        $pictureNameAfterUpload);


    $db_functions->qryFire($queryToRun);
    $db_functions->closeDbConnection();


    return true;


}

//checking if user with provided params is already exist
function checkIfUserExist($login, $email)
{
    $db_functions = new DBfunctions();

    $escapedEmail = $db_functions->escapeString($email);
    $escapedLogin = $db_functions->escapeString($login);

    $queryToRun = sprintf("select * from users where login = '%s' or email = '%s'", $escapedLogin, $escapedEmail);


    $userInfo = $db_functions->qrySelect($queryToRun);
    $db_functions->closeDbConnection();


    if (!is_null($userInfo[0]))
        return true;

    return false;

}

function retrieveUserInfo($login, $email, $password)
{
    $db_functions = new DBfunctions();

    $escapedEmail = $db_functions->escapeString($email);
    $escapedLogin = $db_functions->escapeString($login);
    $hashedPassword = hash('sha512', $password);

    $queryToRun = sprintf("select * from users where (login = '%s' or email = '%s') 
                                                 and password = '%s'", $escapedLogin, $escapedEmail, $hashedPassword);


    $userInfo = $db_functions->qrySelect($queryToRun);
    $db_functions->closeDbConnection();

    return $userInfo;
}

function retrieveUserProducts($userId)
{
    $db_functions = new DBfunctions();

    $queryToRun = sprintf("select up.user_id,
                                  up.product_id,
                                  up.product_name, 
                                  up.product_img_name
                                  from user_products up 
                                  where up.user_id = '%s'  
                                  order by up.product_id desc", $userId);


    $userProducts = $db_functions->qrySelect($queryToRun);
    $db_functions->closeDbConnection();

    return $userProducts;
}

function retrieveProductCategories($productId)
{
    $db_functions = new DBfunctions();

    $queryToRun = sprintf("select pc.product_id,
                                  pc.category_id,
                                  uc.category_name
                           from product_categories pc,
                                user_categories uc                                
                           where pc.product_id = '%s'
                             and pc.category_id = uc.category_id
                           order by pc.category_id", $productId);


    $productCategories = $db_functions->qrySelect($queryToRun);
    $db_functions->closeDbConnection();

    return $productCategories;
}

?>
