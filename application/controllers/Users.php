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

    public function logout()
    {
        //session_start();
        session_unset();
        session_destroy();

        redirect(base_url('index.php/users/login'));
    }

}

