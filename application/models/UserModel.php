<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model
{

    function addNewUserToDB($login, $email, $password)
    {
        $this->load->database();

        $hashedPassword = hash('sha512', $password);

        $sql = "insert into users (login, email, password) values (" . $this->db->escape(ltrim(rtrim($login))) . "," . $this->db->escape(ltrim(rtrim($email))) . ",'" . $hashedPassword . "')";

        $this->db->query($sql);

        $this->db->close();

    }


    function deleteProductFromUserList($userId, $productId)
    {
        $this->load->database();


        $sql = "delete from user_products where user_id = " . $userId . "
                                            and product_id = " . $productId . "";


        $this->db->query($sql);

        $this->db->close();

    }


    function addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesArray)
    {
        $this->load->database();


        //mysql treating null php values as "" value and not NULL. Thus defining correct value before insert
        if (is_null($pictureNameAfterUpload))
            $pictureNameAfterUpload = "null";


        $sql = "insert into user_products (user_id, product_name, product_img_name) 
                               values (" . $userId . ", 
                               " . $this->db->escape(ltrim(rtrim($productName))) . ", 
                               if ('" . $pictureNameAfterUpload . "' = 'null', null, '" . $pictureNameAfterUpload . "'))";

        $this->db->query($sql);

        $lastCreatedProductID = $this->db->insert_id();


        //if non-existing category was submitted we must create a record for it in corresponding table
        foreach ($productCategoriesArray as $pCA) {

            $pCA = $this->db->escape(ltrim(rtrim($pCA)));


            if ($pCA != "''") {
                $sql = "select * from user_categories where user_id = " . $userId . " 
                                                                   and category_name = " . $pCA . "";

                $query = $this->db->query($sql);

                $userCategoryInfo = $query->result_object();

                /*echo "userCategoryInfo <br>";
                var_dump($userCategoryInfo);
                echo "<br>";*/

                if (empty($userCategoryInfo)) {

                    $sql = "insert into user_categories (user_id, category_name, from_date) 
                               values (" . $userId . ", " . $pCA . ", now())";

                    $this->db->query($sql);


                }

                $sql = "delete from product_x_categories where product_id = " . $lastCreatedProductID . " and category_id = (select category_id from user_categories 
                                                                  where user_id = " . $userId . "
                                                                  and category_name = " . $pCA . ")";

                $this->db->query($sql);

                $sql = "insert into product_x_categories (product_id, category_id) 
                               values (" . $lastCreatedProductID . ", (select category_id from user_categories 
                                                                  where user_id = " . $userId . "
                                                                  and category_name = " . $pCA . "))";

                $this->db->query($sql);


            }
        }


        $this->db->close();


    }

//updating user products list string
    function updateUserProductString($userId, $productId, $productName, $pictureNameAfterUpload, $productCategoriesArray)
    {

        $this->load->database();

        //delete product categories no longer in use
        $sql = "delete from product_x_categories where product_id = " . $productId . "";

        $this->db->query($sql);


        //if non-existing category was submitted we must create a record for it in corresponding table
        foreach ($productCategoriesArray as $pCA) {
            $pCA = $this->db->escape(ltrim(rtrim($pCA)));

            if ($pCA != "''") {

                $sql = "select * from user_categories where user_id = " . $userId . "
                                                                   and category_name =" . $pCA . "";

                $query = $this->db->query($sql);


                $userCategoryInfo = $query->result_object();

                /*echo "userCategoryInfo <br>";
                var_dump($userCategoryInfo);
                echo "<br>";*/

                if (empty($userCategoryInfo)) {

                    $sql = "insert into user_categories (user_id, category_name, from_date) 
                               values (" . $userId . ", " . $pCA . ", now())";

                    $this->db->query($sql);
                }


            }
        }


        //mysql treating null php values as "" value and not NULL. Thus defining correct value before update

        if (is_null($pictureNameAfterUpload)) {
            $pictureNameAfterUpload = "null";
        }


        //updating existing record in user's product list
        $sql = "update user_products 
                           set product_name = " . $this->db->escape(ltrim(rtrim($productName))) . ",
							   product_img_name = (if ('" . $pictureNameAfterUpload . "' = 'null', null, '" . $pictureNameAfterUpload . "'))
						   where product_id = " . $productId . "";


        $this->db->query($sql);


        //link between user_products and user_categories must be created - only if new category was submitted
        foreach ($productCategoriesArray as $pCA) {
            $pCA = $this->db->escape(ltrim(rtrim($pCA)));

            if ($pCA != "''") {

                //delete product categories no longer in use
                $sql = "delete from product_x_categories where product_id = " . $productId . " and category_id = (select category_id from user_categories 
                                                                  where user_id = " . $userId . "
                                                                  and category_name = " . $pCA . ")";

                $this->db->query($sql);

                $sql = "insert into product_x_categories (product_id, category_id) 
                               values (" . $productId . ", (select category_id from user_categories 
                                                                  where user_id = " . $userId . "
                                                                  and category_name = " . $pCA . "))";

                $this->db->query($sql);

            }

        }
        $this->db->close();

    }

//checking if user with provided params is already exist
    function checkIfUserExist($login, $email)
    {
        $this->load->database();

        $sql = "select * from users where login = " . $this->db->escape($login) . " or email = " . $this->db->escape($email) . "";

        $query = $this->db->query($sql);
        $this->db->close();


        $userInfo = $query->result_object();



        if (!empty($userInfo))
            return true;

        return false;

    }

    function retrieveUserInfo($login, $email, $password)
    {

        $this->load->database();

        $hashedPassword = hash('sha512', $password);

        $sql = "select * from users where (login = " . $this->db->escape($login) . " or email = " . $this->db->escape($email) . ") 
                                     and password = '" . $hashedPassword . "'";

        $query = $this->db->query($sql);
        $this->db->close();

        $userInfo = $query->result_object();

        return $userInfo;
    }

    function retrieveUserProducts($userId)
    {
        $this->load->database();

        $sql = "select up.user_id,
                                  up.product_id,
                                  up.product_name, 
                                  up.product_img_name
                                  from user_products up 
                                  where up.user_id = '" . $userId . "' 
                                  order by up.product_id desc";


        $query = $this->db->query($sql);
        $this->db->close();

        $userProducts = $query->result_object();

        return $userProducts;
    }

    function retrieveProductCategories($productId)
    {
        $this->load->database();

        $sql = "select pc.product_id,
                                  pc.category_id,
                                  uc.category_name,
                                  pc.sort_id
                           from product_x_categories pc,
                                user_categories uc                                
                           where pc.product_id = " . $productId . "
                             and pc.category_id = uc.category_id
                           order by pc.sort_id";

        $query = $this->db->query($sql);
        $this->db->close();

        $productCategories = $query->result_object();


        return $productCategories;
    }

    function retrieveProductInfo($userId, $productId)
    {
        $this->load->database();

        $sql = "select up.user_id,
                                  up.product_id,
                                  up.product_name, 
                                  up.product_img_name
                                  from user_products up 
                                  where up.user_id = " . $userId . " 
                                  and up.product_id = '" . $productId . "'
                                  order by up.product_id desc";


        $query = $this->db->query($sql);
        $this->db->close();

        $productInfo = $query->result_object();

        return $productInfo;
    }
}