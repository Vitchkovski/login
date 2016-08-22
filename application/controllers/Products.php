<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function index()
    {
        //main function - opening product page

        if ($this->session->userdata('userSessionId')) {
            //if user is logged in retrieving user info, user products

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

        $this->load->helper('dataFunctions');
        $this->load->model('productsModel');


        if (isset($_POST['addCategory'])) {

            $data['categoryCounter'] = $_POST['categoryCounter'];

            $data['productName'] = $_POST['productName'];

            $data['productCategoriesArray'] = $_POST['productCategoriesArray'];

        }

        $this->load->library('form_validation');

        //validation rules
        $this->form_validation->set_rules('productName', 'Product Name', 'trim|required|max_length[254]');
        $this->form_validation->set_rules('productCategoriesArray[]', 'Product Category', 'trim|max_length[254]');


        if ($this->form_validation->run() == FALSE) {
            //data is incorrect (or form just opened) - returning to add product page, notifying user

            //defining variables
            if (!isset($data['productName']))
                $data['productName'] = "";
           /* if (!isset($data['productCategoriesArray'][$data['categoryCounter']]))
                $data['productCategoriesArray'][$data['categoryCounter'] - 1] = null;*/

            $this->load->view('header');
            $this->load->view('products/addProduct', $data);
            $this->load->view('footer');
        } else {
            //everything is fine, attempting to insert product
            if ($this->session->userdata('userSessionId')) {
                //logged user

                $userId = $this->session->userdata('userSessionId');
                $productName = $this->input->post('productName', TRUE);


                //product picture submitted
                if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

                    $pictureNameAfterUpload = uploadProductPicture($data['userId']);

                }

                $productCategoriesArray = $this->input->post('productCategoriesArray[]', TRUE);


                //picture can be not submitted. In that case - setting picture name to NULL
                if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Error on load") {

                    if (isset($pictureNameAfterUpload) && $pictureNameAfterUpload == "Error on load")
                        $data['imageIncorrectFlag'] = true;

                    $pictureNameAfterUpload = null;

                }
                var_dump($productCategoriesArray);
                $this->productsModel->addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesArray);

                //incorrect image flag should be saved in session before refreshing
                $_SESSION['imageIncorrectFlag'] = false;
                if (isset($data['imageIncorrectFlag'])) {
                    $_SESSION['imageIncorrectFlag'] = $data['imageIncorrectFlag'];
                }

                redirect(base_url('index.php/products'));

            } else {
                //user is not logged in
                $this->load->view('header');
                $this->load->view('products/userPersonalPage', $data);
                $this->load->view('footer');
            }
        }

    }

    public function deleteProduct()
    {

        $this->load->helper('dataFunctions');
        $this->load->model('productsModel');

        if ($this->session->userdata('userSessionId')) {

            $userId = $this->session->userdata('userSessionId');
            $productId = $this->input->post('product_id', TRUE);


            $this->productsModel->deleteProductFromUserList($userId, $productId);

            if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) {
                $_SESSION['imageIncorrectFlag'] = false;
            }

            redirect(base_url('index.php/products'));


        } else {
            redirect(base_url('index.php/users/login'));
        }
    }

    public function editProduct()
    {

        $this->load->helper('dataFunctions');
        $this->load->model('productsModel');


        $data['userId'] = $this->session->userdata('userSessionId');


        $this->load->library('form_validation');

        //validation rules
        $this->form_validation->set_rules('productName', 'Product Name', 'trim|required|max_length[254]');
        $this->form_validation->set_rules('productCategoriesArray[]', 'Product Category', 'trim|max_length[254]');


        if ($this->form_validation->run() == FALSE) {
            //something is incorrect or form just opened

            if (!isset($data['productId']))
                $data['productId'] = $this->uri->segment(3);
            /*if (!isset($data['categoryCounter']))
                $data['categoryCounter'] = 1;
            if (!isset($data['productCategoriesArray'][$data['categoryCounter']]))
                $data['productCategoriesArray'][$data['categoryCounter'] - 1] = null;*/

            $data['productInfo'] = $this->productsModel->retrieveProductInfo($data['userId'], $data['productId']);

            if ($data['productInfo']) {
                //checking if product exist for the user
                $data['productCategories'][$data['productId']] = $this->productsModel->retrieveProductCategories($data['productId']);

                $this->load->view('header');
                $this->load->view('products/editProduct', $data);
                $this->load->view('footer');
            } else
                redirect(base_url('index.php/products'));

        } else {
            //data is correct
            if ($this->session->userdata('userSessionId')) {
                //logged user

                $userId = $this->session->userdata('userSessionId');

                //product picture submitted
                if (isset ($_FILES["productPicture"]) && !empty($_FILES["productPicture"]["name"])) {

                    $pictureNameAfterUpload = uploadProductPicture($data['userId']);

                }

                //Product Line to be updated
                $productId = $this->input->post('product_id', TRUE);

                //Changes submitted
                $productName = $this->input->post('productName', TRUE);
                $productCategoriesArray = $this->input->post('productCategoriesArray', TRUE);


                //picture can be not submitted. In that case - setting picture name to the initial value
                if (!isset ($pictureNameAfterUpload) || $pictureNameAfterUpload == "Error on load") {

                    if (isset($pictureNameAfterUpload) && $pictureNameAfterUpload == "Error on load")
                        $imageIncorrectFlag = true;

                    $pictureNameAfterUpload = $_POST['initialProductPictureName'];

                    //empty value after submit to null for processing
                    if ($pictureNameAfterUpload == "")
                        $pictureNameAfterUpload = null;

                }


                $this->productsModel->updateUserProductString($userId, $productId, $productName, $pictureNameAfterUpload, $productCategoriesArray);

                //incorrect image flag should be saved in session before refreshing
                $_SESSION['imageIncorrectFlag'] = false;
                if (isset($imageIncorrectFlag)) {
                    $_SESSION['imageIncorrectFlag'] = $imageIncorrectFlag;
                }

                redirect(base_url('index.php/products'));


            } else {
                //user is not logged in
                redirect(base_url('index.php/users/login'));
            }
        }

    }

    public function closeMessage()
    {

        $_SESSION['imageIncorrectFlag'] = false;
        redirect(base_url('index.php/products'));
    }


}