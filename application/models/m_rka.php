<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_rka extends CI_Model
{
	var $table_rka = 't_rka';
	var $table = 'tx_rka';
	var $table_urusan = 'm_urusan';
	var $table_bidang = 'm_bidang';
	var $table_program = 'm_program';
	var $table_kegiatan = 'm_kegiatan';
	var $primary_rka = 'id';

	var $table_program_kegiatan = 'tx_rka_prog_keg';
	var $table_indikator_program = 'tx_rka_indikator_prog_keg';
	var $is_program = 1;
	var $is_kegiatan = 2;

	var $id_status_baru = "1";
	var $id_status_send = "2";
	var $id_status_revisi = "3";
	var $id_status_approved = "4";

	var $history_renja = 'tx_rka_history';


	function count_jendela_kontrol($id_skpd,$ta){
		if($this->session->userdata("id_skpd") > 100){
			$id_skpd = $this->session->userdata("id_skpd");
			$search = "AND tx_rka_prog_keg.id_skpd in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')";
		}else {
			$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
			if ($id_skpd == $kode_unit) {
				$search = "AND tx_rka_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')";
				$search2 = "id_skpd IN (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')";
			}else {
				$search = "AND (tx_rka_prog_keg.id_skpd = '$id_skpd' OR tx_rka_prog_keg.id_skpd = '$kode_unit')";
				$search2 = "id_skpd = '$id_skpd'";
			}
		}
		$query = "SELECT
						SUM(IF(tx_rka_prog_keg.id_status=?, 1, 0)) as baru,
						SUM(IF(tx_rka_prog_keg.id_status>=?, 1, 0)) as kirim,
						SUM(IF(tx_rka_prog_keg.id_status>?, 1, 0)) as proses,
						SUM(IF(tx_rka_prog_keg.id_status=?, 1, 0)) as revisi,
						SUM(IF(tx_rka_prog_keg.id_status>=?, 1, 0)) as veri
					FROM
						tx_rka_prog_keg
					WHERE
						tahun = ?".$search ." AND
						((tx_rka_prog_keg.is_prog_or_keg =2 and nominal >0) or
							id in (select parent from tx_rka_prog_keg where ".$search2." and tahun ='$ta' and nominal>0))"
						;
		$data = array(
					$this->id_status_baru,
					$this->id_status_send,
					$this->id_status_send,
					$this->id_status_revisi,
					$this->id_status_approved,
					$ta,$id_skpd, $this->is_kegiatan);
		$result = $this->db->query($query, $data);
		// print_r($this->db->last_query());
		// exit();
		return $result->row();
	}

	private function add_history_renja($id_rka, $status, $keterangan=NULL){
		$history = array('id_rka' => $id_rka, 'id_status' => $status, 'create_date'=>date("Y-m-d H:i:s"),
		'user'=>$this->session->userdata('username'));
		if (!empty($keterangan)) {
			$history['keterangan'] = $keterangan;
		}
		$result = $this->db->insert($this->history_renja, $history);
		return $result;
	}

	function kirim_kendali_renja($id_skpd,$ta) {
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();
		$data_renja = $this->get_rka_skpd($id_skpd,$ta);
		//echo $this->db->last_query();
		foreach ($data_renja as $renja){
			if($renja->id_status=='1'){
				$this->update_status($renja->id,'2');
				$this->add_history_renja($renja->id, $this->id_status_send);
			}

			/*else if ($renja->id_status=='3'){
				$this->update_status($renja->id,'2');
			}*/
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_one_rka_veri($id){
		$query = "SELECT tx_rka_prog_keg.* FROM tx_rka_prog_keg WHERE id=?";
		$result = $this->db->query($query, array($id));
		return $result->row();
	}

	function disapprove_renja($id_skpd,$ta) {
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();
		$data_renja = $this->get_rka_skpd($id_skpd,$ta);
		//echo $this->db->last_query();
		foreach ($data_renja as $renja){
			if($renja->id_status=='2'){
				$this->update_status($renja->id,'3');
				$this->add_history_renja($renja->id, $this->id_status_revisi,'data tidak valid keseluruhan');
			}

			/*else if ($renja->id_status=='3'){
				$this->update_status($renja->id,'2');
			}*/
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function approved_renja($id){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array('id_status'=>$this->id_status_approved));
		$this->add_history_renja($id, $this->id_status_approved);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function not_approved_renja($id, $ket){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array('id_status'=>$this->id_status_revisi));
		$this->add_history_renja($id, $this->id_status_approved, $ket);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}


	function get_rka_skpd($id_skpd,$ta)
    {
    	$sql="
			SELECT * FROM ".$this->table_program_kegiatan."
			WHERE id_skpd = ?
			AND tahun = ?
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}

			return NULL;
    }

	function update_status($id, $id_status)
     {
		$this->db->set('id_status',$id_status);
		$this->db->where('id', $id);
		$result=$this->db->update('tx_rka_prog_keg');
		return $result;
	 }

	function get_rka($id_skpd,$ta)
    {
    	$sql="
			SELECT * FROM ".$this->table."
			WHERE id_skpd = ?
			AND tahun = ?
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
    }
	function get_all_program($id_skpd,$ta){
		if ($this->session->userdata("id_skpd") > 100) {
			$id_skpd = $this->session->userdata("id_skpd");
			$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			WHERE `id_skpd` in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')
			AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program
			and id  in (select parent from tx_rka_prog_keg where id_skpd='$id_skpd' and nominal>0 and tahun = '$ta')
			ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";

			$result = $this->db->query($query);
		}else {
			$cek = $this->m_skpd->get_kode_unit($id_skpd);
			if ($cek == $id_skpd) {
				$query = "SELECT * FROM (SELECT *, id AS id_a,
				(SELECT SUM(nominal) FROM ".$this->table_program_kegiatan." WHERE parent = id_a) AS nom1,
				(SELECT SUM(nominal_thndpn) FROM ".$this->table_program_kegiatan." WHERE parent = id_a) AS nom2
				FROM ".$this->table_program_kegiatan." WHERE `id_skpd` IN 
				(SELECT id_skpd FROM m_skpd WHERE kode_unit = '".$id_skpd."')
				AND `tahun` = '".$ta."' 
				AND `is_prog_or_keg` = ".$this->is_program." 
				) AS tref
				WHERE (tref.nom1 > 0)
				ORDER BY `kd_urusan` ASC, `kd_bidang` ASC, `kd_program` ASC";

				$result = $this->db->query($query);
			}else{

				$query = "SELECT * FROM (SELECT *, id AS id_a,
				(SELECT SUM(nominal) FROM ".$this->table_program_kegiatan." WHERE parent = id_a) AS nom1,
				(SELECT SUM(nominal_thndpn) FROM ".$this->table_program_kegiatan." WHERE parent = id_a) AS nom2
				FROM ".$this->table_program_kegiatan." WHERE `id_skpd` = '".$id_skpd."' AND `tahun` = '".$ta."' 
				AND `is_prog_or_keg` = ".$this->is_program." 
				) AS tref
				WHERE (tref.nom1 > 0)
				ORDER BY `kd_urusan` ASC, `kd_bidang` ASC, `kd_program` ASC";

				$result = $this->db->query($query);
			}
			// $query = "SELECT * FROM (`$this->table_program_kegiatan`)
			// WHERE `id_skpd` = '$id_skpd'
			// AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program
			// and id  in (select parent from tx_rka_prog_keg where id_skpd='$id_skpd' and nominal>0 and tahun = '$ta')
			// ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";

			// $result = $this->db->query($query);


			// $cek = $this->m_skpd->get_kode_unit($id_skpd);
			// if ($cek == $id_skpd) {
			// 	$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			// 	WHERE `id_skpd` in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')
			// 	AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program
			// 	ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";
			//
			// 	$result = $this->db->query($query);
			// }else {
			// 	$this->db->select($this->table_program_kegiatan.".*");
			// 	$this->db->where('id_skpd', $id_skpd);
			// 	$this->db->where('tahun', $ta);
			// 	$this->db->where('is_prog_or_keg', $this->is_program);
			// 	$this->db->from($this->table_program_kegiatan);
			// 	$this->db->order_by('kd_urusan', 'asc');
			// 	$this->db->order_by('kd_bidang', 'asc');
			// 	$this->db->order_by('kd_program', 'asc');
			//
			// 	$result = $this->db->get();
			// }
		}
		return $result->result();
	}

	function insert_rka($id_skpd, $ta){
		$created_date = date("Y-m-d H:i:s");
		$created_by = $this->session->userdata('username');
		$this->db->set('id_skpd', $id_skpd);
		$this->db->set('tahun', $ta);
		$this->db->set('created_date', $created_date);
		$this->db->set('created_by', $created_by);
		$this->db->insert('tx_rka');
		return $this->db->insert_id();
	}

	function get_rka_belanja_per_tahun($id, $is_tahun){
		//------- query by deesudi
			$query = $this->db->query("SELECT id ,tahun,
							kode_sumber_dana AS kode_sumber_dana,(
								SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
							) AS sumberDana,
							kode_jenis_belanja AS kode_jenis_belanja, (
								SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
							) AS jenis,
							kode_kategori_belanja AS kode_kategori_belanja, (
								SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
							) AS kategori,
							kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
								SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
							) AS subkategori,
							kode_belanja AS kode_belanja,(
								SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
							) AS belanja,
							uraian_belanja,detil_uraian_belanja,volume,satuan,nominal_satuan,subtotal,is_tahun_sekarang,id_keg
							FROM tx_rka_belanja_kegiatan
							WHERE is_tahun_sekarang = '$is_tahun' AND id_keg = '$id'
							ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC");
		return $query->result();
	}

	function get_rka_belanja_per_tahun221($tahun_pilihan, $is_tahun, $idK){
		//------- query by deesudi
			$query = $this->db->query("SELECT id ,tahun,
							kode_sumber_dana AS kode_sumber_dana,(
								SELECT nama FROM m_sumberdana WHERE id_sumberdana = kode_sumber_dana
							) AS sumberDana,
							kode_jenis_belanja AS kode_jenis_belanja, (
								SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
							) AS jenis,
							kode_kategori_belanja AS kode_kategori_belanja, (
								SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
							) AS kategori,
							kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
								SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
							) AS subkategori,
							kode_belanja AS kode_belanja,(
								SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
							) AS belanja,(
								SELECT Kd_Fungsi FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang
							) AS kode_fungsi,(
								SELECT Nm_Fungsi FROM m_fungsi WHERE Kd_Fungsi = kode_fungsi
							) AS nama_fungsi,(
								SELECT Nm_Urusan FROM m_urusan WHERE Kd_Urusan = kode_urusan
							) AS nama_urusan,(
								SELECT Ket_Program FROM m_program WHERE Kd_Urusan = kode_urusan  AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program
							) AS nama_program,(
								SELECT Ket_Kegiatan FROM m_kegiatan WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program AND Kd_Keg = kode_kegiatan
							) AS nama_kegiatan,(
							SELECT Nm_Bidang FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang
							) AS nama_bidang,(
							SELECT id FROM m_tahun_anggaran WHERE tahun_anggaran = '$tahun_pilihan'
							) AS tahun_anggaran,(
							SELECT nominal FROM `tx_rka_prog_keg` WHERE id = '$idK'
							) AS nominal_tahun,

							uraian_belanja,detil_uraian_belanja,volume,satuan,nominal_satuan,subtotal,tahun,id_keg , kode_urusan , kode_bidang , kode_program, kode_kegiatan
							FROM tx_rka_belanja_kegiatan
							WHERE tahun = '$tahun_pilihan' and is_tahun_sekarang = '$is_tahun' and id_keg = '$idK'
							ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC");
		return $query->result();
	}

	function get_indikator_keluaran($ta, $idK){
			$query = $this->db->query("SELECT * FROM `tx_rka_indikator_prog_keg` WHERE   `id_prog_keg` = '$idK'");
		return $query->result();
	}

	function get_indikator_capaian( $idP){
			$query = $this->db->query("SELECT * FROM `tx_rka_indikator_prog_keg` WHERE   `id_prog_keg` = '$idP'");
		return $query->result();
	}
	function get_nominal_rka( $idK, $is_tahun_sekarang){

		if ($is_tahun_sekarang == 1) {
			$query = $this->db->query("SELECT  nominal_thndpn ,
									CASE WHEN
									(SELECT
										 nominal FROM tx_rka_prog_keg WHERE  id = '$idK' AND tahun  =  tahun-1
									) IS NULL THEN 0 ELSE
									(SELECT
										 nominal FROM tx_rka_prog_keg WHERE  id = '$idK' AND tahun  =  tahun-1
									) END AS nominal_min , nominal
									FROM `tx_rka_prog_keg` WHERE id = '$idK' ");
		}else{
			$query = $this->db->query("SELECT  nominal_thndpn as nominal
									 , nominal as nominal_min , 0 as nominal_thndpn
									FROM `tx_rka_prog_keg` WHERE id = '$idK' ");
		}

		return $query->result();
	}

	function import_from_renja($id_skpd, $ta){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();


		# For program #
		$query="SELECT
					$ta AS tahun,
					t_ppas_prog_keg.id AS id_renja,
					is_prog_or_keg,
					kd_urusan,
					kd_bidang,
					kd_program,
					kd_kegiatan,
					nama_prog_or_keg,
					lokasi,
					lokasi as lokasi_thndpn,
					penanggung_jawab,
					t_ppas_prog_keg.id_skpd,
					nominal,
					nominal_thndpn,
					t_ppas_prog_keg.id_prog_rpjmd
				FROM t_ppas_prog_keg WHERE t_ppas_prog_keg.is_prog_or_keg=1 AND tahun=$ta AND t_ppas_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = ?)";
		$result = $this->db->query($query, $id_skpd);
		$rka_baru = $result->result_array();

		foreach ($rka_baru as $row) {
			$this->db->insert("tx_rka_prog_keg", $row);

			$new_id = $this->db->insert_id();

			$query = "INSERT INTO tx_rka_indikator_prog_keg(id_prog_keg, indikator, indikator_thndpn, satuan_target, satuan_target_thndpn, status_indikator, status_indikator_thndpn, kategori_indikator, kategori_indikator_thndpn, target, target_thndpn, id_indikator_renja)
			SELECT ?, indikator,indikator, satuan_target,satuan_target, status_indikator, status_indikator, kategori_indikator, kategori_indikator, target, target_thndpn, t_ppas_indikator_prog_keg.id FROM t_ppas_indikator_prog_keg WHERE id_prog_keg=?";
			$result = $this->db->query($query, array($new_id, $row['id_renja']));

			# For kegiatan #
			$query="SELECT
					$ta AS tahun,
					t_ppas_prog_keg.id AS id_renja,
					is_prog_or_keg,
					kd_urusan,
					kd_bidang,
					kd_program,
					kd_kegiatan,
					nama_prog_or_keg,
					lokasi,
					lokasi AS lokasi_thndpn,
					penanggung_jawab,
					t_ppas_prog_keg.id_skpd,
					nominal,
					nominal_thndpn,
					catatan,
					catatan_thndpn,
					t_ppas_prog_keg.id_prog_rpjmd,
					? AS parent
				FROM t_ppas_prog_keg WHERE t_ppas_prog_keg.is_prog_or_keg=2 AND tahun=$ta AND t_ppas_prog_keg.parent=? AND t_ppas_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = ?)";
			$result = $this->db->query($query, array($new_id, $row['id_renja'], $id_skpd));
			$kegiatan_rka_baru = $result->result_array();

			foreach ($kegiatan_rka_baru as $row1) {
				$id_renja_nya = $row1['id_renja'];

				$this->db->insert("tx_rka_prog_keg", $row1);
				$new_id = $this->db->insert_id();

				$query = "INSERT INTO tx_rka_indikator_prog_keg(id_prog_keg, indikator, indikator_thndpn, satuan_target, satuan_target_thndpn, status_indikator, status_indikator_thndpn, kategori_indikator, kategori_indikator_thndpn, target, target_thndpn, id_indikator_renja)
											SELECT ?, indikator,indikator, satuan_target,satuan_target, status_indikator, status_indikator, kategori_indikator, kategori_indikator, target, target_thndpn, t_ppas_indikator_prog_keg.id
											FROM t_ppas_indikator_prog_keg WHERE id_prog_keg=?";
				$result = $this->db->query($query, array($new_id, $row1['id_renja']));

				//belanja kegiatan
				$query2 = "
							INSERT INTO `tx_rka_belanja_kegiatan`
							(
							`id_renja`,
							`tahun`,
							`kode_urusan`,
							`kode_bidang`,
							`kode_program`,
							`kode_kegiatan`,
							`kode_sumber_dana`,
							`kode_jenis_belanja`,
							`kode_kategori_belanja`,
							`kode_sub_kategori_belanja`,
							`kode_belanja`,
							`uraian_belanja`,
							`detil_uraian_belanja`,
							`volume`,
							`satuan`,
							`nominal_satuan`,
							`subtotal`,
							`created_date`,
							`is_tahun_sekarang`,
							`id_status_rka`,
							`id_keg`)
							SELECT
							 '$id_renja_nya',
							 tahun,
							 `kode_urusan`,
 							 `kode_bidang`,
 							 `kode_program`,
 							 `kode_kegiatan`,
 							 `kode_sumber_dana`,
 							 `kode_jenis_belanja`,
 							 `kode_kategori_belanja`,
 							 `kode_sub_kategori_belanja`,
 							 `kode_belanja`,
 							 `uraian_belanja`,
 							 `detil_uraian_belanja`,
 							 `volume`,
 							 `satuan`,
 							 `nominal_satuan`,
 							 `subtotal`,
							 `created_date`,
 							 `is_tahun_sekarang`,
 							 `id_status_rka`,
 							 '$new_id'
							FROM t_ppas_belanja_kegiatan
							WHERE  id_keg = '$id_renja_nya'
					 ";
					 $result2 =  $this->db->query($query2);


			}
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_indikator_prog_keg($id, $return_result=TRUE, $satuan=FALSE){
		$this->db->select($this->table_indikator_program.".*, satuan_target as nama_value");
		$this->db->where('id_prog_keg', $id);
		// $this->db->where(('(target > 0 OR target_thndpn > 0)'));
		$this->db->from($this->table_indikator_program);

		if ($satuan) {
			// $this->db->select("m_lov.nama_value");
			$this->db->select("m_status_indikator.nama_status_indikator");
			$this->db->select("m_kategori_indikator.nama_kategori_indikator");
			// $this->db->join("m_lov",$this->table_indikator_program.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
			$this->db->join("m_status_indikator",$this->table_indikator_program.".status_indikator = m_status_indikator.kode_status_indikator","inner");
			$this->db->join("m_kategori_indikator",$this->table_indikator_program.".kategori_indikator = m_kategori_indikator.kode_kategori_indikator","inner");
		}

		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_all_kegiatan($id, $id_skpd, $ta){
		if ($this->session->userdata("id_skpd") > 100) {
			$id_skpd = $this->session->userdata("id_skpd");
			$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			WHERE `id_skpd` in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')
			AND `tahun` = '$ta' AND parent = $id
			AND `is_prog_or_keg` = $this->is_kegiatan and nominal>0
			ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc, `kd_kegiatan` asc";

			$result = $this->db->query($query);

		}else {
			$cek = $this->m_skpd->get_kode_unit($id_skpd);
			if ($cek == $id_skpd) {
				$query = "SELECT * FROM (`$this->table_program_kegiatan`)
				WHERE `id_skpd` in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')
				AND `tahun` = '$ta' AND parent = $id and nominal>0
				AND `is_prog_or_keg` = $this->is_kegiatan
				ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc, `kd_kegiatan` asc";

				$result = $this->db->query($query);
			}else {
				$this->db->select($this->table_program_kegiatan.".*");
				$this->db->where('id_skpd', $id_skpd);
				$this->db->where('tahun', $ta);
				$this->db->where('parent', $id);
				$this->db->where('is_prog_or_keg', $this->is_kegiatan);
				$this->db->where('nominal >', '0');
				
				$this->db->from($this->table_program_kegiatan);
				$this->db->order_by('kd_urusan','asc');
				$this->db->order_by('kd_bidang','asc');
				$this->db->order_by('kd_program','asc');
				$this->db->order_by('kd_kegiatan','asc');

				$result = $this->db->get();
			}
		}
		return $result->result();
	}

	function get_one_kegiatan($id_program=NULL, $id, $detail=FALSE){
		if (!empty($id_program)) {
			$this->db->where('parent', $id_program);
		}

		if ($detail) {
			$this->db->select($this->table_program_kegiatan.".*");
			$this->db->select("nama_skpd");

			$this->db->join("m_skpd", $this->table_program_kegiatan.".id_skpd = m_skpd.id_skpd","inner");

			$this->db->select("m_urusan.Nm_Urusan");
			$this->db->select("m_bidang.Nm_Bidang");
			$this->db->select("m_program.Ket_Program");
			$this->db->join("m_urusan",$this->table_program_kegiatan.".kd_urusan = m_urusan.Kd_Urusan","inner");
			$this->db->join("m_bidang",$this->table_program_kegiatan.".kd_urusan = m_bidang.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_bidang.Kd_Bidang","inner");
			$this->db->join("m_program",$this->table_program_kegiatan.".kd_urusan = m_program.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_program.Kd_Bidang AND ".$this->table_program_kegiatan.".kd_program = m_program.Kd_Prog","inner");
		}

		$this->db->where($this->table_program_kegiatan.'.id', $id);
		$this->db->from($this->table_program_kegiatan);
		$result = $this->db->get();
		return $result->row();
	}

	function get_one_program($id=NULL, $detail=FALSE){
		if (!empty($id)) {
			$this->db->where($this->table_program_kegiatan.'.id', $id);
		}

		if ($detail) {
			$this->db->select($this->table_program_kegiatan.".*");
			$this->db->select("nama_skpd");

			$this->db->join($this->table, $this->table_program_kegiatan.".id = ".$this->table.".id","inner");
			$this->db->join("m_skpd", $this->table.".id_skpd = m_skpd.id_skpd","inner");

			$this->db->select("m_urusan.Nm_Urusan");
			$this->db->select("m_bidang.Nm_Bidang");
			$this->db->select("m_program.Ket_Program");
			$this->db->join("m_urusan",$this->table_program_kegiatan.".kd_urusan = m_urusan.Kd_Urusan","inner");
			$this->db->join("m_bidang",$this->table_program_kegiatan.".kd_urusan = m_bidang.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_bidang.Kd_Bidang","inner");
			$this->db->join("m_program",$this->table_program_kegiatan.".kd_urusan = m_program.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_program.Kd_Bidang AND ".$this->table_program_kegiatan.".kd_program = m_program.Kd_Prog","inner");
		}

		$this->db->where($this->table_program_kegiatan.'.id', $id);
		$this->db->from($this->table_program_kegiatan);
		$result = $this->db->get();
		return $result->row();
	}

	function add_program_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program);
		$data = $this->global_function->add_array($data, $add);

		$this->db->insert($this->table_program_kegiatan, $data);

		$id = $this->db->insert_id();
		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value, 'satuan_target' => $satuan_target[$key],
			'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key],'target_thndpn' => $target_thndpn[$key]));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_program_skpd($data, $id_program, $indikator, $id_indikator_program, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_program);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_program[$key])) {
				$this->db->where('id', $id_indikator_program[$key]);
				$this->db->where('id_prog_keg', $id_program);
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key],
				'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key], 'target_thndpn' => $target_thndpn[$key]));
				unset($id_indikator_program[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_program, 'indikator' => $value, 'satuan_target' => $satuan_target[$key],
				'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key], 'target_thndpn' => $target_thndpn[$key], ));
			}
		}

		if (!empty($id_indikator_program)) {
			$this->db->where_in('id', $id_indikator_program);
			$this->db->delete($this->table_indikator_program);
		}

		$renja = $this->get_one_program(NULL, NULL, $id_program);
		//$this->update_status_after_edit($renja->id, NULL, $id_program);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_program($id){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->where('is_prog_or_keg', $this->is_program);
		$this->db->delete($this->table_program_kegiatan);

		$this->db->where('parent', $id);
		$this->db->where('is_prog_or_keg', $this->is_kegiatan);
		$this->db->delete($this->table_program_kegiatan);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_info_kodefikasi_program($id_program=NULL){
		if (!empty($id_program)) {
			$this->db->select($this->table_program_kegiatan.".kd_urusan");
			$this->db->select($this->table_program_kegiatan.".kd_bidang");
			$this->db->select($this->table_program_kegiatan.".kd_program");
			$this->db->select($this->table_program_kegiatan.".nama_prog_or_keg");
			$this->db->from($this->table_program_kegiatan);
			$this->db->where($this->table_program_kegiatan.'.id', $id_program);
		}

		$result = $this->db->get();
		return $result->row();
	}

	function add_kegiatan_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn, $dataKegiatan1, $dataKegiatan2){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$KodeUrusan = $data['kd_urusan'];
		$KodeBidang = $data['kd_bidang'];
		$kodeProgram = $data['kd_program'];
		$KodeKegiatan = $data['kd_kegiatan'];
		$thnskr = $dataKegiatan1['tahun'];
		$thndpn = $dataKegiatan2['tahun'];
		$created_date =  date("d-m-Y_H-i-s");

		$add = array('is_prog_or_keg'=> $this->is_kegiatan, 'id_status'=> $this->id_status_baru);
		$data = $this->global_function->add_array($data, $add);

		$this->db->insert($this->table_program_kegiatan, $data);

		$id = $this->db->insert_id();
		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_indikator[$key],
			'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key], 'target_thndpn' => $target_thndpn[$key]));
		}

		// $banyakData1 = count($dataKegiatan1['kode_sumber_dana']);
		// for($i =1; $i <= $banyakData1; ++$i) {
		// 		$datatahun1_batch[] = array(
		// 			'tahun'=>$thnskr,
		// 			'kode_urusan'=>$KodeUrusan,
		// 			'kode_bidang' => $KodeBidang,
		// 			'kode_program' => $kodeProgram,
		// 			'id_keg' => $id,
		// 			'kode_kegiatan' => $KodeKegiatan,
		// 			'kode_sumber_dana' => $dataKegiatan1['kode_sumber_dana'][$i],
		// 			'kode_jenis_belanja' => $dataKegiatan1['kode_jenis_belanja'][$i],
		// 			'kode_kategori_belanja' => $dataKegiatan1['kode_kategori_belanja'][$i],
		// 			'kode_sub_kategori_belanja' => $dataKegiatan1['kode_sub_kategori_belanja'][$i],
		// 			'kode_belanja' => $dataKegiatan1['kode_belanja'][$i],
		// 			'uraian_belanja' => $dataKegiatan1['uraian_belanja'][$i],
		// 			'detil_uraian_belanja' => $dataKegiatan1['detil_uraian_belanja'][$i],
		// 			'volume' => $dataKegiatan1['volume'][$i],
		// 			'nominal_satuan' => $dataKegiatan1['nominal_satuan'][$i],
		// 			'satuan' => $dataKegiatan1['satuan'][$i],
		// 			'subtotal' => $dataKegiatan1['subtotal'][$i],
		// 			'is_tahun_sekarang'=>1,
		// 			'id_status_rka'=>1,
		// 			'created_date' => $created_date
		// 			)	;
		// }

		// $banyakData2 = count($dataKegiatan2['kode_sumber_dana']);
		// for($i =1; $i <= $banyakData2; ++$i) {
		// 		$datatahun2_batch[] = array(
		// 			'tahun'=>$thndpn,
		// 			'kode_urusan'=>$KodeUrusan,
		// 			'kode_bidang' => $KodeBidang,
		// 			'kode_program' => $kodeProgram,
		// 			'id_keg' => $id,
		// 			'kode_kegiatan' => $KodeKegiatan,
		// 			'kode_sumber_dana' => $dataKegiatan2['kode_sumber_dana'][$i],
		// 			'kode_jenis_belanja' => $dataKegiatan2['kode_jenis_belanja'][$i],
		// 			'kode_kategori_belanja' => $dataKegiatan2['kode_kategori_belanja'][$i],
		// 			'kode_sub_kategori_belanja' => $dataKegiatan2['kode_sub_kategori_belanja'][$i],
		// 			'kode_belanja' => $dataKegiatan2['kode_belanja'][$i],
		// 			'uraian_belanja' => $dataKegiatan2['uraian_belanja'][$i],
		// 			'detil_uraian_belanja' => $dataKegiatan2['detil_uraian_belanja'][$i],
		// 			'volume' => $dataKegiatan2['volume'][$i],
		// 			'nominal_satuan' => $dataKegiatan2['nominal_satuan'][$i],
		// 			'satuan' => $dataKegiatan2['satuan'][$i],
		// 			'subtotal' => $dataKegiatan2['subtotal'][$i],
		// 			'is_tahun_sekarang'=>0,
		// 			'id_status_rka'=>1,
		// 			'created_date' => $created_date
		// 			)	;
		// }

		// $this->db->insert_batch('tx_rka_belanja_kegiatan', $datatahun1_batch);

		// $this->db->insert_batch('tx_rka_belanja_kegiatan', $datatahun2_batch);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_kegiatan_skpd($data, $id_kegiatan, $indikator, $id_indikator_kegiatan, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_kegiatan);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_kegiatan);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_kegiatan[$key])) {
				$this->db->where('id', $id_indikator_kegiatan[$key]);
				$this->db->where('id_prog_keg', $id_kegiatan);
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key],
				 'status_indikator_thndpn' => $status_indikator[$key], 'kategori_indikator_thndpn' => $kategori_indikator[$key], 'target' => $target[$key], 'target_thndpn' => $target_thndpn[$key]));
				unset($id_indikator_kegiatan[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_kegiatan, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key],
				 'status_indikator_thndpn' => $status_indikator[$key], 'kategori_indikator_thndpn' => $kategori_indikator[$key], 'target' => $target[$key], 'target_thndpn' => $target_thndpn[$key]));
			 }
		}

		if (!empty($id_indikator_kegiatan)) {
			$this->db->where_in('id', $id_indikator_kegiatan);
			$this->db->delete($this->table_indikator_program);
		}

		//$renstra = $this->get_one_kegiatan(NULL, NULL, NULL, $id_kegiatan);
		//$this->update_status_after_edit($renstra->id_renstra, NULL, NULL, $id_kegiatan);

		// $KodeUrusan = $data['kd_urusan'];
		// $KodeBidang = $data['kd_bidang'];
		// $kodeProgram = $data['kd_program'];
		// $KodeKegiatan = $data['kd_kegiatan'];
		// $thnskr = $dataKegiatan1['tahun'];
		// $thndpn = $dataKegiatan2['tahun'];
		// $created_date =  date("d-m-Y_H-i-s");

		// $this->db->query("delete from tx_rka_belanja_kegiatan where id_keg = $id_kegiatan ");

		// $banyakData1 = count($dataKegiatan1['kode_sumber_dana']);
		// for($i =1; $i <= $banyakData1; ++$i) {
		// 		$datatahun1_batch[] = array(
		// 			'tahun'=>$thnskr,
		// 			'kode_urusan'=>$KodeUrusan,
		// 			'kode_bidang' => $KodeBidang,
		// 			'kode_program' => $kodeProgram,
		// 			'id_keg' => $id_kegiatan,
		// 			'kode_kegiatan' => $KodeKegiatan,
		// 			'kode_sumber_dana' => $dataKegiatan1['kode_sumber_dana'][$i],
		// 			'kode_jenis_belanja' => $dataKegiatan1['kode_jenis_belanja'][$i],
		// 			'kode_kategori_belanja' => $dataKegiatan1['kode_kategori_belanja'][$i],
		// 			'kode_sub_kategori_belanja' => $dataKegiatan1['kode_sub_kategori_belanja'][$i],
		// 			'kode_belanja' => $dataKegiatan1['kode_belanja'][$i],
		// 			'uraian_belanja' => $dataKegiatan1['uraian_belanja'][$i],
		// 			'detil_uraian_belanja' => $dataKegiatan1['detil_uraian_belanja'][$i],
		// 			'volume' => $dataKegiatan1['volume'][$i],
		// 			'nominal_satuan' => $dataKegiatan1['nominal_satuan'][$i],
		// 			'satuan' => $dataKegiatan1['satuan'][$i],
		// 			'subtotal' => $dataKegiatan1['subtotal'][$i],
		// 			'is_tahun_sekarang'=>1,
		// 			'id_status_rka'=>1,
		// 			'created_date' => $created_date
		// 			)	;
		// }

		// $banyakData2 = count($dataKegiatan2['kode_sumber_dana']);
		// for($i =1; $i <= $banyakData2; ++$i) {
		// 		$datatahun2_batch[] = array(
		// 			'tahun'=>$thndpn,
		// 			'kode_urusan'=>$KodeUrusan,
		// 			'kode_bidang' => $KodeBidang,
		// 			'kode_program' => $kodeProgram,
		// 			'id_keg' => $id_kegiatan,
		// 			'kode_kegiatan' => $KodeKegiatan,
		// 			'kode_sumber_dana' => $dataKegiatan2['kode_sumber_dana'][$i],
		// 			'kode_jenis_belanja' => $dataKegiatan2['kode_jenis_belanja'][$i],
		// 			'kode_kategori_belanja' => $dataKegiatan2['kode_kategori_belanja'][$i],
		// 			'kode_sub_kategori_belanja' => $dataKegiatan2['kode_sub_kategori_belanja'][$i],
		// 			'kode_belanja' => $dataKegiatan2['kode_belanja'][$i],
		// 			'uraian_belanja' => $dataKegiatan2['uraian_belanja'][$i],
		// 			'detil_uraian_belanja' => $dataKegiatan2['detil_uraian_belanja'][$i],
		// 			'volume' => $dataKegiatan2['volume'][$i],
		// 			'nominal_satuan' => $dataKegiatan2['nominal_satuan'][$i],
		// 			'satuan' => $dataKegiatan2['satuan'][$i],
		// 			'subtotal' => $dataKegiatan2['subtotal'][$i],
		// 			'is_tahun_sekarang'=>0,
		// 			'id_status_rka'=>1,
		// 			'created_date' => $created_date
		// 			)	;
		// }

		// $this->db->insert_batch('tx_rka_belanja_kegiatan', $datatahun1_batch);

		// $this->db->insert_batch('tx_rka_belanja_kegiatan', $datatahun2_batch);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_kegiatan($id){
		$this->db->where('id', $id);
		$this->db->where('is_prog_or_keg', $this->is_kegiatan);
		$result = $this->db->delete($this->table_program_kegiatan);
		return $result;
	}

	function add_rka()
	{
		$data = $this->global_function->add_array($data, $add);

		$result = $this->db->insert($this->table_rka, $data);
		return $result;
	}

	function get_data($data,$table){
        $this->db->where($data);
        $query = $this->db->get($this->$table);
        return $query->row();
    }

	function get_rka_by_id($id_rka)
	{
		$sql = "
				SELECT *
				FROM t_rka
				WHERE id_rka = ?
			";

		$query = $this->db->query($sql, array($id_rka));

		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}

		return NULL;
	}

	function simpan_rka($data_rka)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();


		$data_rka->created_date		= Formatting::get_datetime();
		$data_rka->created_by		= $this->session->userdata('username');

		$this->db->set($data_rka);
    	$this->db->insert('t_rka');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
    function update_rka($data,$id,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id_rka','kd_urusan','kd_bidang','kd_program','kd_kegiatan');
		$sql = "
			SELECT * FROM (
				SELECT r.`id_rka`,r.`kd_urusan`,r.`kd_bidang`,r.`kd_program`,r.`kd_kegiatan`,r.`indikator_capaian`,r.`tahun_sekarang`,r.`lokasi`,
				r.`capaian_sekarang`,r.`jumlah_dana_sekarang`,r.`tahun_mendatang`,r.`capaian_mendatang`,r.`jumlah_dana_mendatang`,
				u.`Nm_Urusan` AS nm_urusan, b.`Nm_Bidang` AS nm_bidang, p.`Ket_Program` AS ket_program, k.`Ket_Kegiatan` AS ket_kegiatan
				FROM t_rka AS r
				LEFT JOIN m_urusan AS u ON r.`kd_urusan`=u.`Kd_Urusan`
				LEFT JOIN m_bidang AS b ON r.`kd_urusan`=b.`Kd_Urusan`
										AND r.`kd_bidang`=b.`Kd_Bidang`
				LEFT JOIN m_program AS p ON r.`kd_urusan`=p.`Kd_Urusan`
										AND r.`kd_bidang`=p.`Kd_Bidang`
										AND r.`kd_program`=p.`Kd_Prog`
				LEFT JOIN m_kegiatan AS k ON r.`kd_urusan`=k.`kd_urusan`
										AND r.`kd_bidang`=k.`Kd_Bidang`
										AND r.`kd_program`=k.`Kd_Prog`
										AND r.`kd_kegiatan`=k.`Kd_Keg`
				WHERE (r.kd_urusan LIKE '%".$search['value']."%'
				OR r.kd_bidang LIKE '%".$search['value']."%'
				OR r.kd_program LIKE '%".$search['value']."%'
				OR r.kd_kegiatan LIKE '%".$search['value']."%')
			) AS a
			order by ".$order_arr[$order["column"]]." ".$order["dir"]."
            limit $start,$length
		";
		// $sql="
		// 	SELECT * FROM (
		// 	SELECT r.id_rka
		// 	FROM ".$this->table_rka." AS r
		// 		LEFT JOIN
		// 	WHERE (kd_urusan LIKE '%".$search['value']."%'
  //           OR kd_bidang LIKE '%".$search['value']."%'
  //           OR kd_program LIKE '%".$search['value']."%'
  //           OR kd_kegiatan LIKE '%".$search['value']."%')
		// 	) AS a
		// ";
		//$this->db->limit($length, $start);
		//$this->db->order_by($order_arr[$order["column"]], $order["dir"]);

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_rka);

		$this->db->like("kd_urusan", $search['value']);
		$this->db->or_like("kd_bidang", $search['value']);
		$this->db->or_like("kd_program", $search['value']);
		$this->db->or_like("kd_kegiatan", $search['value']);

		$result = $this->db->count_all_results();
		return $result;
	}

	function get_data_with_rincian($id_rka,$table)
	{
		$sql="
			SELECT * FROM ".$this->$table."
			WHERE id_rka = ?
		";

		$query = $this->db->query($sql, array($id_rka));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
	}

    function delete_rka($id){
   	    $this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id_rka',$id);
        $this->db->delete('t_rka');


		$this->db->trans_complete();

		return $this->db->trans_status();
    }

	function get_urusan_skpd_4_cetak($id_skpd,$tahun)
    {
    	$query = "SELECT t.*,u.Nm_Urusan as nama_urusan from (
				SELECT
					pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
					SUM(keg.nominal) AS sum_nominal,
					SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
				FROM
					(SELECT * FROM tx_rka_prog_keg WHERE is_prog_or_keg=1) AS pro
				INNER JOIN
					(SELECT * FROM tx_rka_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
				WHERE
					keg.id_skpd = ?
					AND keg.tahun = ?
				GROUP BY pro.kd_urusan
				ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
				) t
				left join m_urusan u
				on t.kd_urusan = u.Kd_Urusan";
		$data = array($id_skpd,$tahun);
		$result = $this->db->query($query, $data);
		return $result->result();
    }

    function get_program_skpd_4_cetak($id_skpd,$tahun,$kd_urusan,$kd_bidang,$for_where=NULL){
    	if (empty($for_where)) {
			$for_where = "keg.id_skpd = ".$id_skpd."";
		}
    	$query = "SELECT
						keg.penanggung_jawab, keg.lokasi, keg.lokasi_thndpn, keg.catatan,
						pro.*,
						SUM(keg.nominal) AS sum_nominal,
						SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
					FROM
						(SELECT * FROM tx_rka_prog_keg WHERE is_prog_or_keg=1) AS pro
					INNER JOIN
						(SELECT * FROM tx_rka_prog_keg WHERE is_prog_or_keg=2 AND id IN (SELECT id_prog_keg 
FROM tx_rka_indikator_prog_keg WHERE target > 0)) AS keg ON keg.parent=pro.id
					WHERE
						".$for_where." 
						AND
						keg.tahun=? AND
						keg.kd_urusan=? AND
						keg.kd_bidang=?

					GROUP BY pro.id
					HAVING SUM(keg.nominal) > 0 
					ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC";
		$data = array($tahun,$kd_urusan,$kd_bidang);
		$result = $this->db->query($query, $data);
		return $result->result();
    }

    function get_kegiatan_skpd_4_cetak($id_program){
		$query = "SELECT
						tx_rka_prog_keg.*
					FROM tx_rka_prog_keg
					WHERE parent=?
					AND id IN (SELECT id_prog_keg FROM tx_rka_indikator_prog_keg
					WHERE id_prog_keg = tx_rka_prog_keg.id
					AND target > 0) and nominal>0";
		$data = array($id_program);
		$result = $this->db->query($query, $data);
		return $result;
	}

	function get_total_kegiatan_dan_indikator($id_program){
		$query = "SELECT
						COUNT(*) AS total
					FROM
						tx_rka_prog_keg
					INNER JOIN
						tx_rka_indikator_prog_keg ON tx_rka_indikator_prog_keg.id_prog_keg=tx_rka_prog_keg.id
					WHERE
						tx_rka_prog_keg.parent=? OR tx_rka_prog_keg.id=?";
		$data = array($id_program, $id_program);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_one_rka_skpd($id_skpd, $detail=FALSE){
		$this->db->select($this->table.".*");
		$this->db->from($this->table);
		$this->db->where($this->table.".id_skpd", $id_skpd);

		if ($detail) {
			$this->db->select("nama_skpd");
			$this->db->join("m_skpd","tx_rka.id_skpd = m_skpd.id_skpd","inner");
		}

		$result = $this->db->get();
		return $result->row();
	}



	function get_kegiatan($id_kegiatan, $tahun=NULL, $is_tahun=NULL, $not_in=NULL){
				$th = "";
				$not = "";
				if (!empty($tahun)) {
					$th = " AND tahun = '".$tahun."' AND is_tahun_sekarang = '".$is_tahun."'";
				}
				if(!empty($not_in)){
					$not = " AND id <> '".$not_in."' ";
				}

				$query = "SELECT id ,tahun,
								kode_sumber_dana AS kode_sumber_dana,(
									SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
								) AS Sumber_dana,
								kode_jenis_belanja AS kode_jenis_belanja, (
									SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
								) AS jenis_belanja,
								kode_kategori_belanja AS kode_kategori_belanja, (
									SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
								) AS kategori_belanja,
								kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
									SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
								) AS sub_kategori_belanja,
								kode_belanja AS kode_belanja,(
									SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
								) AS belanja,
								uraian_belanja, detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal, id_keg
								FROM tx_rka_belanja_kegiatan
								WHERE id_keg = '$id_kegiatan' ".$th." ".$not." 
								ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC";

		$result = $this->db->query($query);
		return $result->result();
	}

	function get_one_belanja($id_belanja){
		$query = "SELECT id ,tahun, 
				kode_sumber_dana AS kode_sumber_dana,(
					SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
				) AS Sumber_dana,
				kode_jenis_belanja AS kode_jenis_belanja, (
					SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
				) AS jenis_belanja,
				kode_kategori_belanja AS kode_kategori_belanja, (
					SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
				) AS kategori_belanja,
				kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
					SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
				) AS sub_kategori_belanja,
				kode_belanja AS kode_belanja,(
					SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
				) AS belanja,
				uraian_belanja, detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal, id_keg
				FROM tx_rka_belanja_kegiatan
				WHERE id = '$id_belanja'
				ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC";

		$result = $this->db->query($query);
		return $result->row();
	}

	function add_belanja_kegiatan($data, $id_belanja){
		if (!empty($id_belanja)) {
			$this->db->where('id', $id_belanja);
			$this->db->update('tx_rka_belanja_kegiatan', $data);
		}else{
			$this->db->insert('tx_rka_belanja_kegiatan', $data);
		}
	}

	function delete_one_kegiatan($id){
		$this->db->query('DELETE FROM tx_rka_belanja_kegiatan WHERE id = "'.$id.'"');
	}

	function get_kegiatan_for_221_new($ta, $idK){
		$query = $this->db->query("SELECT kd_urusan AS kode_urusan, kd_bidang AS kode_bidang, kd_program AS kode_program, kd_kegiatan AS kode_kegiatan
			,(SELECT Kd_Fungsi FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang) AS kode_fungsi
			,(SELECT Nm_Fungsi FROM m_fungsi WHERE Kd_Fungsi = kode_fungsi) AS nama_fungsi
			,(SELECT Nm_Urusan FROM m_urusan WHERE Kd_Urusan = kode_urusan) AS nama_urusan
			,(SELECT Nm_Bidang FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang) AS nama_bidang
			,(SELECT Ket_Program FROM m_program WHERE Kd_Urusan = kode_urusan  AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program) AS nama_program
			,(SELECT Ket_Kegiatan FROM m_kegiatan WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program AND Kd_Keg = kode_kegiatan) AS nama_kegiatan
			,nominal,nominal_thndpn, parent, lokasi
			FROM tx_rka_prog_keg
			WHERE id = '$idK'");
		return $query->row();
	}

	function get_belanja_for_221_new($ta, $is_thn, $idK){
		$query = $this->db->query("SELECT kode_sumber_dana AS kode_sumber_dana
			,(SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana) AS sumberDana
			,kode_jenis_belanja AS kode_jenis_belanja
			,(SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja) AS jenis
			,kode_kategori_belanja AS kode_kategori_belanja
			,(SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja) AS kategori
			,kode_sub_kategori_belanja AS kode_sub_kategori_belanja
			,(SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja) AS subkategori
			,kode_belanja AS kode_belanja,(SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja) AS belanja
			,uraian_belanja, REPLACE(UPPER(uraian_belanja), ' ','') AS uraian_upper
			,detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal
			FROM tx_rka_belanja_kegiatan
			WHERE tahun = '$ta' AND id_keg = '$idK' AND is_tahun_sekarang = '$is_thn'
			ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC, uraian_upper ASC, detil_uraian_belanja ASC");
		return $query->result();
	}


	###### UNTUK SIMDA KEUANGAN ######
	function export_to_simda($ta, $id_skpd){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		//------------------------------------hapus semua data pada DB simda MYSQL
		// $this->db->query('DELETE FROM simda_ta_sub_unit');
		// $this->db->query('DELETE FROM simda_ta_misi');
		// $this->db->query('DELETE FROM simda_ta_tujuan');
		// $this->db->query('DELETE FROM simda_ta_sasaran');
		$this->db->query('DELETE FROM simda_ta_program');
		$this->db->query('DELETE FROM simda_ta_capaian_program');
		$this->db->query('DELETE FROM simda_ta_kegiatan');
		$this->db->query('DELETE FROM simda_ta_indikator');
		$this->db->query('DELETE FROM simda_ta_belanja');
		$this->db->query('DELETE FROM simda_ta_belanja_rinc');
		$this->db->query('DELETE FROM simda_ta_belanja_rinc_sub');

		//------------------------------------for sub unit / visi
		// $this->db->query('INSERT INTO simda_ta_sub_unit(
		// 	SELECT '.$ta.' AS tahun
		// 	,s.kode_urusan
		// 	,s.kode_bidang
		// 	,s.kd_unit
		// 	,s.kode_sub_unit
		// 	,REPLACE(s.kaskpd_nama, "\'", "") AS Nip_Pimpinan
		// 	,s.kaskpd_nip
		// 	,REPLACE(s.nama_jabatan, "\'", "") AS Jbt_Pimpinan
		// 	,REPLACE(s.alamat, "\'", "") AS Alamat
		// 	,REPLACE(r.visi, "\'", "") AS Ur_Visi
		// 	FROM t_renstra r
		// 	INNER JOIN m_skpd s
		// 	ON r.id_skpd = s.kode_unit
		// 	WHERE s.id_skpd = '.$id_skpd.')');

		//------------------------------------for misi, tujuan, dan sasaran
		// $dump_misi = $this->db->query('SELECT '.$ta.' AS Tahun
		// 	,m_skpd.kode_urusan AS Kd_Urusan
		// 	,m_skpd.kode_bidang AS Kd_Bidang
		// 	,m_skpd.kd_unit AS Kd_Unit
		// 	,m_skpd.kode_sub_unit AS Kd_Sub
		// 	,t_renstra_misi.id AS No_Misi
		// 	,REPLACE(t_renstra_misi.misi, "\'", "") AS Ur_Misi
		// 	FROM t_renstra_misi 
		// 	INNER JOIN t_renstra 
		// 	ON t_renstra_misi.id_renstra = t_renstra.id 
		// 	INNER JOIN m_skpd
		// 	ON t_renstra.id_skpd= m_skpd.kode_unit
		// 	WHERE m_skpd.id_skpd = '.$id_skpd.'');

		// foreach ($dump_misi->result() as $key_dump_misi => $val_dump_misi) {
		// 	$old_id_misi = $val_dump_misi->No_Misi; 
		// 	$val_dump_misi->No_Misi = $key_dump_misi+1;

		// 	$dump_tujuan = $this->db->query('SELECT '.$ta.' AS tahun
		// 		,m_skpd.kode_urusan AS Kd_Urusan
		// 		,m_skpd.kode_bidang AS Kd_Bidang
		// 		,m_skpd.kd_unit AS Kd_Unit
		// 		,m_skpd.kode_sub_unit AS Kd_Sub
		// 		,'.$val_dump_misi->No_Misi.' AS No_Misi
		// 		,t_renstra_tujuan.id AS No_Tujuan
		// 		,REPLACE(t_renstra_tujuan.tujuan, "\'", "") AS Ur_Tujuan
		// 		FROM t_renstra_tujuan 
		// 		INNER JOIN t_renstra 
		// 		ON t_renstra_tujuan.id_renstra = t_renstra.id 
		// 		INNER JOIN m_skpd
		// 		ON t_renstra.id_skpd = m_skpd.kode_unit
		// 		WHERE t_renstra.id_skpd = '.$id_skpd.' AND t_renstra_tujuan.id_misi = '.$old_id_misi.'');

		// 	foreach ($dump_tujuan->result() as $key_dump_tujuan => $val_dump_tujuan) {
		// 		$old_id_tujuan = $val_dump_tujuan->No_Tujuan; 
		// 		$val_dump_tujuan->No_Tujuan = $key_dump_tujuan+1;

		// 		$dump_sasaran = $this->db->query('SELECT '.$ta.' AS tahun
		// 			,m_skpd.kode_urusan AS Kd_Urusan
		// 			,m_skpd.kode_bidang AS Kd_Bidang
		// 			,m_skpd.kd_unit AS Kd_Unit
		// 			,m_skpd.kode_sub_unit AS Kd_Sub
		// 			,'.$val_dump_tujuan->No_Misi.' AS No_Misi
		// 			,'.$val_dump_tujuan->No_Tujuan.' AS No_Tujuan
		// 			,t_renstra_sasaran.id AS No_Sasaran
		// 			,REPLACE(t_renstra_sasaran.sasaran, "\'", "") AS Keterangan
		// 			FROM t_renstra_sasaran
		// 			INNER JOIN t_renstra
		// 			ON t_renstra_sasaran.id_renstra = t_renstra.id
		// 			INNER JOIN m_skpd
		// 			ON t_renstra.id_skpd = m_skpd.kode_unit
		// 			WHERE t_renstra.id_skpd = '.$id_skpd.' AND t_renstra_sasaran.id_tujuan = '.$old_id_tujuan.'');

		// 		foreach ($dump_sasaran->result() as $key_dump_sasaran => $val_dump_sasaran) {
		// 			$val_dump_sasaran->No_Sasaran = $key_dump_sasaran+1;

		// 			$this->db->insert('simda_ta_sasaran', $val_dump_sasaran);
		// 		}

		// 		$this->db->insert('simda_ta_tujuan', $val_dump_tujuan);
		// 	}

		// 	$this->db->insert('simda_ta_misi', $val_dump_misi);
		// }

		//------------------------------------for program dan capaian program
		$dump_program = $this->db->query('SELECT a.tahun
			,b.kode_urusan AS Kd_Urusan
			,b.kode_bidang AS Kd_Bidang
			,b.kd_unit AS Kd_Unit
			,b.kode_sub_unit AS Kd_Sub
			,a.kd_program AS Kd_Prog
			,a.id AS ID_Prog
			,a.nama_prog_or_keg AS ket_program
			,"" AS tolak_ukur
			,"" AS target_angka
			,"" AS target_uraian
			,a.kd_urusan AS Kd_Urusan1
			,a.kd_bidang AS Kd_Bidang1
			FROM tx_rka_prog_keg a 
			INNER JOIN m_skpd b
			ON a.id_skpd = b.id_skpd
			WHERE is_prog_or_keg=1 AND a.tahun = '.$ta.' AND a.id_skpd='.$id_skpd.'');

		foreach ($dump_program->result() as $key_dump_program => $val_dump_program) {
			$f_Id_Program[$val_dump_program->ID_Prog] = $val_dump_program->Kd_Urusan1.$val_dump_program->Kd_Bidang1;
			$f_Id_Program2 = $val_dump_program->ID_Prog;
			$val_dump_program->ID_Prog = $val_dump_program->Kd_Urusan1.$val_dump_program->Kd_Bidang1;

			$dump_capaian = $this->db->query('SELECT b.tahun
				,c.kode_urusan AS Kd_Urusan
				,c.kode_bidang AS Kd_Bidang
				,c.kd_unit AS Kd_Unit
				,c.kode_sub_unit AS Kd_Sub
				,b.kd_program AS Kd_Prog
				,'.$val_dump_program->ID_Prog.' AS Id_Prog
				,a.id AS No_ID
				,a.indikator AS Tolak_Ukur
				,a.target AS Target_Angka
				,a.satuan_target AS Target_Uraian
				FROM tx_rka_indikator_prog_keg a
				INNER JOIN tx_rka_prog_keg b
				ON a.id_prog_keg = b.id
				INNER JOIN m_skpd c
				ON b.id_skpd = c.id_skpd
				WHERE b.is_prog_or_keg = 1 AND b.tahun = '.$ta.' AND b.id_skpd = '.$id_skpd.' AND b.id = '.$f_Id_Program2.'');

			foreach ($dump_capaian->result() as $key_dump_capaian => $val_dump_capaian) {
				$val_dump_capaian->No_ID = $key_dump_capaian+1;
				$this->db->insert('simda_ta_capaian_program', $val_dump_capaian);
			}

			$this->db->insert('simda_ta_program', $val_dump_program);
		}

		//------------------------------------for kegiatan
		$dump_kegiatan = $this->db->query('SELECT a.tahun
			,b.kode_urusan AS Kd_Urusan
			,b.kode_bidang AS Kd_Bidang
			,b.kd_unit AS Kd_Unit
			,b.kode_sub_unit AS Kd_Sub
			,a.kd_program AS Kd_Prog
			,a.parent AS Id_Prog
			,a.kd_kegiatan AS Kd_Keg
			,a.nama_prog_or_keg AS Ket_Kegiatan
			,a.lokasi AS Lokasi
			,NULL AS kelompok_sasaran
			,"1" AS status_kegiatan
			,NULL AS pagu_anggaran
			,NULL AS waktu_pelaksanaan
			,NULL AS kd_sumber
			FROM tx_rka_prog_keg a 
			INNER JOIN m_skpd b
			ON a.id_skpd=b.id_skpd
			WHERE a.is_prog_or_keg = 2 AND a.tahun = '.$ta.' AND b.id_skpd = '.$id_skpd.'');

		foreach ($dump_kegiatan->result() as $key_dump_kegiatan => $val_dump_kegiatan) {
			$val_dump_kegiatan->Id_Prog = $f_Id_Program[$val_dump_kegiatan->Id_Prog];
			$this->db->insert('simda_ta_kegiatan', $val_dump_kegiatan);
		}

		//------------------------------------for indikator
		$dump_indikator = $this->db->query('SELECT b.tahun
				,c.kode_urusan AS Kd_Urusan
				,c.kode_bidang AS Kd_Bidang
				,c.kd_unit AS Kd_Unit
				,c.kode_sub_unit AS Kd_Sub
				,b.kd_program AS Kd_Prog
				,b.parent AS Id_Prog
				,b.kd_kegiatan AS Kd_Keg
				,"3" AS Kd_Indikator
				,a.id AS No_ID
				,a.indikator AS Tolak_Ukur
				,a.target AS Target_Angka
				,a.satuan_target AS Target_Uraian
				FROM tx_rka_indikator_prog_keg a
				INNER JOIN tx_rka_prog_keg b
				ON a.id_prog_keg = b.id
				INNER JOIN m_skpd c
				ON b.id_skpd = c.id_skpd
				WHERE b.is_prog_or_keg = 2 AND b.tahun = '.$ta.' AND b.id_skpd = '.$id_skpd.'');

		foreach ($dump_indikator->result() as $key_dump_indikator => $val_dump_indikator) {
			$val_dump_indikator->Id_Prog = $f_Id_Program[$val_dump_indikator->Id_Prog];
			$val_dump_indikator->No_ID = $key_dump_indikator+1;
			$this->db->insert('simda_ta_indikator', $val_dump_indikator);
		}

		//------------------------------------for belanja (CUKUP RUWET)
		$belanja = $this->db->query('SELECT c.id_keg
			,a.tahun AS Tahun
			,b.kode_urusan AS Kd_Urusan
			,b.kode_bidang AS Kd_Bidang
			,b.kd_unit AS Kd_Unit
			,b.kode_sub_unit AS Kd_Sub
			,a.kd_program AS Kd_Prog
			,a.parent AS Id_Prog
			,a.kd_kegiatan AS Kd_Keg
			,LEFT(c.kode_jenis_belanja, 1) AS Kd_Rek_1
			,RIGHT(c.kode_jenis_belanja, 1) AS Kd_Rek_2
			,c.kode_kategori_belanja AS Kd_Rek_3
			,c.kode_sub_kategori_belanja AS Kd_Rek_4
			,c.kode_belanja AS Kd_Rek_5
			,NULL AS Kd_Ap_Pub
			,c.kode_sumber_dana AS Kd_Sumber
			FROM tx_rka_prog_keg a 
			INNER JOIN m_skpd b
			ON a.id_skpd = b.id_skpd
			INNER JOIN tx_rka_belanja_kegiatan c
			ON a.id = c.id_keg
			WHERE a.is_prog_or_keg = 2 AND a.tahun = '.$ta.' AND a.id_skpd = '.$id_skpd.'
			GROUP BY b.kode_urusan, b.kode_bidang, b.kode_unit, b.kode_sub_unit ,a.kd_program 
			,a.parent ,a.kd_kegiatan ,LEFT(c.kode_jenis_belanja, 1) ,RIGHT(c.kode_jenis_belanja, 1)
			,c.kode_kategori_belanja ,c.kode_sub_kategori_belanja ,c.kode_belanja');

		foreach ($belanja->result() as $key_belanja => $val_belanja) {
			$val_belanja->Id_Prog = $f_Id_Program[$val_belanja->Id_Prog];

			$belanja_rinc = $this->db->query('SELECT a.tahun AS Tahun
				,b.kode_urusan AS Kd_Urusan
				,b.kode_bidang AS Kd_Bidang
				,b.kd_unit AS Kd_Unit
				,b.kode_sub_unit AS Kd_Sub
				,a.kd_program AS Kd_Prog
				,a.parent AS Id_Prog
				,a.kd_kegiatan AS Kd_Keg
				,LEFT(c.kode_jenis_belanja, 1) AS Kd_Rek_1
				,RIGHT(c.kode_jenis_belanja, 1) AS Kd_Rek_2
				,c.kode_kategori_belanja AS Kd_Rek_3
				,c.kode_sub_kategori_belanja AS Kd_Rek_4
				,c.kode_belanja AS Kd_Rek_5
				,NULL AS No_Rinc
				,REPLACE(c.uraian_belanja, "\'", "") AS Keterangan
				,c.kode_sumber_dana AS Kd_Sumber
				FROM tx_rka_prog_keg a 
				INNER JOIN m_skpd b
				ON a.id_skpd = b.id_skpd
				INNER JOIN tx_rka_belanja_kegiatan c
				ON a.id = c.id_keg
				WHERE is_prog_or_keg = 2 AND c.is_tahun_sekarang = 1 AND a.tahun = '.$ta.' 
				AND a.id_skpd = '.$id_skpd.' AND c.id_keg = '.$val_belanja->id_keg.' 
				AND LEFT(c.kode_jenis_belanja, 1) = '.$val_belanja->Kd_Rek_1.' 
				AND RIGHT(c.kode_jenis_belanja, 1) = '.$val_belanja->Kd_Rek_2.' 
				AND c.kode_kategori_belanja = '.$val_belanja->Kd_Rek_3.' 
				AND c.kode_sub_kategori_belanja = '.$val_belanja->Kd_Rek_4.' 
				AND c.kode_belanja = '.$val_belanja->Kd_Rek_5.'
				GROUP BY c.uraian_belanja');

			foreach ($belanja_rinc->result() as $key_belanja_rinc => $val_belanja_rinc) {
				$val_belanja_rinc->No_Rinc = $key_belanja_rinc+1;
				$val_belanja_rinc->Id_Prog = $f_Id_Program[$val_belanja_rinc->Id_Prog];

				$belanja_rinc_sub = $this->db->query('SELECT a.tahun AS Tahun
					,b.kode_urusan AS Kd_Urusan
					,b.kode_bidang AS Kd_Bidang
					,b.kd_unit AS Kd_Unit
					,b.kode_sub_unit AS Kd_Sub
					,a.kd_program AS Kd_Prog
					,a.parent AS Id_Prog
					,a.kd_kegiatan AS Kd_Keg
					,LEFT(c.kode_jenis_belanja, 1) AS Kd_Rek_1
					,RIGHT(c.kode_jenis_belanja, 1) AS Kd_Rek_2
					,c.kode_kategori_belanja AS Kd_Rek_3
					,c.kode_sub_kategori_belanja AS Kd_Rek_4
					,c.kode_belanja AS Kd_Rek_5
					,'.$val_belanja_rinc->No_Rinc.' AS No_Rinc
					,NULL AS No_ID
					,REPLACE(c.satuan, "\'", "") AS Sat_1
					,c.volume AS Nilai_1
					,NULL AS Sat_2
					,NULL AS Nilai_2
					,NULL AS Sat_3
					,NULL AS Nilai_3
					,c.satuan AS Satuan123
					,c.volume AS Jml_Satuan
					,c.nominal_satuan AS Nilai_Rp
					,c.subtotal AS Total
					,REPLACE(c.detil_uraian_belanja, "\'", "") AS Keterangan
					FROM tx_rka_prog_keg a 
					INNER JOIN m_skpd b
					ON a.id_skpd = b.id_skpd
					INNER JOIN tx_rka_belanja_kegiatan c
					ON a.id = c.id_keg
					WHERE is_prog_or_keg = 2 AND c.is_tahun_sekarang = 1 AND a.tahun = '.$ta.' 
					AND a.id_skpd = '.$id_skpd.' AND c.id_keg = '.$val_belanja->id_keg.' 
					AND LEFT(c.kode_jenis_belanja, 1) = '.$val_belanja->Kd_Rek_1.' 
					AND RIGHT(c.kode_jenis_belanja, 1) = '.$val_belanja->Kd_Rek_2.' 
					AND c.kode_kategori_belanja = '.$val_belanja->Kd_Rek_3.' 
					AND c.kode_sub_kategori_belanja = '.$val_belanja->Kd_Rek_4.' 
					AND c.kode_belanja = '.$val_belanja->Kd_Rek_5.'
					AND c.uraian_belanja = "'.$val_belanja_rinc->Keterangan.'"');

				foreach ($belanja_rinc_sub->result() as $key_belanja_rinc_sub => $val_belanja_rinc_sub) {
					$val_belanja_rinc_sub->No_ID = $key_belanja_rinc_sub+1;
					$val_belanja_rinc_sub->Id_Prog = $f_Id_Program[$val_belanja_rinc_sub->Id_Prog];
					$this->db->insert('simda_ta_belanja_rinc_sub', $val_belanja_rinc_sub);
				}

				$this->db->insert('simda_ta_belanja_rinc', $val_belanja_rinc);
			}

			unset($val_belanja->id_keg);
			$this->db->insert('simda_ta_belanja', $val_belanja);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function export_to_simda_sqlsrv($ta, $id_skpd){
		$skpd = $this->db->query('SELECT * FROM m_skpd WHERE id_skpd = '.$id_skpd.'')->row();
		$kd_urusan = $skpd->kode_urusan;
		$kd_bidang = $skpd->kode_bidang;
		$kd_unit = $skpd->kd_unit;
		$kd_sub = $skpd->kode_sub_unit;
		// $ta_sub_unit = $this->db->query('SELECT * FROM simda_ta_sub_unit WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		// $ta_misi = $this->db->query('SELECT * FROM simda_ta_misi WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		// $ta_tujuan = $this->db->query('SELECT * FROM simda_ta_tujuan WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		// $ta_sasaran = $this->db->query('SELECT * FROM simda_ta_sasaran WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		$ta_program = $this->db->query('SELECT * FROM simda_ta_program WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		$ta_capaian_program = $this->db->query('SELECT * FROM simda_ta_capaian_program WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		$ta_kegiatan = $this->db->query('SELECT * FROM simda_ta_kegiatan WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		$ta_indikator = $this->db->query('SELECT * FROM simda_ta_indikator WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		$ta_belanja = $this->db->query('SELECT * FROM simda_ta_belanja WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		$ta_belanja_rinc = $this->db->query('SELECT * FROM simda_ta_belanja_rinc WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		$ta_belanja_rinc_sub = $this->db->query('SELECT * FROM simda_ta_belanja_rinc_sub WHERE tahun = '.$ta.' AND kd_urusan = '.$kd_urusan.' AND kd_bidang = '.$kd_bidang.' AND kd_unit = '.$kd_unit.' AND kd_sub ='.$kd_sub.'')->result();
		
		
		$serverName = "202.52.11.227\SERVER";
		// $serverName = "192.168.2.147\sqlexpress";
		// $serverName = "192.168.1.245\sqlexpress";
		// $serverName = "192.168.1.245";
		$connectionInfo = array("Database"=>"latihan", "UID"=>"admino", "PWD"=>"admin");
		$conn = sqlsrv_connect($serverName, $connectionInfo);

		// $qy_ta_sub_unit = 'Alter table ta_sub_unit disable trigger all;';
		// foreach ($ta_sub_unit as $row) {
		// 	$qy_ta_sub_unit .= "INSERT INTO ta_sub_unit 
		// 		VALUES ('".$row->Tahun."'
		// 		,'".$row->Kd_Urusan."'
		// 		,'".$row->Kd_Bidang."'
		// 		,'".$row->Kd_Unit."'
		// 		,'".$row->Kd_Sub."'
		// 		,'".$row->Nm_Pimpinan."'
		// 		,'".$row->Nip_Pimpinan."'
		// 		,'".$row->Jbt_Pimpinan."'
		// 		,'".$row->Alamat."'
		// 		,'".$row->Ur_Visi."');";
		// }
		// $qy_ta_sub_unit .= 'Alter table ta_sub_unit enable trigger all;';

		// $qy_ta_misi = 'Alter table ta_misi disable trigger all;';
		// foreach ($ta_misi as $row) {
		// 	$qy_ta_misi .= "INSERT INTO ta_misi 
		// 		VALUES ('".$row->Tahun."'
		// 		,'".$row->Kd_Urusan."'
		// 		,'".$row->Kd_Bidang."'
		// 		,'".$row->Kd_Unit."'
		// 		,'".$row->Kd_Sub."'
		// 		,'3'
		// 		,'".$row->Ur_Misi."');";
		// }
		// $qy_ta_misi .= 'Alter table ta_misi enable trigger all;';		

		// $qy_ta_tujuan = 'Alter table ta_tujuan disable trigger all;';
		// foreach ($ta_tujuan as $row) {
		// 	$qy_ta_tujuan .= "INSERT INTO ta_tujuan 
		// 		VALUES ('".$row->Tahun."'
		// 		,'".$row->Kd_Urusan."'
		// 		,'".$row->Kd_Bidang."'
		// 		,'".$row->Kd_Unit."'
		// 		,'".$row->Kd_Sub."'
		// 		,'".$row->No_Misi."'
		// 		,'".$row->No_Tujuan."'
		// 		,'".$row->Ur_Tujuan."');";
		// }
		// $qy_ta_tujuan .= 'Alter table ta_tujuan enable trigger all;';		

		// $qy_ta_sasaran = 'Alter table ta_sasaran disable trigger all;';
		// foreach ($ta_sasaran as $row) {
		// 	$qy_ta_sasaran .= "INSERT INTO ta_sasaran 
		// 		VALUES ('".$row->Tahun."'
		// 		,'".$row->Kd_Urusan."'
		// 		,'".$row->Kd_Bidang."'
		// 		,'".$row->Kd_Unit."'
		// 		,'".$row->Kd_Sub."'
		// 		,'".$row->No_Misi."'
		// 		,'".$row->No_Tujuan."'
		// 		,'".$row->No_Sasaran."'
		// 		,'".$row->Keterangan."');";
		// }
		// $qy_ta_sasaran .= 'Alter table ta_sasaran enable trigger all;';

		$qy_ta_program = 'Alter table ta_program disable trigger all;';
		foreach ($ta_program as $row) {
			$qy_ta_program .= "INSERT INTO ta_program 
				VALUES ('".$row->Tahun."'
				,'".$row->Kd_Urusan."'
				,'".$row->Kd_Bidang."'
				,'".$row->Kd_Unit."'
				,'".$row->Kd_Sub."'
				,'".$row->Kd_Prog."'
				,'".$row->ID_Prog."'
				,'".$row->Ket_Program."'
				,'".$row->Tolak_Ukur."'
				,'".$row->Target_Angka."'
				,'".$row->Target_Uraian."'
				,'".$row->Kd_Urusan1."'
				,'".$row->Kd_Bidang1."');";
		}
		$qy_ta_program .= 'Alter table ta_program enable trigger all;';

		$qy_ta_capaian_program = 'Alter table ta_capaian_program disable trigger all;';
		foreach ($ta_capaian_program as $row) {
			$qy_ta_capaian_program .= "INSERT INTO ta_capaian_program 
				VALUES ('".$row->Tahun."'
				,'".$row->Kd_Urusan."'
				,'".$row->Kd_Bidang."'
				,'".$row->Kd_Unit."'
				,'".$row->Kd_Sub."'
				,'".$row->Kd_Prog."'
				,'".$row->ID_Prog."'
				,'".$row->No_ID."'
				,'".$row->Tolak_Ukur."'
				,'".$row->Target_Angka."'
				,'".$row->Target_Uraian."');";
		}
		$qy_ta_capaian_program .= 'Alter table ta_capaian_program enable trigger all;';

		$qy_ta_kegiatan = 'Alter table ta_kegiatan disable trigger all;';
		foreach ($ta_kegiatan as $row) {
			$qy_ta_kegiatan .= "INSERT INTO ta_kegiatan 
				VALUES ('".$row->Tahun."'
				,'".$row->Kd_Urusan."'
				,'".$row->Kd_Bidang."'
				,'".$row->Kd_Unit."'
				,'".$row->Kd_Sub."'
				,'".$row->Kd_Prog."'
				,'".$row->ID_Prog."'
				,'".$row->Kd_Keg."'
				,'".$row->Ket_Kegiatan."'
				,'".$row->Lokasi."'
				,'".$row->Kelompok_Sasaran."'
				,'".$row->Status_Kegiatan."'
				,'".$row->Pagu_Anggaran."'
				,'".$row->Waktu_Pelaksanaan."'
				,'".$row->Kd_Sumber."');";
		}
		$qy_ta_kegiatan .= 'Alter table ta_kegiatan enable trigger all;';

		$qy_ta_indikator = 'Alter table ta_indikator disable trigger all;';
		foreach ($ta_indikator as $row) {
			$qy_ta_indikator .= "INSERT INTO ta_indikator 
				VALUES ('".$row->Tahun."'
				,'".$row->Kd_Urusan."'
				,'".$row->Kd_Bidang."'
				,'".$row->Kd_Unit."'
				,'".$row->Kd_Sub."'
				,'".$row->Kd_Prog."'
				,'".$row->ID_Prog."'
				,'".$row->Kd_Keg."'
				,'".$row->Kd_Indikator."'
				,'".$row->No_ID."'
				,'".$row->Tolak_Ukur."'
				,'".$row->Target_Angka."'
				,'".$row->Target_Uraian."');";
		}
		$qy_ta_indikator .= 'Alter table ta_indikator enable trigger all;';

		// $ex_ta_sub_unit = sqlsrv_query($conn, $qy_ta_sub_unit);
		// $ex_ta_misi = sqlsrv_query($conn, $qy_ta_misi);
		// $ex_ta_tujuan = sqlsrv_query($conn, $qy_ta_tujuan);
		// $ex_ta_sasaran = sqlsrv_query($conn, $qy_ta_sasaran);
		$ex_ta_program = sqlsrv_query($conn, $qy_ta_program);
		$ex_ta_capaian_program = sqlsrv_query($conn, $qy_ta_capaian_program);
		$ex_ta_kegiatan = sqlsrv_query($conn, $qy_ta_kegiatan);
		$ex_ta_indikator = sqlsrv_query($conn, $qy_ta_indikator);

		sqlsrv_query($conn, "Alter table ta_belanja disable trigger all;");
		foreach ($ta_belanja as $row) {
			sqlsrv_query($conn, "INSERT INTO ta_belanja 
				VALUES ('".$row->Tahun."'
				,'".$row->Kd_Urusan."'
				,'".$row->Kd_Bidang."'
				,'".$row->Kd_Unit."'
				,'".$row->Kd_Sub."'
				,'".$row->Kd_Prog."'
				,'".$row->ID_Prog."'
				,'".$row->Kd_Keg."'
				,'".$row->Kd_Rek_1."'
				,'".$row->Kd_Rek_2."'
				,'".$row->Kd_Rek_3."'
				,'".$row->Kd_Rek_4."'
				,'".$row->Kd_Rek_5."'
				,NULL
				,NULL);");
		}
		sqlsrv_query($conn, "Alter table ta_belanja enable trigger all;");

		sqlsrv_query($conn, "Alter table ta_belanja_rinc disable trigger all;");
		foreach ($ta_belanja_rinc as $row) {
			sqlsrv_query($conn, "INSERT INTO ta_belanja_rinc 
				VALUES ('".$row->Tahun."'
				,'".$row->Kd_Urusan."'
				,'".$row->Kd_Bidang."'
				,'".$row->Kd_Unit."'
				,'".$row->Kd_Sub."'
				,'".$row->Kd_Prog."'
				,'".$row->ID_Prog."'
				,'".$row->Kd_Keg."'
				,'".$row->Kd_Rek_1."'
				,'".$row->Kd_Rek_2."'
				,'".$row->Kd_Rek_3."'
				,'".$row->Kd_Rek_4."'
				,'".$row->Kd_Rek_5."'
				,'".$row->No_Rinc."'
				,'".$row->Keterangan."'
				,NULL);");
		}
		sqlsrv_query($conn, "Alter table ta_belanja_rinc enable trigger all;");

		sqlsrv_query($conn, "Alter table ta_belanja_rinc_sub disable trigger all;");
		foreach ($ta_belanja_rinc_sub as $row) {
			sqlsrv_query($conn, "INSERT INTO ta_belanja_rinc_sub 
				VALUES ('".$row->Tahun."'
				,'".$row->Kd_Urusan."'
				,'".$row->Kd_Bidang."'
				,'".$row->Kd_Unit."'
				,'".$row->Kd_Sub."'
				,'".$row->Kd_Prog."'
				,'".$row->ID_Prog."'
				,'".$row->Kd_Keg."'
				,'".$row->Kd_Rek_1."'
				,'".$row->Kd_Rek_2."'
				,'".$row->Kd_Rek_3."'
				,'".$row->Kd_Rek_4."'
				,'".$row->Kd_Rek_5."'
				,'".$row->No_Rinc."'
				,'".$row->No_ID."'
				,'".$row->Sat_1."'
				,'".$row->Nilai_1."'
				,NULL
				,'".$row->Nilai_2."'
				,NULL
				,'".$row->Nilai_3."'
				,'".$row->Satuan123."'
				,'".$row->Jml_Satuan."'
				,'".$row->Nilai_Rp."'
				,'".$row->Total."'
				,'".$row->Keterangan."');");
		}
		sqlsrv_query($conn, "Alter table ta_belanja_rinc_sub enable trigger all;");

		sqlsrv_close($conn);
	}

	function get_belanja_kegiatan($id_kegiatan, $group=NULL, $id_pilihan=NULL, $tahun=NULL, $is_tahun=NULL, $not_in=NULL){
		$th = "";
		$not = "";
		$group_by = "";
		$where_tambahan = "";
		$pilihan = array(
			'for_order' => array(
				'1' => 'kode_jenis_belanja, kode_kategori_belanja', 
				'2' => 'kode_jenis_belanja, kode_kategori_belanja, kode_sub_kategori_belanja', 
				'3' => 'kode_jenis_belanja, kode_kategori_belanja, kode_sub_kategori_belanja, kode_belanja', 
				'4' => 'kode_jenis_belanja, kode_kategori_belanja, kode_sub_kategori_belanja, kode_belanja, uraian_belanja',
				'5' => 'kode_jenis_belanja, kode_kategori_belanja, kode_sub_kategori_belanja, kode_belanja, uraian_belanja, detil_uraian_belanja'
			),
			'for_where' => array(
				'1' => 'AND kode_jenis_belanja = "'.$id_pilihan[1].'"',
				'2' => 'AND kode_jenis_belanja = "'.$id_pilihan[1].'" AND kode_kategori_belanja="'.$id_pilihan[2].'"',
				'3' => 'AND kode_jenis_belanja = "'.$id_pilihan[1].'" AND kode_kategori_belanja="'.$id_pilihan[2].'" AND kode_sub_kategori_belanja="'.$id_pilihan[3].'"',
				'4' => 'AND kode_jenis_belanja = "'.$id_pilihan[1].'" AND kode_kategori_belanja="'.$id_pilihan[2].'" AND kode_sub_kategori_belanja="'.$id_pilihan[3].'" AND kode_belanja="'.$id_pilihan[4].'"',
				'5' => 'AND kode_jenis_belanja = "'.$id_pilihan[1].'" AND kode_kategori_belanja="'.$id_pilihan[2].'" AND kode_sub_kategori_belanja="'.$id_pilihan[3].'" AND kode_belanja="'.$id_pilihan[4].'" AND uraian_belanja="'.$id_pilihan[5].'"'
			)
		);
		if (!empty($tahun)) {
			$th = " AND tahun = '".$tahun."' AND is_tahun_sekarang = '".$is_tahun."'";
		}
		if(!empty($not_in)){
			$not = " AND id <> '".$not_in."' ";
		}
		if (!empty($group)) {
			$group_by = 'GROUP BY '.$pilihan["for_order"][$group];
			$where_tambahan = $pilihan["for_where"][$group];
		}

		$query = "SELECT id ,tahun, id_renja,
						SUM(subtotal) AS sum_all,
						kode_sumber_dana AS kode_sumber_dana,(
							SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
						) AS Sumber_dana,
						kode_jenis_belanja AS kode_jenis_belanja, (
							SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
						) AS jenis_belanja,
						kode_kategori_belanja AS kode_kategori_belanja, (
							SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
						) AS kategori_belanja,
						kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
							SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
						) AS sub_kategori_belanja,
						kode_belanja AS kode_belanja,(
							SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
						) AS belanja,
						uraian_belanja, detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal, id_keg
						FROM tx_rka_belanja_kegiatan
						WHERE id_keg = '$id_kegiatan' ".$th." ".$not." ".$where_tambahan."
						".$group_by."
						ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC, uraian_belanja ASC, Sumber_dana ASC, detil_uraian_belanja ASC";

		$result = $this->db->query($query);
		return $result->result();
	}

	function sumber_dana_rekap($tahun, $id_skpd){
		return $this->db->query("SELECT * FROM (
			SELECT ref.id_sumber,
			ref.sumber_dana,
			SUM( IF( ref.tahun = '$tahun' AND ref.is_tahun_sekarang = 1, ref.subtotal, 0) ) AS 'tahun1',
			SUM( IF( ref.tahun = '$tahun' AND ref.is_tahun_sekarang = 0, ref.subtotal, 0) ) AS 'tahun2'
			FROM (SELECT id_keg, 
			(SELECT id_skpd FROM tx_rka_prog_keg WHERE id = id_keg) AS id_skpd
			,kode_sumber_dana AS id_sumber
			,(SELECT sumber_dana FROM m_sumber_dana WHERE id = id_sumber) AS sumber_dana
			,subtotal
			,tahun
			,is_tahun_sekarang
			FROM tx_rka_belanja_kegiatan AS ref1
			WHERE kode_jenis_belanja IS NOT NULL )
			AS ref INNER JOIN m_skpd
			ON ref.id_skpd = m_skpd.id_skpd
			WHERE ref.id_skpd = '$id_skpd'
			GROUP BY ref.id_sumber, ref.sumber_dana
			ORDER BY ref.id_sumber ASC) AS las
			WHERE las.tahun1 > 0 OR las.tahun2 > 0");	
	}

	function get_kegiatan_for_211_new($ta, $idK){
		$query = $this->db->query("SELECT kd_urusan AS kode_urusan, kd_bidang AS kode_bidang, kd_program AS kode_program, kd_kegiatan AS kode_kegiatan
			,(SELECT Kd_Fungsi FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang) AS kode_fungsi
			,(SELECT Nm_Fungsi FROM m_fungsi WHERE Kd_Fungsi = kode_fungsi) AS nama_fungsi
			,(SELECT Nm_Urusan FROM m_urusan WHERE Kd_Urusan = kode_urusan) AS nama_urusan
			,(SELECT Nm_Bidang FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang) AS nama_bidang
			,(SELECT Ket_Program FROM m_program WHERE Kd_Urusan = kode_urusan  AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program) AS nama_program
			,(SELECT Ket_Kegiatan FROM m_kegiatan WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program AND Kd_Keg = kode_kegiatan) AS nama_kegiatan
			,nominal,nominal_thndpn, parent, lokasi, id_skpd
			FROM tx_rka_prog_keg
			WHERE id = '$idK'");
		return $query->row();
	}

	function copy_belanja_kegiatan($keg_dari, $keg_tujuan){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->query("
			INSERT INTO  tx_rka_belanja_kegiatan (
			tahun,
			kode_urusan,
			kode_bidang,
			kode_program,
			kode_kegiatan,
			kode_sumber_dana,
			kode_jenis_belanja,
			kode_kategori_belanja,
			kode_sub_kategori_belanja,
			kode_belanja,
			uraian_belanja,
			detil_uraian_belanja,
			volume,
			nominal_satuan,
			subtotal,
			is_tahun_sekarang,
			id_keg,
			created_date)
			SELECT 
			tahun,
			kode_urusan,
			kode_bidang,
			kode_program,
			kode_kegiatan,
			kode_sumber_dana,
			kode_jenis_belanja,
			kode_kategori_belanja,
			kode_sub_kategori_belanja,
			kode_belanja,
			uraian_belanja,
			detil_uraian_belanja,
			volume,
			nominal_satuan,
			subtotal,
			is_tahun_sekarang,
			'$keg_tujuan',
			'".date('Y-m-d H:i:s')."'
			FROM tx_rka_belanja_kegiatan
			WHERE id_keg = '$keg_dari'
			");

		$total = $this->db->query("SELECT 
			SUM( IF(ref.is_tahun_sekarang = 1, ref.subtotal, 0)) AS total_skr,
			SUM( IF(ref.is_tahun_sekarang = 0, ref.subtotal, 0)) AS total_dpn
			FROM tx_rka_belanja_kegiatan AS ref
			WHERE id_keg = '$keg_tujuan'")->row();
		$this->db->query("UPDATE tx_rka_prog_keg SET nominal = '".$total->total_skr."', nominal_thndpn = '".$total->total_dpn."'
			WHERE id = '$keg_tujuan'");

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}
