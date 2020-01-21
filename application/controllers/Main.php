<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public $status;
    public $roles;

    function __construct(){
        parent::__construct();
        $this->load->model('User_model', 'user_model', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel'); 
        
    }

    //index dasboard
	public function index()
	{
	    //user data from session
	    $data = $this->session->userdata;
	    if(empty($data)){
	        redirect(site_url().'main/login/');
	    }

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level
        
	    $data['title'] = "Dashboard Admin";
	    
        if(empty($this->session->userdata['email'])){
            redirect(site_url().'main/login/');
        }else{
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('index', $data);
            $this->load->view('footer');
        }

	}
	
	public function checkLoginUser(){
	     //user data from session
	    $data = $this->session->userdata;
	    if(empty($data)){
	        redirect(site_url().'main/login/');
	    }
	    
	$this->load->library('user_agent');
        $browser = $this->agent->browser();
        $os = $this->agent->platform();
        $getip = $this->input->ip_address();
        
        $result = $this->user_model->getAllSettings();
        $stLe = $result->site_title;
	    $tz = $result->timezone;
	    
	      $now = new DateTime();
          date_default_timezone_set($tz);
          date_default_timezone_set('Asia/riyadh');
        // $now->setTimezone(new DateTimezone($tz));
        $dTod =  $now->format('Y-m-d');
        $dTim =  $now->format('H:i:s');
        
        $this->load->helper('cookie');
        $keyid = rand(1,9000);
        $scSh = sha1($keyid);
        $neMSC = md5($data['email']);
        $setLogin = array(
            'name'   => $neMSC,
            'value'  => $scSh,
            'expire' => strtotime("+2 year"),
        );
        $getAccess = get_cookie($neMSC);
	    
        if(!$getAccess && $setLogin["name"] == $neMSC){
            $this->load->library('email');
            $this->load->library('sendmail');
            $bUrl = base_url();
            $message = $this->sendmail->secureMail($data['first_name'],$data['last_name'],$data['email'],$dTod,$dTim,$stLe,$browser,$os,$getip,$bUrl);
            $to_email = $data['email'];
            $this->email->from($this->config->item('register'), 'New sign-in! from '.$browser.'');
            $this->email->to($to_email);
            $this->email->subject('New sign-in! from '.$browser.'');
            $this->email->message($message);
            $this->email->set_mailtype("html");
            $this->email->send();
            
            $this->input->set_cookie($setLogin, TRUE);
            redirect(site_url().'main/');
        }else{
            $this->input->set_cookie($setLogin, TRUE);
            redirect(site_url().'main/');
        }
	}
	
	public function settings(){
	    $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

        $data['title'] = "Settings";
        $this->form_validation->set_rules('site_title', 'Site Title', 'required');
        $this->form_validation->set_rules('timezone', 'Timezone', 'required');
        $this->form_validation->set_rules('recaptcha', 'Recaptcha', 'required');
        $this->form_validation->set_rules('theme', 'Theme', 'required');

        $result = $this->user_model->getAllSettings();
        $data['id'] = $result->id;
	    $data['site_title'] = $result->site_title;
	    $data['timezone'] = $result->timezone;
	    
	    if (!empty($data['timezone']))
	    {
	        $data['timezonevalue'] = $result->timezone;
	        $data['timezone'] = $result->timezone;
	    }
	    else
	    {
	        $data['timezonevalue'] = "";
            $data['timezone'] = "Select a time zone";
	    }
	    
	    if($dataLevel == "is_admin"){
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('navbar', $data);
                $this->load->view('container');
                $this->load->view('settings', $data);
                $this->load->view('footer');
            }else{
                $post = $this->input->post(NULL, TRUE);
                $cleanPost = $this->security->xss_clean($post);
                $cleanPost['id'] = $this->input->post('id');
                $cleanPost['site_title'] = $this->input->post('site_title');
                $cleanPost['timezone'] = $this->input->post('timezone');
                $cleanPost['recaptcha'] = $this->input->post('recaptcha');
                $cleanPost['theme'] = $this->input->post('theme');
    
                if(!$this->user_model->settings($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your data!');
                }else{
                    $this->session->set_flashdata('success_message', 'Your data has been updated.');
                }
                redirect(site_url().'main/settings/');
            }
	    }

	}
    
    	//user list
	public function users()
	{
	    $data = $this->session->userdata;
	    $data['title'] = "User List";
	    $data['groups'] = $this->user_model->getUserData(); 
        $data['xfooter'] = '
        
       
        <script src="' . base_url().'assets/styles/js/jquery.dataTables.min.js"></script> 
        <script src="' . base_url().'assets/styles/js/dataTables.bootstrap.min.js"></script> 
        
        ';
        $data['xjq'] = " 
        $(\".datatable\").DataTable();
        console.log('dataTable called');
        ";
	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    //check is admin or not
	    if($dataLevel == "is_admin"){
            $this->output->delete_cache();
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);

            $this->load->view('container');
            $this->load->view('user', $data);
            $this->load->view('adduser', $data);
            $this->load->view('footer');
	    }else{
	        redirect(site_url().'main/');
	    }
	}

    	

    //edit user
	public function changeuser() 
    {
        // sha256:1000::RDSzmFu7FdqnVLMmCrX89yNnKeZQ3T7F      123456
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

        $dataInfo = array(
            'firstName'=> $data['first_name'],
            'id'=>$data['id'],
        );

        $data['title'] = "Change Password";
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        $data['groups'] = $this->user_model->getUserInfo($dataInfo['id']);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('changeuser', $data);
            $this->load->view('footer');
        }else{
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $dataInfo['id'];
            $cleanPost['email'] = $this->input->post('email');
            $cleanPost['firstname'] = $this->input->post('firstname');
            $cleanPost['lastname'] = $this->input->post('lastname');
            unset($cleanPost['passconf']);
            if(!$this->user_model->updateProfile($cleanPost)){
                $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
            }else{
                $this->session->set_flashdata('success_message', 'Your profile has been updated.');
            }
            redirect(site_url().'main/');
        }
    }

    //open profile and gravatar user
    public function profile()
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

        $data['title'] = "Profile";
        $this->load->view('header', $data);
        $this->load->view('navbar', $data);
        $this->load->view('container');
        $this->load->view('profile', $data);
        $this->load->view('footer');

    }
 

    //add new user from backend
    public function adduser()
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    } 
	    $dataLevel = $this->userlevel->checkLevel($data['role']); 
	    if($dataLevel == "is_admin"){
            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('phone', 'phone', 'required'); 

            $data['title'] = "Add User";
            if ($this->form_validation->run() == FALSE)
             {
                $this->load->view('header', $data);
                $this->load->view('navbar');
                $this->load->view('container');
                $this->load->view('adduser', $data);
                $this->load->view('footer');
            }
            else
            {

                    if($this->user_model->isDuplicate($this->input->post('phone'))){
                        $this->session->set_flashdata('flash_message', 'User phone  already exists');
                        redirect(site_url().'main/adduser');
                }
                else{
                    $this->load->library('password');
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post); 
                    $cleanPost['email'] = $this->input->post('email'); 
                    $cleanPost['firstname'] = $this->input->post('firstname');
                    $cleanPost['phone'] = $this->input->post('phone'); 
                    $cleanPost['company'] = $this->input->post('company'); 
                    $cleanPost['dpt'] = $this->input->post('dpt'); 

                    //insert to database
                    if(!$this->user_model->addUser($cleanPost)){
                        $this->session->set_flashdata('flash_message', 'There was a problem add new member');
                    }else{
                        $this->session->set_flashdata('success_message', 'New member has been added.');
                    }
                    redirect(site_url().'main/users/');
                };
            }
	    }else{
	        redirect(site_url().'main/');
	    }
    }
    //update   user from backend
    public function updateuser($id=null)
    {    
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

         

        $data['title'] = "Change info";
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required'); 

        $data['groups'] = $this->user_model->getUserInfo($id);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('updateuser', $data);
            $this->load->view('footer');
        }else{ 
            $post = $this->input->post(NULL, TRUE); 
            $cleanPost['user_id'] = $id;
            $cleanPost['email'] = $this->input->post('email');
            $cleanPost['firstname'] = $this->input->post('firstname');
            $cleanPost['phone'] = $this->input->post('phone');
            $cleanPost['company'] = $this->input->post('company');
            $cleanPost['dpt'] = $this->input->post('dpt');
            unset($cleanPost['passconf']);
            if(!$this->user_model->updateuser($cleanPost)){
                $this->session->set_flashdata('flash_message', 'There was a problem updating member');
            }else{
                $this->session->set_flashdata('success_message', 'member has been updated.');
            }
            redirect(site_url().'main/users');
        }
    }

     

    //if success new user register
    public function successregister()
    {
        $data['title'] = "Success Register";
        $this->load->view('header', $data);
        $this->load->view('container');
        $this->load->view('register-info');
        $this->load->view('footer');
    }

   

    protected function _islocal(){
        return strpos($_SERVER['HTTP_HOST'], 'local');
    }

    

    //check login failed or success
    public function login()
    {
        $data = $this->session->userdata;
        if(!empty($data['email'])){
	        redirect(site_url().'main/');
	    }else{
	        $this->load->library('curl');
            $this->load->library('recaptcha');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            $data['title'] = "Welcome Back!";
            
            $result = $this->user_model->getAllSettings();
            $data['recaptcha'] = $result->recaptcha;

            if($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('container');
                $this->load->view('login');
                $this->load->view('footer');
            }else{
                $post = $this->input->post();
                $clean = $this->security->xss_clean($post);
                $userInfo = $this->user_model->checkLogin($clean);
                
                if($data['recaptcha'] == 'yes'){
                    //recaptcha
                    $recaptchaResponse = $this->input->post('g-recaptcha-response');
                    $userIp = $_SERVER['REMOTE_ADDR'];
                    $key = $this->recaptcha->secret;
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$recaptchaResponse."&remoteip=".$userIp; //link
                    $response = $this->curl->simple_get($url);
                    $status= json_decode($response, true);
    
                    if(!$userInfo)
                    {
                        $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                        redirect(site_url().'main/login');
                    }
                    elseif($userInfo->banned_users == "ban")
                    {
                        $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                        redirect(site_url().'main/login');
                    }
                    else if(!$status['success'])
                    {
                        //recaptcha failed
                        $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                        redirect(site_url().'main/login/');
                        exit;
                    }
                    elseif($status['success'] && $userInfo && $userInfo->banned_users == "unban") //recaptcha check, success login, ban or unban
                    {
                        foreach($userInfo as $key=>$val){
                        $this->session->set_userdata($key, $val);
                        }
                        redirect(site_url().'main/checkLoginUser/');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', 'Something Error!');
                        redirect(site_url().'main/login/');
                        exit;
                    }
                }else{
                    if(!$userInfo)
                    {
                        $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                        redirect(site_url().'main/login');
                    }
                    elseif($userInfo->banned_users == "ban")
                    {
                        $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                        redirect(site_url().'main/login');
                    }
                    elseif($userInfo && $userInfo->banned_users == "unban") //recaptcha check, success login, ban or unban
                    {
                        foreach($userInfo as $key=>$val){
                        $this->session->set_userdata($key, $val);
                        }
                        redirect(site_url().'main/checkLoginUser/');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', 'Something Error!');
                        redirect(site_url().'main/login/');
                        exit;
                    }
                }
            }
	    }
    }

    //Logout
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url().'main/login/');
    }
 
    public function present($id)
    {    
      
        date_default_timezone_set('Asia/riyadh');
        $this->db->where('id', $id);
        $this->db->update('users', array('last_login' => date('Y-m-d h:i:s A')));
           redirect(site_url().'main/users');
         
            
            
    }
    public function cancel($id)
    {    
       
        $this->db->where('id', $id);
        $this->db->update('users', array('last_login' => date('')));
           redirect(site_url().'main/users');
         
            
            
    }
}
