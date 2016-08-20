<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function register()
    {
        $this->load->helper('dataFunctions');

        if (!empty($_POST['login'])) {
            $userEscapedLogin = escapeSpecialCharactersHTML($_POST['login']);
        }
        if (!empty($_POST['email'])) {
            $userEscapedEmail = escapeSpecialCharactersHTML($_POST['email']);
        }
        if (!empty($_POST['password'])) {
            $userEscapedPassword = escapeSpecialCharactersHTML($_POST['password']);
        }


        if (!empty($_POST) && isset($userEscapedLogin) && isset($userEscapedEmail) && isset($userEscapedPassword)) {
            $this->load->model('userModel');


            if (!$this->userModel->checkIfUserExist($userEscapedLogin, $userEscapedEmail)) {

                //User does not exist. Creating user in the database
                if (validateIfPasswordSecure($userEscapedPassword)) {
                    //pass is complicated enough
                    $this->userModel->addNewUserToDB($userEscapedLogin, $userEscapedEmail, $userEscapedPassword);

                    //immediately after login - retrieving user info
                    $userInfo = $this->userModel->retrieveUserInfo($userEscapedLogin, $userEscapedEmail, $userEscapedPassword);

                    $userId = $userInfo[0]->user_id;
                    $userEmail = $userInfo[0]->email;
                    $userName = $userInfo[0]->login;

                    session_start();
                    $_SESSION['thisIsLoggedUser'] = true;

                    $_SESSION['userSessionId'] = $userId;
                    $_SESSION['userSessionEmail'] = $userEmail;
                    $_SESSION['userSessionName'] = $userName;

                    //redirecting to the personal page
                    redirect(base_url('index.php/products'));
                } else {
                    //password is to short. Notifying user.
                    $data['passwordIsToShortFlag'] = true;

                    $this->load->view('header');
                    $this->load->view('login/userRegister', $data);
                    $this->load->view('footer');
                }
            } else {
                //User is already exist. Retrieving id.
                $data['userIsAlreadyExistFlag'] = true;

                session_start();
                $_SESSION['userSessionEmail'] = $_POST['email'];
                $_SESSION['userSessionLogin'] = $_POST['login'];

                $this->load->view('header');
                $this->load->view('login/userRegister', $data);
                $this->load->view('footer');
            }
        } else {
            //Nothing submitted yet - form just opened.
            $this->load->view('header');
            $this->load->view('login/userRegister');
            $this->load->view('footer');

        }
    }


    public function login()
    {
        $this->load->helper('dataFunctions');
        $this->load->model('userModel');

        if (!empty($_POST) && !isset($_POST['logout_flag'])) {

            //escaping special & space characters first for all input
            if (!empty($_POST['email'])) {
                $userEscapedEmail = escapeSpecialCharactersHTML($_POST['email']);
            }
            if (!empty($_POST['password'])) {
                $userEscapedPassword = escapeSpecialCharactersHTML($_POST['password']);
            }
            $userEscapedLogin = "";



            $userInfo = $this->userModel->retrieveUserInfo($userEscapedLogin, $userEscapedEmail, $userEscapedPassword);

            //credentials are correct
            if (!empty($userInfo)) {


                $userId = $userInfo[0]->user_id;
                $userEmail = $userInfo[0]->email;
                $userName = $userInfo[0]->login;

                //$userProducts = retrieveUserProducts($userId);

                session_start();
                $_SESSION['thisIsLoggedUser'] = true;

                $_SESSION['userSessionId'] = $userId;
                $_SESSION['userSessionEmail'] = $userEmail;
                $_SESSION['userSessionName'] = $userName;

                redirect(base_url('index.php/products'));
                //include("../views/userPersonalPage.php");

                //Login Succesfull
            } else {
                //credentials are incorrect. Notifying user

                $data['credentialsAreIncorrectFlag'] = true;

                $this->load->view('header');
                $this->load->view('login/userLogin', $data);
                $this->load->view('footer');

            }


        } else {
            //Nothing submitted yet - form just opened. Defining variables.
            session_start();


            //Redirecting authorized user to the personal info page
            if (isset($_SESSION['thisIsLoggedUser'])) {

                redirect(base_url('index.php/products'));


            } else {
                //Session is not started for the user - opening login page
                $this->load->view('header');
                $this->load->view('login/userLogin');
                $this->load->view('footer');
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        redirect(base_url('index.php/users/login'));
    }

}

