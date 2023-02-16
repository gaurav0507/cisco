<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class temp_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
		$this->tableName = 'temp_table';
		$this->masterTableName = 'master_table';
		    
    }
	
	public function insert_temp($arrdata,$arrwhere = array())
    {
    	if(!empty($arrwhere))
    	{
            foreach ($arrwhere as $field => $value)
    		{
    			$this->db->where($field,$value);
    		}
      	    $result = $this->db->update($this->tableName, $arrdata);
		    return $result;
    	}else{
    		    $this->db->insert($this->tableName, $arrdata);
				return $this->db->insert_id();
    	}
    	
    	
    }
	
	public function duplicateCheck($sapid,$loopback,$hostname,$macaddress)
	{
		$this->db->select('*');
		$this->db->from('temp_table');
		$this->db->where("sapid",$sapid);
		$this->db->where("loopback",$loopback);
		$this->db->where("hostname",$hostname);
		$this->db->where("macaddress",$macaddress);
		$query = $this->db->get();
		if($query->num_rows() > 0) 
		{
		    return 1;
		}else{
		   return 0;
		}
	}
	
	public function select_temporary()
	{
		$this->db->select('*');
		$this->db->from('temp_table');
		$this->db->order_by("id","asc");
		$query = $this->db->get();
		if($query->num_rows() > 0) 
		{
		    foreach ($query->result() as $row) 
			{
			  $data[] = $row;
			}
		    return json_encode($data);
		}
		   return false;
	}
	
	function updateRouterDetails($id){
        $data=array($field => $value);
        $this->db->where('id',$id);
        $this->db->update('temp_table',$data);
    }
	
	public function getRouterValid()
	{
		$this->db->select('*');
		$this->db->from('temp_table');
		$this->db->where("duplicate_check",1);
		$query = $this->db->get();
		if($query->num_rows() > 0) 
		{
		    return true;
		}else{
		   return false;
		}
	}
	
	public function getRouterDetails(){
		$this->db->select('*');
		$this->db->from('temp_table');
		$this->db->order_by("id","asc");
		$query = $this->db->get();
		if($query->num_rows() > 0) 
		{
		    foreach ($query->result() as $row) 
			{
			  $data[] = $row;
			}
		    return json_encode($data);
		}
		   return false;
	}
	
	public function insertMasterRecord($Mydata)
	{
		$batchsuccess = $this->db->insert_batch('master_table', $Mydata);
		if($batchsuccess > 0){
		   $test = TRUE;
		}else{
			$test = FALSE;
		}
	   return $test;
	}
}