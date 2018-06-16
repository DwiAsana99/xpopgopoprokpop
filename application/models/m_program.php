<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_program extends CI_Model 
{
       
    public function __construct()
    {
        parent::__construct();           
    }
    
    var $table_program = 'm_program';

	function get_value_autocomplete_kd_prog($search, $kd_urusan, $kd_bidang){
		$this->db->select('Kd_Prog AS id, Ket_Program AS label');
		$this->db->where('Kd_Urusan', $kd_urusan);
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('(Kd_Prog LIKE \'%'. $search .'%\' OR Ket_Program LIKE \'%'. $search .'%\')');
		$result = $this->db->get($this->table_program);
		return $result->result();
	}	

	function get_prog($kd_urusan=NULL, $kd_bidang=NULL, $modul=NULL, $id_modul=NULL){
		$this->db->select('Kd_Prog AS id, Ket_Program AS nama');
		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		if (!empty($kd_bidang)) {
			$this->db->where('Kd_Bidang', $kd_bidang);		
		}
		// if (!empty($modul)) {
		// 	$this->db->get($this->table_program);
		// 	$result = $this->db->query("SELECT kd_prog as id, ket_program as nama
		// 	FROM m_program
		// 	WHERE kd_urusan = '".$kd_urusan."' AND kd_bidang = '".$kd_bidang."'
		// 	AND kd_prog NOT IN 
		// 	(SELECT kd_program FROM ".$modul." WHERE ".$id_modul." AND kd_urusan = '".$kd_urusan."' AND kd_bidang = '".$kd_bidang."')");
		// 	return $result->result();
		// }
		$result = $this->db->get($this->table_program);
		return $result->result();
	}
	function get_prog_one($kd_urusan=NULL, $kd_bidang=NULL,$kd_program){
		$this->db->select('Kd_Prog AS id, Ket_Program AS nama');
		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		if (!empty($kd_bidang)) {
			$this->db->where('Kd_Bidang', $kd_bidang);		
		}	
		if (!empty($kd_program)) {
			$this->db->where('Kd_Prog', $kd_program);		
		}	
		$result = $this->db->get($this->table_program);
		return $result->row();
	}

	function get_data_program($kd_urusan=NULL, $kd_bidang=NULL, $id=NULL){
		$this->db->select('m_program.id AS id_prog, m_program.*, m_urusan.Nm_Urusan, m_bidang.Nm_Bidang');
		$this->db->join('m_urusan', 'm_program.kd_urusan = m_urusan.kd_urusan', 'inner');
		$this->db->join('m_bidang', 'm_program.kd_bidang = m_bidang.kd_bidang', 'inner');
		if (!empty($kd_urusan)) {
			$this->db->where('m_urusan.kd_urusan', $kd_urusan);
		}
		if (!empty($kd_bidang) && !empty($kd_urusan)) {
			$this->db->where('m_bidang.kd_urusan', $kd_urusan);
			$this->db->where('m_bidang.kd_bidang', $kd_bidang);
		}

		if (!empty($id)) {
			$this->db->where('m_program.id', $id);
		}

		$this->db->order_by('m_program.kd_urusan');
		$this->db->order_by('m_program.kd_bidang');
		$this->db->order_by('m_program.kd_prog');

		return $this->db->get('m_program');
	}


}
?>