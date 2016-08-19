<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function index()
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
}