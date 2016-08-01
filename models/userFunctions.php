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


function addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesArray)
{
    $db_functions = new DBfunctions();

    //$escapedProductCategoriesString = $db_functions->escapeString(ltrim(rtrim($productCategoriesString)));

    //Multiple delimiters can be used for the categories. Defining them and converting categories string to an array
    //$limitedCategoriesArray = categoryStringToArray($escapedProductCategoriesString);

   /* echo "limitedCategoriesArray <br>";
    var_dump($limitedCategoriesArray);
    echo "<br>";*/

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


    //if non-existing category was submitted we must create a record for it in corresponding table
    foreach ($productCategoriesArray as $pCA) {

        $escapedCategoryName = $db_functions->escapeString(ltrim(rtrim($pCA)));
        if (!is_null($escapedCategoryName) && $escapedCategoryName != "") {
            $queryToRun = sprintf("select * from user_categories where user_id = '%s' 
                                                                   and category_name = '%s'", $userId, $escapedCategoryName);


            $userCategoryInfo = $db_functions->qrySelect($queryToRun);

            /*echo "userCategoryInfo <br>";
            var_dump($userCategoryInfo);
            echo "<br>";*/

            if (is_null($userCategoryInfo[0])) {

                $queryToRun = sprintf("insert into user_categories (user_id, category_name, from_date) 
                               values ('%s', '%s', now())", $userId, $escapedCategoryName);

                $db_functions->qryFire($queryToRun);
            }
        }
    }


    //link between user_products and user_categories must be created
    foreach ($productCategoriesArray as $pCA) {
        $escapedCategoryName = $db_functions->escapeString(ltrim(rtrim($pCA)));

        $queryToRun = sprintf('insert into product_categories (product_id, category_id) 
                               values ("%1$s", (select category_id from user_categories 
                                                                  where user_id = "%2$s"
                                                                  and category_name = "%3$s"))', $lastCreatedProductID, $userId, $escapedCategoryName);

        $db_functions->qryFire($queryToRun);

    }


    $db_functions->closeDbConnection();


    return true;
}

//updating user products list string
function updateUserProductString($userId, $productId, $productName, $pictureNameAfterUpload, $productCategoriesArray)
{

    $db_functions = new DBfunctions();

    //$escapedProductCategoriesString = $db_functions->escapeString(ltrim(rtrim($productCategoriesArray)));

    //Multiple delimiters can be used for the categories. Defining them and converting categories string to an array
    //$limitedCategoriesArray = categoryStringToArray($escapedProductCategoriesString);


    /*echo "limitedCategoriesArray <br>";
    var_dump($limitedCategoriesArray);
    echo "<br>";*/

    $escapedProductName = $db_functions->escapeString(ltrim(rtrim($productName)));



    //if non-existing category was submitted we must create a record for it in corresponding table
    foreach ($productCategoriesArray as $pCA) {
        $pCA = $db_functions->escapeString(ltrim(rtrim($pCA)));
        if (!is_null($pCA) && $pCA != "") {
            $escapedCategoryName = $db_functions->escapeString(ltrim(rtrim($pCA)));
            $queryToRun = sprintf("select * from user_categories where user_id = '%s' 
                                                                   and category_name = '%s'", $userId, $escapedCategoryName);


            $userCategoryInfo = $db_functions->qrySelect($queryToRun);

            /*echo "userCategoryInfo <br>";
            var_dump($userCategoryInfo);
            echo "<br>";*/

            if (is_null($userCategoryInfo[0])) {

                $queryToRun = sprintf("insert into user_categories (user_id, category_name, from_date) 
                               values ('%s', '%s', now())", $userId, $escapedCategoryName);

                $db_functions->qryFire($queryToRun);
            }
        }
    }

    //delete product categories no longer in use
    $queryToRun = sprintf('delete from product_categories where product_id = "%1$s" ', $productId);

    $db_functions->qryFire($queryToRun);


    //mysql treating null php values as "" value and not NULL. Thus defining correct value before update

    if (is_null($pictureNameAfterUpload)) {
        $pictureNameAfterUpload = "null";
    }


    //updating existing record in user's product list
    $queryToRun = sprintf('update user_products 
                           set product_name = "%1$s",
							   product_img_name = (if ("%2$s" = "null", null, "%2$s"))
						   where product_id = "%3$s"',
        $escapedProductName,
        $pictureNameAfterUpload,
        $productId);


    $db_functions->qryFire($queryToRun);


    //link between user_products and user_categories must be created - only if new category was submitted
    foreach ($productCategoriesArray as $pCA) {
        $escapedCategoryName = $db_functions->escapeString(ltrim(rtrim($pCA)));

        $pCA = $db_functions->escapeString(ltrim(rtrim($pCA)));
        $queryToRun = sprintf('insert into product_categories (product_id, category_id) 
                               values ("%1$s", (select category_id from user_categories 
                                                                  where user_id = "%2$s"
                                                                  and category_name = "%3$s"))', $productId, $userId, $escapedCategoryName);

        $db_functions->qryFire($queryToRun);

    }


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
                                  uc.category_name,
                                  pc.relationship_id
                           from product_categories pc,
                                user_categories uc                                
                           where pc.product_id = '%s'
                             and pc.category_id = uc.category_id
                           order by pc.relationship_id", $productId);


    $productCategories = $db_functions->qrySelect($queryToRun);
    $db_functions->closeDbConnection();

    return $productCategories;
}

function retrieveProductInfo($userId, $productId){
    $db_functions = new DBfunctions();

    $queryToRun = sprintf("select up.user_id,
                                  up.product_id,
                                  up.product_name, 
                                  up.product_img_name
                                  from user_products up 
                                  where up.user_id = '%s'  
                                  and up.product_id = '%s'
                                  order by up.product_id desc", $userId, $productId);


    $productInfo = $db_functions->qrySelect($queryToRun);
    $db_functions->closeDbConnection();

    return $productInfo;
}

?>
