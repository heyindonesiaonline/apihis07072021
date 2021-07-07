<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Patient extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('M_patient','pat');
    }
    
    function listpatient_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->list_patient($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function currentpatient_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->current_patient($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    
    
}
?>