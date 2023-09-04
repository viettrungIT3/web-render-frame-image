<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function login()
    {
        $posting_data = $this->get_posting_data();

        $cookie_name = $_ENV['COOKIE_NAME'];
        $correct_username = $_ENV['USER_NAME'];
        $correct_password = $_ENV['USER_PASSWORD'];

        $input_username = $posting_data['username'] ?? '';
        $input_password = $posting_data['password'] ?? '';

        if ($input_username == $correct_username && $input_password == $correct_password) {
            $this->load->library('session');
            $this->session->set_userdata('logged_in', TRUE);
            $this->session->mark_as_temp(array('logged_in' => 900)); // Set thời gian sống cho session là 15p = 60 * 15 

            redirect('list-cards/' . $cookie_name);
        } else {
            // Đăng nhập thất bại
            $this->load->view('login');
        }
    }

}