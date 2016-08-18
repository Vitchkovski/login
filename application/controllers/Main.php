<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller
{

    public function index()
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
                    redirect(base_url().'index.php/myPage');
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



    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        redirect(base_url().'index.php/main');
    }


}

