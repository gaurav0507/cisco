<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Ajax extends CI_Controller {

   public function __construct() {
      parent::__construct();
      $this->load->database();
	  $this->load->model('temp_model');

		
		
   }

   public function get_items()
   {
      $draw = intval($this->input->get("draw"));
      $start = intval($this->input->get("start"));
      $length = intval($this->input->get("length"));


      $query = $this->db->get("temp_table");


      $data = [];


      foreach($query->result() as $r) {
           $data[] = array(
                $r->id,
                $r->sapid,
                $r->hostname,
				$r->loopback,
				$r->macaddress
           );
      }


      $result = array(
               "draw" => $draw,
                 "recordsTotal" => $query->num_rows(),
                 "recordsFiltered" => $query->num_rows(),
                 "data" => $data
            );


      echo json_encode($result);
      exit();
   }
   
   public function delete($id)
   {
       $this->db->delete('temp_table', array('id' => $id));
       echo 'Deleted successfully.';
   }
   
   public function updateRouterDetails(){
		// POST values
	    $id = $this->input->post('id');
	    $field = $this->input->post('field');
	    $value = $this->input->post('value');

	    // Update records
	    $this->temp_model->updateRouterDetails($id,$field,$value);

	    echo 1;
	    exit;
	}
}