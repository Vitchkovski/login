<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyPage extends CI_Controller
{

    public function index()
    {
        session_start();

        if (isset($_SESSION['thisIsLoggedUser'])) {

            $this->load->helper('url');
            $this->load->helper('dataFunctions');
            $this->load->model('userModel');

            $data['userId'] = $_SESSION['userSessionId'];
            //$userEmail = $_SESSION['userSessionEmail'];
            $data['userName'] = $_SESSION['userSessionName'];




            //echo "User ID: ".$userId."<br>";
            $data['userProducts'] = $this->userModel->retrieveUserProducts($data['userId']);


            /*echo "userProducts: ";
            var_dump($data['userProducts']);
            echo "<br><br>";*/
            if (!empty($data['userProducts'])) {
                foreach ($data['userProducts'] as $uP):
                    $data['productCategories'][$uP->product_id] = $this->userModel->retrieveProductCategories($uP->product_id);
                endforeach;

            }

            /*echo "productCategories: ";
            var_dump($data['productCategories']);
            echo "<br><br>";*/


             $this->load->view('userPersonalPage', $data);

        } else {
            //Session is not started for the user - opening login page
            redirect(base_url().'index.php/login');

        }

    }

    public function addProduct()
    {
        session_start();
        $this->load->helper('url');
        $this->load->helper('dataFunctions');
        $this->load->model('userModel');

        if (isset($_POST['addCategory'])) {

            $data['categoryCounter'] = $_POST['categoryCounter'];

            $data['productName'] = $_POST['productName'];

            $data['productCategoriesArray'] = $_POST['productCategoriesArray'];

        }

        if (isset($_POST['saveProduct'])) {
            if (isset($_SESSION['thisIsLoggedUser'])) {
                $data['userId'] = $_SESSION['userSessionId'];
                $productName = $_POST['productName'];

                if ($productName != null || $productName != "") {

                    //product picture submitted
                    if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

                        $pictureNameAfterUpload = uploadProductPicture($data['userId']);

                    }

                    $productCategoriesArray = $_POST['productCategoriesArray'];


                    //picture can be not submitted. In that case - setting picture name to NULL
                    if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Error on load") {

                        if (isset($pictureNameAfterUpload) && $pictureNameAfterUpload == "Error on load")
                            $data['imageIncorrectFlag'] = true;

                        $pictureNameAfterUpload = null;

                    }

                    $this->userModel->addProductToUserList($data['userId'], $productName, $pictureNameAfterUpload, $productCategoriesArray);

                    //incorrect image flag should be saved in session before refreshing
                    $_SESSION['imageIncorrectFlag'] = false;
                    if (isset($data['imageIncorrectFlag'])) {
                        $_SESSION['imageIncorrectFlag'] = $data['imageIncorrectFlag'];
                    }

                    redirect(base_url().'index.php/myPage');
                }else {
                    $data['incorrectProductNameFlag'] = true;

                }
            }
        }

        //defining variables
        if (!isset($data['categoryCounter']))
            $data['categoryCounter'] = 1;
        if (!isset($data['productName']))
            $data['productName'] = "";
        if (!isset( $data['productCategoriesArray'][$data['categoryCounter']]))
            $data['productCategoriesArray'][$data['categoryCounter'] - 1] = null;

        $this->load->view('addProduct', $data);
    }

    public function deleteProduct()
    {
        session_start();

        $this->load->helper('url');
        $this->load->helper('dataFunctions');
        $this->load->model('userModel');

        if (isset($_SESSION['thisIsLoggedUser'])) {

            $userId = $_SESSION['userSessionId'];
            $productId = $_POST['product_id'];


            $this->userModel->deleteProductFromUserList($userId, $productId);

            if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) {
                $_SESSION['imageIncorrectFlag'] = false;
            }

            redirect(base_url().'index.php/myPage');


        } else {
            redirect(base_url().'index.php/login');
        }
    }

    public function editProduct()
    {
        session_start();

        $this->load->helper('url');
        $this->load->helper('dataFunctions');
        $this->load->model('userModel');

        //echo $this->uri->segment(3);

        $data['userId'] = $_SESSION['userSessionId'];

        if (isset($_POST['addCategory'])) {

            $data['categoryCounter'] = $_POST['categoryCounter'];

            $data['productName'] = $_POST['productName'];
            $data['productId'] = $_POST['product_id'];
            $data['productCategoriesArray'] = $_POST['productCategoriesArray'];

        }

        if (isset($_POST['updateProduct'])) {
            if (isset($_SESSION['thisIsLoggedUser'])) {
                $data['userId'] = $_SESSION['userSessionId'];

                //product picture submitted
                if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

                    $pictureNameAfterUpload = uploadProductPicture($data['userId']);

                }

                //Product Line to be updated
                $data['productId'] = $_POST['product_id'];

                //Changes submitted
                $productName = $_POST['productName'];
                $productCategoriesArray = $_POST['productCategoriesArray'];

                if ($productName != null || $productName != "") {
                    //picture can be not submitted. In that case - setting picture name to the initial value
                    if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Error on load") {

                        if (isset($pictureNameAfterUpload) && $pictureNameAfterUpload == "Error on load")
                            $imageIncorrectFlag = true;

                        $pictureNameAfterUpload = $_POST['initialProductPictureName'];

                        //empty value after submit to null for processing
                        if ($pictureNameAfterUpload == "")
                            $pictureNameAfterUpload = null;

                    }


                    $this->userModel->updateUserProductString($data['userId'], $data['productId'], $productName, $pictureNameAfterUpload, $productCategoriesArray);

                    //incorrect image flag should be saved in session before refreshing
                    $_SESSION['imageIncorrectFlag'] = false;
                    if (isset($imageIncorrectFlag)) {
                        $_SESSION['imageIncorrectFlag'] = $imageIncorrectFlag;
                    }

                    redirect(base_url().'index.php/myPage');


                } else {
                    $data['incorrectProductNameFlag'] = true;
                }
            }else {
                redirect(base_url().'index.php/login');
            }
        }

        if (!isset($data['productId']))
            $data['productId'] = $this->uri->segment(3);
        if (!isset($data['categoryCounter']))
            $data['categoryCounter'] = 1;
        if (!isset($data['productCategoriesArray'][$data['categoryCounter']]))
            $data['productCategoriesArray'][$data['categoryCounter'] - 1] = null;

        $data['productInfo'] = $this->userModel->retrieveProductInfo($data['userId'], $data['productId']);

        $data['productCategories'][$data['productId']] = $this->userModel->retrieveProductCategories($data['productId']);


        $this->load->view('editProduct', $data);

    }

    public function closeMessage(){

        session_start();
        $this->load->helper('url');

        $_SESSION['imageIncorrectFlag'] = false;
        redirect(base_url().'index.php/myPage');
    }

}