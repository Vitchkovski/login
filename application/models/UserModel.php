<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model
{

    function addNewUserToDB($login, $email, $password)
    {
        $this->load->database();

        $hashedPassword = hash('sha512', $password);

        $sql = "insert into users (login, email, password) values (" . $this->db->escape(ltrim(rtrim($login))) . "," . $this->db->escape(ltrim(rtrim($email))) . ",'" . $hashedPassword . "')";

        $this->db->query($sql);

        $this->db->close();

    }



//checking if user with provided params is already exist
    function checkIfUserExist($login, $email)
    {
        $this->load->database();

        $sql = "select * from users where login = " . $this->db->escape($login) . " or email = " . $this->db->escape($email) . "";

        $query = $this->db->query($sql);
        $this->db->close();


        $userInfo = $query->result_object();



        if (!empty($userInfo))
            return true;

        return false;

    }

    function retrieveUserInfo($email, $password)
    {

        $this->load->database();

        $hashedPassword = hash('sha512', $password);

        $sql = "select * from users where email = " . $this->db->escape($email) . " 
                                     and password = '" . $hashedPassword . "'";

        $query = $this->db->query($sql);
        $this->db->close();

        $userInfo = $query->result_object();

        return $userInfo;
    }

    function checkIfEmailExist($email)
    {

        $this->load->database();

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email =', $email);

        $query = $this->db->get();
        $this->db->close();

        $userInfo = $query->result_object();

        return $userInfo;
    }

    function verifyResetLinkCode($email, $resetEmailCode)
    {

        $this->load->database();

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email =', $email);

        $query = $this->db->get();
        $this->db->close();

        $userInfo = $query->result_object();

        if(!empty ($userInfo) && $resetEmailCode == sha1($this->config->item('encryption_key') . $userInfo[0]->login) ){
        return true;
        } else {
            return false;
        }

    }


    function updatePassword($email, $newPassword)
    {

        $this->load->database();

        $data = array(
            'password' => hash('sha512', $newPassword)
        );

        $this->db->where('email =', $email);
        $this->db->update('users', $data);
        $this->db->close();

    }




}