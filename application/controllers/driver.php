<?php

class Driver extends CI_Controller {

    public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
		$this->load->helper('url');
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}
    
    
    public function session(){
        echo "<pre>";
        var_dump($_SESSION);
        //var_dump($this->session->userdata('active_menu'));
        echo "</pre>";
    }

    function ceksqlserver(){
        $serverName = "202.52.11.227\SERVER";
        // $connectionInfo = array("Database"=>"latihan", "UID"=>"admino", "PWD"=>"admin");
        // $conn = sqlsrv_connect($serverName, $connectionInfo);
        // if( $conn ) {
        //      echo "Connection established.<br />";
        // }else{
        //      echo "Connection could not be established.<br />";
        //      die( print_r( sqlsrv_errors(), true));
        // }

        try {
            $conn = new PDO ("dblib:host=$serverName;dbname=latihan", "admino", "admin");
            if ($conn) {
            	echo "<B>BERHASIL CUYYY</B> <BR>";
            }
        } catch (PDOException $e) {
            $logsys .= "Failed to get DB handle: " . $e->getMessage() . "\n";
            echo $logsys;
        }

        $stmt = $conn->prepare("select * from ta_program");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
		print_r($row);
		}
		unset($conn); unset($stmt);

    }


}
?>