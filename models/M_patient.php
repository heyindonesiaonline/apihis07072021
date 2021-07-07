<?php

class M_patient extends CI_Model{


    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->secretkey_server = $this->config->item('secretkey_server');
        $this->base_url = $this->config->item('base_url');
        $this->load->model('M_base','base');
    }


    function list_patient($userid,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_login = $this->db->query($sql_login)->row();

        if (isset($exec_login)) {

                $get_patient = $this->db->query("SELECT * FROM patients")->result();
                return array('Status'=>'Success',
                        'Message'=>'Success List Patient',
                        'Data' => $get_patient,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }

    function current_patient($userid,$secretkey){
        date_default_timezone_set('Asia/Jakarta');
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_login = $this->db->query($sql_login)->row();

        if (isset($exec_login)) {

                //cek appointment today
                $date_now = date("Y-m-d ");
                $time_now = date("h:i:s");
                $sql_schedule = "SELECT id,start_time,end_time FROM schedule sch WHERE sch.end_time >= '$time_now' AND sch.start_time <= '$time_now' AND userid = '$userid' AND status = 1 ";
                $get_schedule = $this->db->query($sql_schedule)->row();
                if (isset($get_schedule)) {
                    $start_time = $get_schedule->start_time;
                    $end_time = $get_schedule->end_time;

                    $datetime_start = $date_now.' '.$start_time;
                    $datetime_end = $date_now.' '.$end_time;
                    $sql_appointment = "SELECT id,patient_name,gender,email,mobileno,message,0 AS age,0 AS dob, 0 AS height FROM appointment WHERE date >= '$datetime_start' AND date <= '$datetime_end' AND doctor = '$userid' ORDER BY id ASC LIMIT 1 ";
                    $get_appointment = $this->db->query($sql_appointment)->row();

                    if (isset($get_appointment)) {
                        return array('Status'=>'Success',
                                'Message'=>'Success Data Patient',
                                'Data' => array(
                                    'id' => $get_appointment->id,
                                    'patient_name' => $get_appointment->patient_name,
                                    'gender' => $get_appointment->gender,
                                    'email' => $get_appointment->email,
                                    'mobileno' => $get_appointment->mobileno,
                                    'message' => $get_appointment->message,
                                    'age' => $get_appointment->age,
                                    'dob' => $get_appointment->dob,
                                    'height' => $get_appointment->height 
                                ),
                                'ResponseCode'=>'00'
                        );
                    }else{
                        return array(
                            'Status' => 'Failed',
                            'Message' => 'Not Found Data',
                            'ResponseCode' => '02 '.$sql_appointment 
                        );
                    }
                }else{
                    return array(
                        'Status' => 'Failed',
                        'Message' => 'Not Found Data',
                        'ResponseCode' => '02' 
                    );
                } 
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }



    
   



}

?>