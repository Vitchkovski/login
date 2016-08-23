<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function register()
    {
        $this->load->library('form_validation');

        //validation rules
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|min_length[6]|max_length[100]|valid_email|is_unique[users.email]',
            array('is_unique' => 'User with this email is already exist.'));
        $this->form_validation->set_rules('login', 'Username', 'trim|required|min_length[3]|max_length[25]|is_unique[users.login]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]');

        //sanitizing input
        $userSanitizedLogin = $this->input->post('login', TRUE);
        $userSanitizedEmail = $this->input->post('email', TRUE);
        $userSanitizedPassword = $this->input->post('password', TRUE);


        if ($this->form_validation->run() == TRUE) {

            //Creating user in the database if everything is correct
            $this->load->model('userModel');

            $this->userModel->addNewUserToDB($userSanitizedLogin, $userSanitizedEmail, $userSanitizedPassword);

            //retrieving user info and putting it into session data
            $userInfo = $this->userModel->retrieveUserInfo($userSanitizedEmail, $userSanitizedPassword);

            $session_data = array(
                'userSessionId' => $userInfo[0]->user_id,
                'userSessionEmail' => $userInfo[0]->email,
                'userSessionName' => $userInfo[0]->login
            );

            $this->session->set_userdata($session_data);

            //redirecting to the personal page
            redirect(base_url('index.php/products'));

        } else {
            //Nothing submitted yet or validation failed - form just opened.
            $this->load->view('header');
            $this->load->view('login/userRegister');
            $this->load->view('footer');

        }
    }


    public function login()
    {
        $this->load->model('userModel');
        $this->load->library('form_validation');

        //some basic initial checks
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|min_length[6]|max_length[100]|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]');

        if ($this->form_validation->run() == FALSE) {
            //something went wrong or form just opened - opening login page and showing the errors if required

            if ($this->session->userdata('userSessionId')) {
                //if user is authorized we are opening personal page instead of login one
                redirect(base_url('index.php/products'));

            } else {
                //Session is not started for the user - opening login page
                $this->load->view('header');
                $this->load->view('login/userLogin');
                $this->load->view('footer');
            }
        } else {
            //data is ok, now check if credentials are correct

            //sanitizing input
            $userSanitizedEmail = $this->input->post('email', TRUE);
            $userSanitizedPassword = $this->input->post('password', TRUE);

            $userInfo = $this->userModel->retrieveUserInfo($userSanitizedEmail, $userSanitizedPassword);

            if (!empty($userInfo)) {
                //credentials are correct. Setting session data and opening personal page

                $session_data = array(
                    'userSessionId' => $userInfo[0]->user_id,
                    'userSessionEmail' => $userInfo[0]->email,
                    'userSessionName' => $userInfo[0]->login
                );

                $this->session->set_userdata($session_data);

                redirect(base_url('index.php/products'));


            } else {
                //credentials are incorrect or user is not exist. Notifying user

                $data['errorMsg'] = 'Credentials you entered are incorrect.';

                $this->load->view('header');
                $this->load->view('login/userLogin', $data);
                $this->load->view('footer');

            }


        }
    }

    public function reset_password()
    {
        $this->load->library('form_validation');

        //some basic initial checks
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|min_length[6]|max_length[100]|valid_email');


        if ($this->form_validation->run() == FALSE) {
            //email is invalid or form just opened
            $this->load->view('header');
            $this->load->view('login/userPasswordRecovery');
            $this->load->view('footer');
        } else {
            $userEmail = $this->input->post('email', TRUE);

            //checking if email exist in the DB
            $this->load->model('userModel');
            $userInfo = $this->userModel->checkIfEmailExist($userEmail);

            if (!empty($userInfo)) {

                $userName = $userInfo[0]->login;
                $this->sendResetPassword($userEmail, $userName);

                $data['successMessage'] = 'Email to reset your password has been sent to ' . $userEmail . '.';

                $this->load->view('header');
                $this->load->view('login/userPasswordRecovery', $data);
                $this->load->view('footer');
            } else {
                $data['errorMsg'] = 'Email you entered is incorrect';

                $this->load->view('header');
                $this->load->view('login/userPasswordRecovery', $data);
                $this->load->view('footer');
            }

        }
    }

    function sendResetPassword($email, $userName)
    {

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'bot_mail@vitchkovski.com',
            'smtp_pass' => 'zxcqp98F',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n"
        );

        $this->load->library('email', $config);
        $emailResetLinkCode = sha1($this->config->item('encryption_key') . $userName);

        $this->email->set_mailtype('html');
        $this->email->from('mail@vitchkovski.com', 'Vitchkovski');
        $this->email->to($email);

        $this->email->subject('Reset your Email');
        $message = '<p>Hi ' . $userName . '.</p>';
        $message .= '<p>Please click <strong><a href="' . base_url() . 'users/resetYourPassword/' . $email . '/' . $emailResetLinkCode . '">here</a></strong> to reset your password.</p>';

        $this->email->message($message);

        $this->email->send();
    }

    function resetYourPassword($email, $emailResetLinkCode)
    {
        //user just followed the link to reset his password
        if (isset($email, $emailResetLinkCode)) {

            $data['email'] = trim($email);
            $data['emailSecureHash'] = sha1($email . $emailResetLinkCode);
            $data['emailResetLinkCode'] = $emailResetLinkCode;

            $this->load->model('userModel');

            //We need to confirm if data received is correct
            $verifyResetLinkCode = $this->userModel->verifyResetLinkCode($data['email'], $emailResetLinkCode);


            if ($verifyResetLinkCode) {
                //everything is correct opening from to update password
                $this->load->view('header');
                $this->load->view('login/userPasswordUpdate', $data);
                $this->load->view('footer');
            } else {

                $data['errorMsg'] = 'There was an error when resetting your password. Please try again.';

                $this->load->view('header');
                $this->load->view('login/userPasswordRecovery', $data);
                $this->load->view('footer');

            }

        }
    }

    function update_password()
    {
        //actually updating password

        if (!isset($_POST['email'], $_POST['emailSecureHash']) || $_POST['emailSecureHash'] !== sha1($_POST['email'] . $_POST['emailResetLinkCode'])) {
            //Most likely this is an attempt to hack recovery, just dying in this case.
            die('Error updating password.');
        }

        $this->load->library('form_validation');

        //some basic initial checks
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|min_length[6]|max_length[100]|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]');

        if ($this->form_validation->run() == FALSE) {
            //data not validated, sending user to try again
            $this->load->view('header');
            $this->load->view('login/userPasswordUpdate');
            $this->load->view('footer');
        } else {
            //data validated
            $this->load->model('userModel');

            $userEmail = $this->input->post('email', TRUE);
            $userNewPassword = $this->input->post('password', TRUE);

            $this->userModel->updatePassword($userEmail, $userNewPassword);

            $data['successMessage'] = 'Your password has been reset.';


            $this->load->view('header');
            $this->load->view('login/userLogin', $data);
            $this->load->view('footer');

        }

    }

    public
    function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url('index.php/users/login'));
    }

}

