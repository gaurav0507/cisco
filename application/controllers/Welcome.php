<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
	   {
		   parent::__construct();
           date_default_timezone_get('Asia/kolkata');
		   
		   $this->load->library('session');
           $this->load->helper('url');
		   $this->load->library('form_validation');
		   $this->load->model(array('temp_model'));
	   }

	
	public function index()
	{
		$this->load->view('excel');
	}
	
	public function temporarydata()
	{
		$data['results'] = json_decode($this->temp_model->select_temporary(),true);
		$this->load->view('dataview.php',$data);
	}
}
