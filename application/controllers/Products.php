<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function index()
    {

        if ($this->session->userdata('userSessionId')) {

            $this->load->helper('dataFunctions');
            $this->load->model('productsModel');

            $data['userId'] = $this->session->userdata('userSessionId');
            $data['userName'] = $this->session->userdata('userSessionName');


            //echo "User ID: ".$userId."<br>";
            $data['userProducts'] = $this->productsModel->retrieveUserProducts($data['userId']);


            /*echo "userProducts: ";
            var_dump($data['userProducts']);
            echo "<br><br>";*/
            if (!empty($data['userProducts'])) {
                foreach ($data['userProducts'] as $uP):
                    $data['productCategories'][$uP->product_id] = $this->productsModel->retrieveProductCategories($uP->product_id);
                endforeach;

            }

            /*echo "productCategories: ";
            var_dump($data['productCategories']);
            echo "<br><br>";*/

            $this->load->view('header');
            $this->load->view('products/userPersonalPage', $data);
            $this->load->view('footer');

        } else {
            //Session is not started for the user - opening login page
            redirect(base_url('index.php/users/login'));

        }

    }

    public function addProduct()
    {
        session_start();
        $this->load->helper('dataFunctions');
        $this->load->model('productsModel');
        $this->load->view('footer');

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

                    $this->productsModel->addProductToUserList($data['userId'], $productName, $pictureNameAfterUpload, $productCategoriesArray);

                    //incorrect image flag should be saved in session before refreshing
                    $_SESSION['imageIncorrectFlag'] = false;
                    if (isset($data['imageIncorrectFlag'])) {
                        $_SESSION['imageIncorrectFlag'] = $data['imageIncorrectFlag'];
                    }

                    redirect(base_url('index.php/products'));
                } else {
                    $data['incorrectProductNameFlag'] = true;

                }
            }
        }

        //defining variables
        if (!isset($data['categoryCounter']))
            $data['categoryCounter'] = 1;
        if (!isset($data['productName']))
            $data['productName'] = "";
        if (!isset($data['productCategoriesArray'][$data['categoryCounter']]))
            $data['productCategoriesArray'][$data['categoryCounter'] - 1] = null;

        $this->load->view('header');
        $this->load->view('products/addProduct', $data);
        $this->load->view('footer');
    }

    public function deleteProduct()
    {
        session_start();

        $this->load->helper('dataFunctions');
        $this->load->model('productsModel');

        if (isset($_SESSION['thisIsLoggedUser'])) {

            $userId = $_SESSION['userSessionId'];
            $productId = $_POST['product_id'];


            $this->productsModel->deleteProductFromUserList($userId, $productId);

            if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) {
                $_SESSION['imageIncorrectFlag'] = false;
            }

            redirect(base_url('index.php/products'));


        } else {
            redirect(base_url('index.php/users/login') );
        }
    }

    public function editProduct()
    {
        session_start();

        $this->load->helper('dataFunctions');
        $this->load->model('productsModel');


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


                    $this->productsModel->updateUserProductString($data['userId'], $data['productId'], $productName, $pictureNameAfterUpload, $productCategoriesArray);

                    //incorrect image flag should be saved in session before refreshing
                    $_SESSION['imageIncorrectFlag'] = false;
                    if (isset($imageIncorrectFlag)) {
                        $_SESSION['imageIncorrectFlag'] = $imageIncorrectFlag;
                    }

                    redirect(base_url('index.php/products'));


                } else {
                    $data['incorrectProductNameFlag'] = true;
                }
            } else {
                redirect(base_url('index.php/users/login'));
            }
        }

        if (!isset($data['productId']))
            $data['productId'] = $this->uri->segment(3);
        if (!isset($data['categoryCounter']))
            $data['categoryCounter'] = 1;
        if (!isset($data['productCategoriesArray'][$data['categoryCounter']]))
            $data['productCategoriesArray'][$data['categoryCounter'] - 1] = null;

        $data['productInfo'] = $this->productsModel->retrieveProductInfo($data['userId'], $data['productId']);

        $data['productCategories'][$data['productId']] = $this->productsModel->retrieveProductCategories($data['productId']);

        $this->load->view('header');
        $this->load->view('products/editProduct', $data);
        $this->load->view('footer');

    }

    public function closeMessage()
    {

        //session_start();

        $_SESSION['imageIncorrectFlag'] = false;
        redirect(base_url('index.php/products'));
    }


}