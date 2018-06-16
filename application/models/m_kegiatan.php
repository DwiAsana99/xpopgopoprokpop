<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kegiatan extends CI_Model 
{
       
    public function __construct()
    {
        parent::__construct();           
    }
    
    var $table_kegiatan = 'm_kegiatan';

	function get_value_autocomplete_kd_keg($search, $kd_urusan, $kd_bidang, $kd_prog){
		$this->db->select('Kd_Keg AS id, Ket_Kegiatan AS label');
		$this->db->where('Kd_Urusan', $kd_urusan);
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Prog', $kd_prog);
		$this->db->where('(Kd_Keg LIKE \'%'. $search .'%\' OR Ket_Kegiatan LIKE \'%'. $search .'%\')');
		$result = $this->db->get($this->table_kegiatan);
		return $result->result();
	}

	function get_keg($kd_urusan=NULL, $kd_bidang=NULL, $kd_prog=NULL, $modul=NULL, $id_modul=NULL){
		$this->db->select('Kd_Keg AS id, Ket_Kegiatan AS nama');

		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		if (!empty($kd_bidang)) {
			$this->db->where('Kd_Bidang', $kd_bidang);		
		}
		if (!empty($kd_prog)) {
			$this->db->where('Kd_Prog', $kd_prog);		
		}
		// if (!empty($modul)) {
		// 	$this->db->get($this->table_kegiatan);
		// 	$result = $this->db->query("SELECT kd_keg as id, ket_kegiatan as nama 
		// 		FROM m_kegiatan 
		// 		WHERE kd_urusan = '".$kd_urusan."' AND kd_bidang = '".$kd_bidang."' AND kd_prog = '".$kd_prog."' 
		// 		AND kd_keg NOT IN 
		// 		(SELECT kd_kegiatan FROM ".$modul." WHERE ".$id_modul." AND kd_urusan = '".$kd_urusan."' AND kd_bidang = '".$kd_bidang."' AND kd_program = '".$kd_prog."' AND kd_kegiatan IS NOT NULL)");
		// 	return $result->result();
		// }

		$result = $this->db->get($this->table_kegiatan);
		return $result->result();
	}    	
	function get_keg_one($kd_urusan=NULL, $kd_bidang=NULL, $kd_prog=NULL,$kd_keg = null){
		$this->db->select('Kd_Keg AS id, Ket_Kegiatan AS nama');

		if (!empty($kd_urusan)) {
			$this->db->where('Kd_Urusan', $kd_urusan);
		}
		if (!empty($kd_bidang)) {
			$this->db->where('Kd_Bidang', $kd_bidang);		
		}
		if (!empty($kd_prog)) {
			$this->db->where('Kd_Prog', $kd_prog);		
		}
		if (!empty($kd_keg)) {
			$this->db->where('Kd_Keg', $kd_keg);		
		}
		$result = $this->db->get($this->table_kegiatan);
		return $result->row();
	}    

	function get_data_kegiatan($kd_urusan=NULL, $kd_bidang=NULL, $kd_prog=NULL, $id=NULL){
		$this->db->select('m_kegiatan.id AS id_keg, m_kegiatan.*, m_program.Ket_Program, m_urusan.Nm_Urusan, m_bidang.Nm_Bidang');
		$this->db->join('m_urusan', 'm_kegiatan.kd_urusan = m_urusan.kd_urusan', 'inner');
		$this->db->join('m_bidang', 'm_kegiatan.kd_urusan = m_bidang.kd_urusan AND m_kegiatan.kd_bidang = m_bidang.kd_bidang', 'inner');
		$this->db->join('m_program', 'm_kegiatan.kd_urusan = m_program.kd_urusan AND m_kegiatan.kd_bidang = m_program.kd_bidang AND m_kegiatan.kd_prog = m_program.kd_prog', 'inner');
		
		if (!empty($kd_urusan) && !empty($kd_bidang) && !empty($kd_prog)) {
			$this->db->where('m_kegiatan.kd_urusan', $kd_urusan);
			$this->db->where('m_kegiatan.kd_bidang', $kd_bidang);
			$this->db->where('m_kegiatan.kd_prog', $kd_prog);
		}

		if (!empty($id)) {
			$this->db->where('m_kegiatan.id', $id);
		}

		$this->db->order_by('m_kegiatan.kd_urusan');
		$this->db->order_by('m_kegiatan.kd_bidang');
		$this->db->order_by('m_kegiatan.kd_prog');
		$this->db->order_by('m_kegiatan.kd_keg');

		return $this->db->get('m_kegiatan');
	}

}
?>