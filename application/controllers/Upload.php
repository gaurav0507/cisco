<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
	
	   protected $data;
	   const BULK_UPLOAD_SHEET_COLS = 4;
	   public function __construct()
	   {
		   parent::__construct();
           date_default_timezone_get('Asia/kolkata');
		   
		   $this->load->library('session');
           $this->load->helper('url');
		   $this->load->library('form_validation');
		   $this->load->model(array('temp_model'));
	   }

	public function import()
	{
		if($this->input->post('submit'))
        {
		   $file_name = str_replace(' ', '_', $_FILES['filedata']['name']);
           $new_file_name = time()."_".$file_name;
           $_FILES['filedata']['name'] = $new_file_name;
           $upload_path  = './uploads/';
		   $config['upload_path'] = './uploads/';
           $config['file_ext_tolower'] = TRUE;
           $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB*2000;
           $config['allowed_types'] = '*';
           $config['remove_spaces'] = TRUE;
           $config['overwrite'] = FALSE;
		   $config['file_name'] = $new_file_name;
           $this->load->library('upload', $config);
           $this->upload->initialize($config);
		   if(!$this->upload->do_upload('filedata'))
		   {
			  $data['fail'][] = $this->upload->display_errors();
              //print_r($data['error']);
			  $this->session->set_flashdata('message', array('message' =>$data['error'],'class' =>'error_strings'));
			  redirect('Welcome/index');
		   }
		   else
		   {   
               $this->load->library('Excel_reader',array('file_name'=>$upload_path.$new_file_name));
               $excel_handler = $this->excel_reader->file_handler;
               $excel_data =  $excel_handler->rows();
			   if(!empty($excel_data))
			   {
				  unset($excel_data[0]);
                  $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));
				  if(!empty($excel_data))
				  {
					foreach ($excel_data as $key =>$value)
					{
                        if(count($value) < SELF::BULK_UPLOAD_SHEET_COLS){
							continue;
						}
                        $duplicateCheck = $this->temp_model->duplicateCheck(htmlentities(trim($value[0])),htmlentities(trim($value[2])),htmlentities(trim($value[1])),htmlentities(trim($value[3])));
						$routerdata = array('sapid'=> htmlentities(trim($value[0])),
									 'hostname'=> htmlentities(trim($value[1])),
									  'loopback'=> htmlentities(trim($value[2])),
									  'macaddress'=> htmlentities(trim($value[3])),
									  'duplicate_check'=>$duplicateCheck);
					$results = $this->temp_model->insert_temp($routerdata);
                    }
					if($results)
					{
						redirect('Welcome/temporarydata');
					}else{
						$this->session->set_flashdata('message',array('message'=>'While Inserting there is error','class'=>'error_strings'));
						redirect('Welcome/index');
					}
						
				  }else{
					$this->session->set_flashdata('message', array('message' =>'Excel sheet is blank','class' =>'error_strings'));
					redirect('Welcome/index');
				  }
			   }
			   else{
				   $this->session->set_flashdata('message', array('message' =>'Excel sheet is blank','class' =>'error_strings'));
				   redirect('Welcome/index');
			   }
			}
		}else{
			$this->session->set_flashdata('message', array('message' =>'Form Method Invalid','class' =>'error_strings'));
			redirect('Welcome/index');
		}
	}
	
	public function updateRouterDetails()
	{
		$sapid = $this->input->post('esapid');
		$hostname = $this->input->post('ehostname');
		$loopback = $this->input->post('eloopback');
		$macaddress = $this->input->post('emacaddress');
		$id = $this->input->post('eid');
		
		$duplicateCheck = $this->temp_model->duplicateCheck($sapid,$loopback,$hostname,$macaddress);
		if($duplicateCheck == 0)
		{
			$where = array('id'=>$id);
			$fields = array('sapid'=>$sapid,'hostname'=>$hostname,'loopback'=>$loopback,'macaddress'=>$macaddress,'duplicate_check'=>0);
			$results = $this->temp_model->insert_temp($fields,$where);
			if($results)
			{
				$data['success'] = true;
				$data['message'] = "update Router details";
				echo json_encode($data);
			}else{
				$data['success'] = false;
				$data['message'] = "unable to update Router details";
				echo json_encode($data);
			}
	    }else{
			$data['success'] = false;
			$data['message'] = "duplicate record";
			echo json_encode($data);
		}
	     
	}
	
	public function importRouterDetails()
	{
		$vaildCheck = $this->temp_model->getRouterValid();
		if($vaildCheck == true){
			
			$data['success'] = false;
			$data['message'] = "Invalid Record.Please Vaildate the Record First";
			echo json_encode($data);
		}
		else{
			$getRouterDetails = json_decode($this->temp_model->getRouterDetails(),true);
			if(!empty($getRouterDetails)){
				$arrayBatch =  array();
				foreach($getRouterDetails as $sendAfter) {
					 if($this->macaddress($sendAfter['macaddress'])== 1 && $this->ipv4($sendAfter['loopback'])==1 && $this->hostname($sendAfter['hostname'])==1 && !empty($sendAfter['hostname']) && !empty($sendAfter['sapid']) && !empty($sendAfter['loopback']) && !empty($sendAfter['macaddress']))
					 {
					   $errorchk[] = array('log'=>0);
					   $arrayBatch[] = array('sapid'=>$sendAfter['sapid'],'hostname'=>$sendAfter['hostname'],'loopback'=>$sendAfter['loopback'],'macaddress'=>$sendAfter['macaddress']);
					 }else{
						 $errorchk[] = array('log'=>1);
					 }
				}
				if(array_search('1', array_column($errorchk, 'log')) !== false) {
					$data['success'] = false;
				    $data['message'] = "Invalid Record.Please Validate the record";
					echo json_encode($data);
				}
				else {
						$results = $this->temp_model->insertMasterRecord($arrayBatch);
						if($results == true){
							$temp = $this->db->count_all('temp_table');
							$master = $this->db->count_all('master_table');
							if($temp == $master)
							{
								 $this->db->truncate('temp_table');
								 $data['success'] = true;
								 $data['message'] = "Record Inserted Successfully";
								 echo json_encode($data);
							}else{
								$this->db->truncate('master_table');
								$data['success'] = false;
								$data['message'] = "Error while matching";
								echo json_encode($data);
							}
						}
						else{
							$data['success'] = false;
							$data['message'] = "While Inserting Error";
							echo json_encode($data);
						}
				}
			}else{
				$data['success'] = false;
				$data['message'] = "Empty Excel Data";
				echo json_encode($data);
			}
		}
	}
	
	function macaddress($macaddress) {
	   if(preg_match('/^(?:(?:[0-9a-f]{2}[\:]{1}){5}|(?:[0-9a-f]{2}[-]{1}){5}|(?:[0-9a-f]{2}){5})[0-9a-f]{2}$/i',$macaddress))
	   {
		   return true;
	   }else{
		   return false;
	   }
	   //$macAddress = '2a:3b:2c:1d:44:23';
	}
	
	function ipv4($ipv4)
	{
		 if(preg_match('/^(?=\d+\.\d+\.\d+\.\d+$)(?:(?:25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]|[0-9])\.?){4}$/',$ipv4))
	   {
		   return true;
	   }else{
		   return false;
	   }
	   //$ipv4 = '192.68.35.35';
	}
	
	function hostname($hname)
	{
		//if(preg_match('/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/',$hname)){
		if(preg_match('/^[0-9]{1,14}$|^(?=.*[a-zA-Z])[a-zA-Z0-9]{1,14}$/',$hname)){	
			return true;
		}else{
			return false;
		}
		//$hname = 'INDMAHABOMAAA1';
	}
	
	function sapid($sapid)
	{
		//if(preg_match('/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/',$hname)){
		if(preg_match('/^[0-9]{1,18}$|^(?=.*[a-zA-Z])[a-zA-Z0-9]{1,18}$/',$sapid)){	
			return true;
		}else{
			return false;
		}
		//$sapid = 'INDMAHABOMAAAAAA01';
	}
}
