<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductsModel extends CI_Model
{
    function deleteProductFromUserList($userId, $productId)
    {
        $this->load->database();

        $this->db->delete('user_products', array('user_id' => $userId,
            'product_id' => $productId));

        $this->db->close();

    }


    function addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesArray)
    {
        $this->load->database();


        //mysql treating null php values as "" value and not NULL. Thus defining correct value before insert

        $sql = "insert into user_products (user_id, product_name, product_img_name) 
                               values (" . $userId . ", 
                               " . $this->db->escape(ltrim(rtrim($productName))) . ", 
                               if ('" . $pictureNameAfterUpload . "' = 'null', null, '" . $pictureNameAfterUpload . "'))";

        $this->db->query($sql);

        $lastCreatedProductID = $this->db->insert_id();


        //if non-existing category was submitted we must create a record for it in corresponding table
        foreach ($productCategoriesArray as $pCA) {



            if ($pCA != "") {
                $query = $this->db->get_where('user_categories', array('user_id' => $userId,
                    'category_name' => $pCA));


                $userCategoryInfo = $query->result_object();


                if (empty($userCategoryInfo)) {
                //creating category
                    $data = array(
                        'user_id' => $userId ,
                        'category_name' => $pCA

                    );
                    $this->db->set('from_date', 'NOW()', FALSE);

                    $this->db->insert('user_categories', $data);

                    //retrieving category info for a future use
                    $query = $this->db->get_where('user_categories', array('user_id' => $userId,
                        'category_name' => $pCA));
                    $userCategoryInfo = $query->result_object();

                }

                $this->db->delete('product_x_categories', array('product_id' => $lastCreatedProductID,
                    'category_id' => $userCategoryInfo[0]->category_id));


                $data = array(
                    'product_id' => $lastCreatedProductID ,
                    'category_id' => $userCategoryInfo[0]->category_id
                );

                $this->db->insert('product_x_categories', $data);

            }
        }


        $this->db->close();


    }

//updating user products list string
    function updateUserProductString($userId, $productId, $productName, $pictureNameAfterUpload, $productCategoriesArray)
    {

        $this->load->database();

        $this->db->delete('product_x_categories', array('product_id' => $productId));

        //if non-existing category was submitted we must create a record for it in corresponding table
        foreach ($productCategoriesArray as $pCA) {
            //$pCA = $this->db->escape_str(ltrim(rtrim($pCA)));

            if ($pCA != "") {
                $query = $this->db->get_where('user_categories', array('user_id' => $userId,
                    'category_name' => $pCA));



                $userCategoryInfo = $query->result_object();


                if (empty($userCategoryInfo)) {

                    $data = array(
                        'user_id' => $userId ,
                        'category_name' => $pCA

                    );
                    $this->db->set('from_date', 'NOW()', FALSE);

                    $this->db->insert('user_categories', $data);

                }


            }
        }


        //updating existing record in user's product list
        $sql = "update user_products 
                           set product_name = " . $this->db->escape(ltrim(rtrim($productName))) . ",
							   product_img_name = (if ('" . $pictureNameAfterUpload . "' = 'null', null, '" . $pictureNameAfterUpload . "'))
						   where product_id = " . $productId . "";


        $this->db->query($sql);


        //link between user_products and user_categories must be created - only if new category was submitted
        foreach ($productCategoriesArray as $pCA) {

            if ($pCA != "") {
                //delete product categories no longer in use

                //retrieving category info for a future use
                $query = $this->db->get_where('user_categories', array('user_id' => $userId,
                    'category_name' => $pCA));
                $userCategoryInfo = $query->result_object();

                $this->db->delete('product_x_categories', array('product_id' => $productId,
                    'category_id' => $userCategoryInfo[0]->category_id));

                $data = array(
                    'product_id' => $productId ,
                    'category_id' => $userCategoryInfo[0]->category_id
                );

                $this->db->insert('product_x_categories', $data);

            }

        }
        $this->db->close();

    }

    function retrieveUserProducts($userId)
    {
        $this->load->database();

        $this->db->select('*');
        $this->db->from('user_products');
        $this->db->where('user_id =', $userId);
        $this->db->order_by('product_id', "desc");

        $query = $this->db->get();

        $this->db->close();

        $userProducts = $query->result_object();


        return $userProducts;
    }

    function retrieveProductCategories($productId)
    {
        $this->load->database();

        $this->db->select('pc.product_id, pc.category_id, uc.category_name, pc.sort_id')
            ->from('product_x_categories AS pc, user_categories AS uc')
            ->where('pc.category_id = uc.category_id')
            ->where('pc.product_id', $productId)
            ->order_by('pc.sort_id');

        $query = $this->db->get();

        $this->db->close();

        $productCategories = $query->result_object();

        return $productCategories;
    }

    function retrieveProductInfo($userId, $productId)
    {
        $this->load->database();

        $this->db->select('*');
        $this->db->from('user_products');
        $this->db->where('user_id =', $userId);
        $this->db->where('product_id =', $productId);
        $this->db->order_by('product_id', "desc");

        $query = $this->db->get();

        $this->db->close();

        $productInfo = $query->result_object();

        return $productInfo;
    }

}