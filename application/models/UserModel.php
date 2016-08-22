<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model
{

    function addNewUserToDB($login, $email, $password)
    {
        $this->load->database();

        $hashedPassword = hash('sha512', $password);
        $login = $this->db->escape_str(ltrim(rtrim($login)));
        $email = $this->db->escape_str(ltrim(rtrim($email)));

        $data = array(
            'login' => $login ,
            'email' => $email ,
            'password' => $hashedPassword
        );

        $this->db->insert('users', $data);

        $this->db->close();

    }



//checking if user with provided params is already exist
    function checkIfUserExist($login, $email)
    {
        $this->load->database();

        $login = $this->db->escape(ltrim(rtrim($login)));
        $email = $this->db->escape(ltrim(rtrim($email)));

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('login =', $login);
        $this->db->or_where('email =', $email);


        $query = $this->db->get();

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
        $email = $this->db->escape_str(ltrim(rtrim($email)));

        $query = $this->db->get_where('users', array('email' => $email,
            'password' => $hashedPassword));

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