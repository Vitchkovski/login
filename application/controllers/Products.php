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


        $this->load->library('form_validation');

        //validation rules
        $this->form_validation->set_rules('productName', 'Product Name', 'trim|required|max_length[254]');
        $this->form_validation->set_rules('productCategoriesArray[]', 'Product Category', 'trim|max_length[254]');


        if ($this->form_validation->run() == FALSE) {
            //data is incorrect (or form just opened) - returning to add product page, notifying user

            $this->load->view('header');
            $this->load->view('products/addProduct');
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

                //saving new product
                $this->productsModel->addProductToUserList($userId, $productName, $pictureNameAfterUpload, $productCategoriesArray);

                //incorrect image flag should be saved in session before refreshing
                $_SESSION['imageIncorrectFlag'] = false;
                if (isset($data['imageIncorrectFlag'])) {
                    $_SESSION['imageIncorrectFlag'] = $data['imageIncorrectFlag'];
                }

                //returning to product page
                redirect(base_url('index.php/products'));

            } else {
                //user is not logged in
                $this->load->view('header');
                $this->load->view('products/userPersonalPage');
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

            $data['productId'] = $this->uri->segment(3);

            $data['productInfo'] = $this->productsModel->retrieveProductInfo($data['userId'], $data['productId']);

            if ($data['productInfo']) {
                //product exist for the user
                $data['productCategories'][$data['productId']] = $this->productsModel->retrieveProductCategories($data['productId']);

                $this->load->view('header');
                $this->load->view('products/editProduct', $data);
                $this->load->view('footer');

            } else {
                //someone passed product id parameter from url and it is incorrect
                $this->session->set_flashdata('errorMsg', 'Product ID is incorrect.');
                redirect(base_url('index.php/products'));
            }

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