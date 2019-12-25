<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    private $emailS = null;
    function permission()
    {
        $role = $this->data['page']['id'];
        if ($role) {
            $response = is_login($role);
            if ($response->status) {
                redirect($this->session->userdata('url'));
            }
        } else {
            $response = is_login();
            // die(var_dump($response));

            if ($response->status) {
                if ($this->session->userdata('time') < time()) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Waktu Login habis!</span></div>');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Anda Sudah Login!</span></div>');
                }
                redirect($this->session->userdata('url'));
            }
        }
    }
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('my_helper');
        $this->load->model('user_model');
        $this->load->library('form_validation');
    }
    public function index()
    {
        $this->permission();
        $this->data['page']['title'] = 'Login Page';
        $this->data['form']['action_login'] = base_url('admin/auth');


        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');


        if ($this->form_validation->run() == false) {
            $this->template->load('admin', 'user/login', $this->data);
        } else {
            // validasinya success
            $this->_login();
        }
    }


    function lock()
    {
        if (!$this->session->userdata('email')) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">You Must Login.!!</span></div>');
            redirect(base_url('admin/auth'));
        } elseif (time() < $this->session->userdata('time'))
            redirect($this->session->userdata('url'));

        $this->data['user'] = $this->user_model->getUser($this->session->userdata('email'))->row_array();
        $this->data['page']['title'] = 'Your Session must Unlock';
        $this->data['form']['action'] = base_url('admin/auth/lock');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run()) {
            $this->_login();
        }

        $this->template->load('admin', 'user/lock', $this->data);
    }
    function unlock()
    {
    }



    function not_found()
    {
        $this->template->load('admin', 'error', $this->data);
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $this->load->model('user_model');
        $user = $this->user_model->cekUser($email)->row_array();

        // jika usernya ada
        if ($user) {
            // jika usernya aktif
            if ($user['is_active'] == 1) {
                // cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'time' => time() + (60 * 60),
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    $this->session->set_userdata($data);
                    $this->session->set_flashdata('notif', '<div class="alert alert-succes" role="alert">Succes Login!</div>');
                    if ($this->session->userdata('url')) {
                        redirect($this->session->userdata('url'));
                    } else
                        redirect('admin/user');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
                    if ($this->session->userdata('email')) {
                        redirect('admin/auth/lock');
                    } else
                        redirect('admin/auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');
                redirect('admin/auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered!</div>');
            redirect('admin/auth');
        }
    }


    public function registration($name)
    {
        $this->permission();

        $user = new $this->tbl;                     //inisialisasi model
        $user->setTable("tbl_user", "user");    //inisialisasi table dan keyTable exp: "user_001"
        $user->addRules("name");
        $user->addRules("email", 'valid_email|is_unique[tbl_user.email]');
        $user->addRules("no_hp", "numeric");
        $user->addRules("alamat");
        $user->addRules("password");
        $user->addRules("password", "matches[password]");
        $validation = $this->form_validation;

        $validation->set_rules($user->getRules());
        if ($validation->run()) {

            // mengubah semua inputan post ke dalam variable post
            $post = $this->input->post();
            // mengubah password ke encripsi/

            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            list($m, $d, $y) = explode("/", $post['tgl_lahir']);
            $timestamp = strtotime("{$d}-{$m}-{$y}");
            $post['tgl_lahir'] = $timestamp;

            // cek role registrasi
            $role = new $this->tbl;
            $role->setTable("tbl_user_role");    //inisialisasi table dan keyTable exp: "user_001"
            $role->setField(['name' => $name]);
            $roleId = $role->getWhere('name')->data->row_array();

            if (!$roleId) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Anda Memaksa Registrasi .!</br></span></div>');
                redirect("/");
            } else
                $post["role_id"] = $roleId['id'];

            $file = new $this->tbl; //inisialisai table modal FILE
            $file->setTable("tbl_user_file", 'file');
            $post['file_id'] = 'file_001'; //File Default 
            $upload_image = $_FILES['image']['name'];
            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size']      = '2048';
                $config['upload_path'] = './assets/img/profile/';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $image = $this->upload->data('file_name');
                    $post['file_id']  = $file->getLastKey();

                    $post['image'] = $image;
                    _img_create_thumbs($image, "profile");
                }
            }


            // cek koneksi internet
            $connected = @fsockopen('www.google.com', 80);
            if (!$connected) {
                redirect("admin/auth/registration/" . $name);
            }
            // simpang data user ke database
            $user->setField($post);
            $eks = $user->add();
            if ($eks->status) { //jika ada sukses input user

                if ($post['file_id'] !== 'file_001') { // cek jika ada  file di upload
                    // buat account  file 
                    $user_data = $user->getField();
                    $file_data = [
                        "type" => 1, // 1 for Image 2 for file
                        "user_id" => $user_data['id_user'],
                        "file" => $post['image'] //save image jika ada
                    ];
                    $file->setField($file_data);
                    $eks = $file->add($file_data);
                } else {
                    die(var_dump("tdk upload"));
                }
                // mengirim token ke email
                $token =  uniqid(); //random token
                $send  = $this->_sendEmail($token, 'verify');
                if ($send) {
                    $user_token = [
                        'email' => $post['email'],
                        'token' => $token,
                        'date_created' => time()
                    ];
                    $token = $this->tbl;
                    $token->setTable("tbl_user_token", "tkn");
                    $token->setField($user_token);
                    $eksT = $token->add($user_token);
                    if (!$eksT->status) {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Kesalahan Sistem .!</br></span></div>');
                        redirect('admin/auth/registration');
                    }
                    redirect('admin/auth/verify?email=' . $post['email']);
                }
            } else
                redirect(base_url('admin/auth/registration/' . $name)); //gagal input kedatabase
        } else {
            $this->data['page']['title'] = 'User Registration';
            $this->template->load('admin', 'user/register', $this->data);
        }
    }


    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'berkominfo@gmail.com',
            'smtp_pass' => 'ichaNK01',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->load->library('email');
        $this->email->initialize($config);

        $this->email->from('berkominfo@gmail.com', 'Berkominfo');
        if ($this->emailS == null) {
            $this->email->to($this->input->post('email'));
        } else
            $this->email->to($this->emailS);

        if ($type == 'verify') {
            $this->email->subject('Account Verification');
            $this->email->message('Your Token : ' . $token . ' ,</br>Click this link to verify you account : <a href="' . base_url() . 'admin/auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Your Token : ' . $token . ' ,</br>Click this link to reset your password : <a href="' . base_url() . 'admin/auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }
        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die();
        }
    }

    function send_token()
    {
        $this->emailS = $this->input->get('email');
        $user_token = $this->user_model->cekToken($this->emailS)->row_array();
        if (!$user_token) {
            redirect(base_url('admin/auth/verify?email=' . $this->emailS));
        } else {  //cek token
            $pesan = "sistem Telah Mengirim Token Ke email Anda";
            $token =  uniqid(); //random token
            if ($user_token['qty'] > 4) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Sistem Mengirim Token terlalu banyak, Silahkan Registrasi ulang</span></div>');
                $this->db->where(["email" => $this->emailS]);
                $this->db->delete('tbl_user');
                redirect("/");
            } else if ($user_token['date_created'] + (60 * 3) < time() && $user_token['qty'] == 1) {
                $this->_sendEmail($token, 'verify');
                $user_token['qty']++;
            } else if ($user_token['qty'] == 2 && $user_token['date_created'] + (60 * 5) < time()) {
                $this->_sendEmail($token, 'verify');
                $user_token['qty']++;
            } else if ($user_token['qty'] == 3 && $user_token['date_created'] + (60 * 7)  < time()) {
                $this->_sendEmail($token, 'verify');
                $user_token['qty']++;
            } else if ($user_token['qty'] == 4 && $user_token['date_created'] + (60 * 8)  < time()) {
                $this->_sendEmail($token, 'verify');
                $user_token['qty']++;
            } else $pesan = "Harap Menunggu Untuk Kirim token Kembali";
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-with-icon' data-notify='container'><i class='fa fa-volume-up' data-notify='icon'></i><button type='button' class='close' data-dismiss='alert' aria-label='Close'><i class='fa fa-times-circle'></i></button><span data-notify='message'>{$pesan}</span></div>");
            $data = [
                "token" => $token,
                "qty" => $user_token['qty'],
                "date_created" => time()
            ];
            $this->user_model->updateToken($this->emailS, $data);
            redirect(base_url('admin/auth/verify?email=' . $this->emailS));
        }
    }

    public function verify()
    {
        $user = $this->user_model;
        $this->permission();
        $get = $this->input->get();

        $dt = $user->getUser($get['email'])->row_array();
        if (!$dt) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong email.</div>');
            redirect('admin/auth');
        } elseif ($dt['is_active'] == 1) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email Anda Terdaftar, Silahkan Login.</div>');
            redirect('admin/auth');
        } else {
            if (!$this->input->get('submit')) {
                $this->template->load('admin', 'user/verify', $this->data);
            } else {
                $user_token = $this->db->get_where('tbl_user_token', ['token' => $get['token']])->row_array();
                if ($user_token) {
                    if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                        $this->db->set('is_active', 1);
                        $this->db->where('email', $get['email']);
                        $this->db->update('tbl_user');
                        $this->db->delete('tbl_user_token', ['email' => $get['email']]);
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $get['email'] . ' has been activated! Please login.</div>');
                        redirect('admin/auth');
                    } else {
                        $this->db->delete('tbl_user', ['email' => $get['email']]);
                        $this->db->delete('user_token', ['email' => $get['email']]);
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Token expired.</div>');
                        redirect('admin/auth');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong token.</div>');
                    redirect('admin/auth/verify?email=' . $get['email']);
                }
            }
        }
    }


    public function logout()
    {
        $user_data = $this->session->all_userdata();

        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out!</div>');
        redirect('admin/auth');
    }


    public function forgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == false) {
            $this->data['title'] = 'Forgot Password';
            $this->load->view('templates/admin/auth_header', $this->data);
            $this->load->view('admin/auth/forgot-password');
            $this->load->view('templates/admin/auth_footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

            if ($user) {
                $token = uniqid();
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Please check your email to reset your password!</div>');
                redirect('admin/auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered or activated!</div>');
                redirect('admin/auth/forgotpassword');
            }
        }
    }


    public function resetPassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong token.</div>');
                redirect('admin/auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong email.</div>');
            redirect('admin/auth');
        }
    }
}
