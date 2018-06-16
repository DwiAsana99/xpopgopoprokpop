<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Data extends CI_Controller
	{
		var $CI = NULL;
		public function __construct()
		{
			$this->CI =& get_instance(); 
	        parent::__construct();    
	        $this->load->database();
	        $this->load->model(array('m_data','m_urusan', 'm_bidang', 'm_program', 'm_kegiatan','m_skpd','m_settings'));
	        if (!empty($this->session->userdata("db_aktif"))) {
	            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
	        }
	    }
//------------------------------------------------------FUNGSI UNTUK MASTER URUSAN-------------------------------------------//
	    function view_urusan()
	    {
	    	$this->auth->restrict();
	    	$data['urusan'] = $this->m_urusan->get_urusan();
	    	$this->template->load('template','data/urusan_view', $data);
	    }

	    function cru_urusan()
		{
			$id = $this->input->post('idur');
			if (!empty($id)) {
				$data['urusan'] = $this->m_urusan->get_urusan_by_kode($id);
			}
			$this->load->view('data/cru_urusan', $data);
		}

		function save_urusan()
		{
			$id_counter = $this->input->post('id_counter');
			$id = $this->input->post('id');
			$nama = $this->input->post('nama');

			if (!empty($id_counter)) {
				$result = $this->m_data->save_urusan($id, $nama, 'edit');
				$msg = array('success' => '1', 'msg' => 'Data Urusan berhasil diubah.');
			}else{
				$result = $this->m_data->save_urusan($id, $nama, 'add');
				$msg = array('success' => '1', 'msg' => 'Data Urusan berhasil dibuat.');
			}

			if ($result) {
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data Urusan gagal dibuat, mohon menghubungi administrator.');
				echo json_encode($msg);
			}
		}

		function delete_urusan()
		{
			$id = $this->input->post('idur');

			$result = $this->m_data->delete_urusan($id);

			if ($result) {
				$msg = array('success' => '1', 'msg' => 'Data Urusan berhasil dihapus.');
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data Urusan gagal dihapus, mohon menghubungi administrator.');
				echo json_encode($msg);
			}
		}

//------------------------------------------------------FUNGSI UNTUK MASTER BIDANG-------------------------------------------//
	    function view_bidang()
	    {
			$this->auth->restrict();
			$data['id_urusan'] = $this->input->post('idur');
			$data['bidang'] = $this->m_bidang->get_data_bidang($data['id_urusan'])->result();
	    	$this->load->view('data/bidang_view', $data);
	    }

	    function cru_bidang()
		{
			$id_urusan = $this->input->post('idur');
			$id = $this->input->post('idbi');

			$kd_fungsi_edit = NULL;
			if (!empty($id)) {
				$data['bidang'] = $this->m_bidang->get_data_bidang(NULL, $id)->row();
				$data['id'] = $id;
				$kd_fungsi_edit = $data['bidang']->Kd_Fungsi;
			}
			
			$kd_fungsi = array('' => '');
			foreach ($this->m_bidang->get_data_fungsi()->result() as $row) {
				$kd_fungsi[$row->Kd_Fungsi] = $row->Nm_Fungsi;
			}

			$data['fungsi'] = form_dropdown('kd_fungsi', $kd_fungsi, $kd_fungsi_edit, 'data-placeholder="Pilih Fungsi" class="common chosen-select" id="kd_fungsi"');
			$data['urusan'] = $this->m_urusan->get_urusan_by_kode($id_urusan);

			$this->load->view('data/cru_bidang', $data);
		}

		function save_bidang()
		{
			$id 		 	= $this->input->post('id');
			$kd_urusan		= $this->input->post('kd_urusan');
			$kd_bidang	 	= $this->input->post('kd_bidang');
			$nm_bidang	 	= $this->input->post('nm_bidang');
			$kd_fungsi 		= $this->input->post('kd_fungsi');

			$data_bidang = array('kd_urusan' => $kd_urusan, 'kd_bidang' => $kd_bidang, 'nm_bidang' => $nm_bidang, 'kd_fungsi' => $kd_fungsi);

			if(empty($id)) {
				$ret = $this->m_data->simpan_bidang($data_bidang);
				$msg = array('success' => '1', 'msg' => 'Data Bidang berhasil dibuat.');
			} else {
				$ret = $this->m_data->update_bidang($data_bidang, $id, 'table_bidang', 'primary_bidang');
				$msg = array('success' => '1', 'msg' => 'Data Bidang berhasil diubah.');
			}

			if ($ret) {
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data Bidang gagal dibuat, mohon menghubungi administrator.');
				echo json_encode($msg);
			}
		}

	    function load_bidang()
	    {
	    	$search = $this->input->post("search");
			$start = $this->input->post("start");
			$length = $this->input->post("length");
			$order = $this->input->post("order");
			
			$bidang = $this->m_data->get_data_table_bidang($search, $start, $length, $order["0"]);		
			$alldata = $this->m_data->count_data_table_bidang($search, $start, $length, $order["0"]);

			$data = array();
			$no=0;


			foreach ($bidang as $row) {
				$no++;
				$data[] = array(
								$no, 
								$row->Kd_Urusan,
								$row->Nm_Urusan,
								$row->Kd_Bidang,
								$row->Nm_Bidang,
								$row->Kd_Fungsi,
								$row->Nm_Fungsi,
								'<a href="javascript:void(0)" onclick="edit_bidang('. $row->id .')" class="icon2-page_white_edit" title="Edit Bidang"/>
								<a href="javascript:void(0)" onclick="delete_bidang('. $row->id .')" class="icon2-delete" title="Hapus Bidang"/>'
								);
			}
			$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
			
	        echo json_encode($json);
	    }

	    function edit_bidang($id)
		{
			//$this->output->enable_profiler(TRUE);
	        $this->auth->restrict();
	        //$data['url_save_data'] = site_url('cik/save_cik');

	        $data['isEdit'] = FALSE;
	        if (!empty($id)) {
	            $data_ = array('id'=>$id);
	            $result = $this->m_data->get_data_with_rincian($id,'table_bidang');
				if (empty($result)) {
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data bidang tidak ditemukan.');
					redirect('data/view_bidang');
				}
				
	            $data['id']					= $result->id;
	    		$data['kd_bidang'] 			= $result->Kd_Bidang;
	    		$data['nm_bidang'] 			= $result->Nm_Bidang;
	    		$data['kd_fungsi']			= $result->Kd_Fungsi;

	            $data['isEdit']				= TRUE;         
	            
	            $kd_urusan_edit = $result->Kd_Urusan;
	    
	            //prepare combobox
	    		$kd_urusan = array("" => "");
	    		foreach ($this->m_urusan->get_urusan() as $row) {
	    			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
	    		}
	    
	    
	    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
	    
			}
	        $this->template->load('template','data/cru_bidang',$data);
	        
		}

		function delete_bidang() 
		{  
	        $id = $this->input->post('idbi');
	        
	        $result = $this->m_data->delete_bidang($id);
	        if ($result) {
				$msg = array('success' => '1', 'msg' => 'Data bidang berhasil dihapus.');
				echo json_encode($msg);			
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data bidang gagal dihapus, mohon menghubungi administrator.');
				echo json_encode($msg);			
			}
		}
//------------------------------------------------AKHIR FUNGSI UNTUK MASTER BIDANG-------------------------------------------//

//---------------------------------------------------FUNGSI UNTUK MASTER PROGRAM---------------------------------------------//
		function view_program()
	    {
			$this->auth->restrict();
			$id_bidang = $this->input->post('idbi');
			$bidang = $this->m_bidang->get_data_bidang(NULL, $id_bidang)->row();

			$data['id_urusan'] = $bidang->Kd_Urusan;
			$data['kd_bidang'] = $bidang->Kd_Bidang;
			$data['id_bidang'] = $id_bidang;
			$data['program'] = $this->m_program->get_data_program($bidang->Kd_Urusan, $bidang->Kd_Bidang)->result();
	    	$this->load->view('data/program_view', $data);
	    }

	    function cru_program()
		{
			$id_urusan = $this->input->post('idur');
			$id_bidang = $this->input->post('idbi');
			$id_prog = $this->input->post('idpr');

			if (!empty($id_prog)) {
				$data['id'] = $id_prog;
				$data['program'] = $this->m_program->get_data_program(NULL, NULL, $id_prog)->row();
			}

			$data['bidang'] = $this->m_bidang->get_data_bidang(NULL, $id_bidang)->row();
			$this->load->view('data/cru_program', $data);
		}

		function check_program(){
			$id_prog = $this->input->post('id_prog');
			$kd_urusan = $this->input->post('kd_urusan');
			$kd_bidang = $this->input->post('kd_bidang');
			$kd_prog = $this->input->post('kd_prog');

			$checked = $this->db->query('SELECT * FROM m_program WHERE kd_urusan = "'.$kd_urusan.'" AND kd_bidang = "'.$kd_bidang.'" AND kd_prog = "'.$kd_prog.'"')->num_rows();

			if ($checked == 0 || $id_prog != "") {
				echo json_encode(1);
			}else{
				echo json_encode(0);
			}
		}

		function save_program()
		{
			$id 		 	= $this->input->post('id');
			// $call_from		= $this->input->post('call_from');
			$kd_urusan		= $this->input->post('kd_urusan');
			$kd_bidang	 	= $this->input->post('kd_bidang');
			$kd_prog	 	= $this->input->post('kd_prog');
			$ket_program 	= $this->input->post('ket_program');

			// if(strpos($call_from, 'data/cru_program') != FALSE) {
			// 	$call_from = '';
			// }

			// $data_program = $this->m_data->get_program_by_id($id);
			// if(empty($data_program)) {
			// 	//cek bank baru
			// 	$data_program = new stdClass();
			// 	$id = '';
			// }

			// $data_program->id				= $id;
			// $data_program->Kd_Urusan		= $kd_urusan;
			// $data_program->Kd_Bidang		= $kd_bidang;
			// $data_program->Kd_Prog  		= $kd_prog;
			// $data_program->Ket_Program 		= $ket_program;

			$data_program = array('kd_urusan' => $kd_urusan, 'kd_bidang' => $kd_bidang, 'kd_prog' => $kd_prog, 'ket_program' => $ket_program);

			if(empty($id)) {
				$ret = $this->m_data->simpan_program($data_program);
				$msg = array('success' => '1', 'msg' => 'Data Program berhasil dibuat.');
			} else {
				$ret = $this->m_data->update_program($data_program, $id, 'table_program', 'primary_program');
				$msg = array('success' => '1', 'msg' => 'Data Program berhasil diubah.');
			}

			if ($ret) {
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data Program gagal dibuat, mohon menghubungi administrator.');
				echo json_encode($msg);
			}
		}

	    function load_program()
	    {
	    	$search = $this->input->post("search");
			$start = $this->input->post("start");
			$length = $this->input->post("length");
			$order = $this->input->post("order");
			
			$program = $this->m_data->get_data_table_program($search, $start, $length, $order["0"]);		
			$alldata = $this->m_data->count_data_table_program($search, $start, $length, $order["0"]);

			$data = array();
			$no=0;


			foreach ($program as $row) {
				$no++;
				$data[] = array(
								$no,
								$row->Kd_Urusan, 
								$row->Nm_Urusan,
								$row->Kd_Bidang,
								$row->Nm_Bidang,
								$row->Kd_Prog,
								$row->Ket_Program,
								'<a href="javascript:void(0)" onclick="edit_program('. $row->id .')" class="icon2-page_white_edit" title="Edit Bidang"/>
								<a href="javascript:void(0)" onclick="delete_program('. $row->id .')" class="icon2-delete" title="Hapus Bidang"/>'
								);
			}
			$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
			
	        echo json_encode($json);
	    }

	    function edit_program($id)
		{
			$this->output->enable_profiler(TRUE);
	        $this->auth->restrict();
	        //$data['url_save_data'] = site_url('cik/save_cik');

	        $data['isEdit'] = FALSE;
	        if (!empty($id)) {
	            $data_ = array('id'=>$id);
	            $result = $this->m_data->get_data_with_rincian($id,'table_program');
				if (empty($result)) {
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data program tidak ditemukan.');
					redirect('data/view_program');
				}
				
	            $data['id']					= $result->id;
	    		$data['kd_prog'] 			= $result->Kd_Prog;
	    		$data['ket_program'] 		= $result->Ket_Program;

	            $data['isEdit']				= TRUE;         
	            
	            $kd_urusan_edit = $result->Kd_Urusan;
	            $kd_bidang_edit = $result->Kd_Bidang;
	    
	            //prepare combobox
	    		// $kd_urusan = array("" => "");
	    		foreach ($this->m_urusan->get_urusan() as $row) {
	    			if ($row->id == $kd_urusan_edit) {
		    			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
	    			}
	    		}
	    		// $kd_bidang = array("" => "");
	    		foreach ($this->m_bidang->get_bidang($result->Kd_Urusan) as $row) {
	    			if ($row->id == $kd_bidang_edit) {
	    				$kd_bidang[$row->id] = $row->id .". ". $row->nama;
	    			}
	    		}
	    
	    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
	    		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
	    
			}
	        $this->template->load('template','data/cru_program',$data);
	        
		}

		function delete_program() 
		{  
	        $id = $this->input->post('idpr');
	        
	        $result = $this->m_data->delete_program($id);
	        if ($result) {
				$msg = array('success' => '1', 'msg' => 'Data program berhasil dihapus.');
				echo json_encode($msg);			
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data program gagal dihapus, mohon menghubungi administrator.');
				echo json_encode($msg);			
			}
		}
//------------------------------------------------AKHIR FUNGSI UNTUK MASTER PROGRAM-------------------------------------------//

//------------------------------------------------- FUNGSI UNTUK MASTER KEGIATAN----------------------------------------------//
		function view_kegiatan()
	    {
			//$this->output->enable_profiler(TRUE);
			$this->auth->restrict();
			$id_prog = $this->input->post('idpr');
			$program = $this->m_program->get_data_program(NULL, NULL, $id_prog)->row();

			$data['id_urusan'] = $program->Kd_Urusan;
			$data['kd_bidang'] = $program->Kd_Bidang;
			$data['kd_prog'] = $program->Kd_Prog;
			$data['id_prog'] = $id_prog;
			$data['kegiatan'] = $this->m_kegiatan->get_data_kegiatan($program->Kd_Urusan, $program->Kd_Bidang, $program->Kd_Prog)->result();

	    	$this->load->view('data/kegiatan_view', $data);
	    }

	    function cru_kegiatan()
		{
			$id_urusan = $this->input->post('idur');
			$id_bidang = $this->input->post('idbi');
			$id_prog = $this->input->post('idpr');
			$id_keg = $this->input->post('idkg');

			if (!empty($id_keg)) {
				$data['id'] = $id_keg;
				$data['kegiatan'] = $this->m_kegiatan->get_data_kegiatan(NULL, NULL, NULL, $id_keg)->row();
			}

			$data['program'] = $this->m_program->get_data_program(NULL, NULL, $id_prog)->row();
			$this->load->view('data/cru_kegiatan', $data);
		}

		function check_kegiatan(){
			$id_keg = $this->input->post('id_keg');
			$kd_urusan = $this->input->post('kd_urusan');
			$kd_bidang = $this->input->post('kd_bidang');
			$kd_prog = $this->input->post('kd_prog');
			$kd_keg = $this->input->post('kd_keg');

			$checked = $this->db->query('SELECT * FROM m_kegiatan WHERE kd_urusan = "'.$kd_urusan.'" AND kd_bidang = "'.$kd_bidang.'" AND kd_prog = "'.$kd_prog.'" AND kd_keg = "'.$kd_keg.'"')->num_rows();

			if ($checked == 0 || $id_keg != "") {
				echo json_encode(1);
			}else{
				echo json_encode(0);
			}
		}

		function save_kegiatan()
		{
			$id 		 	= $this->input->post('id');
			// $call_from		= $this->input->post('call_from');
			$kd_urusan		= $this->input->post('kd_urusan');
			$kd_bidang	 	= $this->input->post('kd_bidang');
			$kd_prog	 	= $this->input->post('kd_prog');
			$kd_keg		 	= $this->input->post('kd_keg');
			$ket_kegiatan 	= $this->input->post('ket_kegiatan');

			$data_kegiatan = array('kd_urusan' => $kd_urusan, 'kd_bidang' => $kd_bidang, 'kd_prog' => $kd_prog, 'kd_keg' => $kd_keg, 'ket_kegiatan' => $ket_kegiatan);
			
			if(empty($id)) {
				$ret = $this->m_data->simpan_kegiatan($data_kegiatan);
				$msg = array('success' => '1', 'msg' => 'Data Kegiatan berhasil dibuat.');
			} else {
				$ret = $this->m_data->update_kegiatan($data_kegiatan, $id, 'table_kegiatan', 'primary_kegiatan');
				$msg = array('success' => '1', 'msg' => 'Data Kegiatan berhasil diubah.');
			}

			if ($ret) {
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data Program gagal dibuat, mohon menghubungi administrator.');
				echo json_encode($msg);
			}
		}

	    function load_kegiatan()
	    {
	    	
	    	$search = $this->input->post("search");
			$start = $this->input->post("start");
			$length = $this->input->post("length");
			$order = $this->input->post("order");
			
			$kegiatan = $this->m_data->get_data_table_kegiatan($search, $start, $length, $order["0"]);		
			$alldata = $this->m_data->count_data_table_kegiatan($search, $start, $length, $order["0"]);

			$data = array();
			$no=0;


			foreach ($kegiatan as $row) {
				
				$no++;
				$data[] = array(
								$no, 
								$row->Kd_Urusan,
								$row->Nm_Urusan,
								$row->Kd_Bidang,
								$row->Nm_Bidang,
								$row->Kd_Prog,
								$row->Ket_Program,
								$row->Kd_Keg,
								$row->Ket_Kegiatan,
								'<a href="javascript:void(0)" onclick="edit_kegiatan('. $row->id .')" class="icon2-page_white_edit" title="Edit Bidang"/>
								<a href="javascript:void(0)" onclick="delete_kegiatan('. $row->id .')" class="icon2-delete" title="Hapus Bidang"/>'
								);
			}
			$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
			
	        echo json_encode($json);
	    }

	    function edit_kegiatan($id)
		{
			$this->output->enable_profiler(TRUE);
	        $this->auth->restrict();
	        //$data['url_save_data'] = site_url('cik/save_cik');

	        $data['isEdit'] = FALSE;
	        if (!empty($id)) {
	            $data_ = array('id'=>$id);
	            $result = $this->m_data->get_data_with_rincian($id,'table_kegiatan');
				if (empty($result)) {
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data Kegiatan tidak ditemukan.');
					redirect('data/view_kegiatan');
				}
				
	            $data['id']					= $result->id;
	    		$data['kd_keg'] 			= $result->Kd_Keg;
	    		$data['ket_kegiatan'] 		= $result->Ket_Kegiatan;

	            $data['isEdit']				= TRUE;         
	            
	            $kd_urusan_edit = $result->Kd_Urusan;
	            $kd_bidang_edit = $result->Kd_Bidang;
	    		$kd_program_edit = $result->Kd_Prog;

	            //prepare combobox
	    		// $kd_urusan = array("" => "");
	    		foreach ($this->m_urusan->get_urusan() as $row) {
	    			if ($row->id == $kd_urusan_edit) {
	    				$kd_urusan[$row->id] = $row->id .". ". $row->nama;
	    			}
	    		}
	    		// $kd_bidang = array("" => "");
	    		foreach ($this->m_bidang->get_bidang($result->Kd_Urusan) as $row) {
	    			if ($row->id == $kd_bidang_edit) {
	    				$kd_bidang[$row->id] = $row->id .". ". $row->nama;
	    			}
	    		}
	    		// $kd_program = array("" => "");
	    		foreach ($this->m_program->get_prog($result->Kd_Urusan,$result->Kd_Bidang) as $row) {
	    			if ($row->id == $kd_program_edit) {
	    				$kd_program[$row->id] = $row->id .". ". $row->nama;
	    			}
	    		}
	    
	    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
	    		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
	    		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
    		
	    
			}
	        $this->template->load('template','data/cru_kegiatan',$data);
	        
		}

		function delete_kegiatan() 
		{  
	        $id = $this->input->post('idkg');
	        
	        $result = $this->m_data->delete_kegiatan($id);
	        if ($result) {
				$msg = array('success' => '1', 'msg' => 'Data Kegiatan berhasil dihapus.');
				echo json_encode($msg);			
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data Kegiatan gagal dihapus, mohon menghubungi administrator.');
				echo json_encode($msg);			
			}
		}
//------------------------------------------------AKHIR FUNGSI UNTUK MASTER KEGIATAN------------------------------------------//

		function form_hapus($cont){
			$jenis = "";
			$query = "";

			if ($cont == 'renstra') {
				$jenis = 'Renstra';
				$query = 'INNER JOIN t_renstra_prog_keg ON m_skpd.id_skpd = t_renstra_prog_keg.id_skpd';
			}elseif ($cont == 'renja') {
				$jenis = 'Renja';
				$query = 'INNER JOIN t_renja_prog_keg ON m_skpd.id_skpd = t_renja_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_renja ON m_skpd.id_skpd = t_renja.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'ppas') {
				$jenis = 'PPAS';
				$query = 'INNER JOIN t_ppas_prog_keg ON m_skpd.id_skpd = t_ppas_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_ppas ON m_skpd.id_skpd = t_ppas.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'rka') {
				$jenis = 'RKA';
				$query = 'INNER JOIN tx_rka_prog_keg ON m_skpd.id_skpd = tx_rka_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_rka ON m_skpd.id_skpd = tx_rka.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'dpa') {
				$jenis = 'DPA';
				$query = 'INNER JOIN tx_dpa_prog_keg ON m_skpd.id_skpd = tx_dpa_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_dpa ON m_skpd.id_skpd = tx_dpa.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'cik') {
				$jenis = 'CIK';
				$query = 'INNER JOIN tx_cik_prog_keg ON m_skpd.id_skpd = tx_cik_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_cik ON m_skpd.id_skpd = tx_cik.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'renja_perubahan') {
				$jenis = 'Remja Perubahan';
				$query = 'INNER JOIN t_renja_prog_keg_perubahan ON m_skpd.id_skpd = t_renja_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_renja_perubahan ON m_skpd.id_skpd = t_renja_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'ppas_perubahan') {
				$jenis = 'PPAS Perubahan';
				$query = 'INNER JOIN t_ppas_prog_keg_perubahan ON m_skpd.id_skpd = t_ppas_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_ppas_perubahan ON m_skpd.id_skpd = t_ppas_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'rka_perubahan') {
				$jenis = 'RKA Perubahan';
				$query = 'INNER JOIN tx_rka_prog_keg_perubahan ON m_skpd.id_skpd = tx_rka_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_rka_perubahan ON m_skpd.id_skpd = tx_rka_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'dpa_perubahan') {
				$jenis = 'DPA Perubahan';
				$query = 'INNER JOIN tx_dpa_prog_keg_perubahan ON m_skpd.id_skpd = tx_dpa_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_dpa_perubahan ON m_skpd.id_skpd = tx_dpa_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'cik_perubahan') {
				$jenis = 'CIK Perubahan';
				$query = 'INNER JOIN tx_cik_prog_keg_perubahan ON m_skpd.id_skpd = tx_cik_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_cik_perubahan ON m_skpd.id_skpd = tx_cik_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}

			if (!empty($query)) {
				$result = $this->db->query('SELECT m_skpd.id_skpd, nama_skpd, m_skpd.kode_unit FROM m_skpd '.$query.' 
				UNION 
				SELECT m_skpd.id_skpd, nama_skpd, m_skpd.kode_unit FROM m_skpd '.$query2.' GROUP BY m_skpd.id_skpd')->result();
			}else{
				$result = array();
			}

			$data['jenis'] = $jenis;
			$data['cont'] = $cont;
			$data['result'] = $result;
			// print_r($this->db->last_query());
			$this->template->load('template', 'data/form_hapus', $data);
		}

		function do_hapus(){
			$id_skpd = $this->input->post('id_skpd');
			$jenis = $this->input->post('jenis');
			$cont = $this->input->post('cont');
			$tahun = $this->m_settings->get_tahun_anggaran();
			$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
			if ($kode_unit == $id_skpd) {
				$induk = TRUE;
			}else{
				$induk = FALSE;
			}

			if ($cont == 'renstra') {
				$result = $this->m_data->ubah_status_renstra($id_skpd, $tahun);
			}elseif ($cont == 'renja') {
				$result = $this->m_data->hapus_data_renja($id_skpd, $tahun, $induk);
			}elseif ($cont == 'ppas') {
				$result = $this->m_data->hapus_data_ppas($id_skpd, $tahun, $induk);
			}elseif ($cont == 'rka') {
				$result = $this->m_data->hapus_data_rka($id_skpd, $tahun, $induk);
			}elseif ($cont == 'dpa') {
				$result = $this->m_data->hapus_data_dpa($id_skpd, $tahun, $induk);
			}elseif ($cont == 'cik') {
				$result = $this->m_data->hapus_data_cik($id_skpd, $tahun, $induk);
			}elseif ($cont == 'renja_perubahan') {
				$result = $this->m_data->hapus_data_renja_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'ppas_perubahan') {
				$result = $this->m_data->hapus_data_ppas_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'rka_perubahan') {
				$result = $this->m_data->hapus_data_rka_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'dpa_perubahan') {
				$result = $this->m_data->hapus_data_dpa_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'cik_perubahan') {
				$result = $this->m_data->hapus_data_cik_perubahan($id_skpd, $tahun, $induk);
			}

			if ($result) {
				if ($cont == 'resntra') {
					$msg = array('success' => '1', 'msg' => 'Status Rensta berhasil diubah.');
				}else{
					$msg = array('success' => '1', 'msg' => 'Data '.$jenis.' berhasil dihapus.');
				}
				
				echo json_encode($msg);
			}else{
				if ($cont == 'resntra') {
					$msg = array('success' => '0', 'msg' => 'ERROR! Status Renstra gagal diubah, mohon menghubungi administrator.');
				}else{
					$msg = array('success' => '0', 'msg' => 'ERROR! Data '.$jenis.' gagal dihapus, mohon menghubungi administrator.');
				}
				echo json_encode($msg);
			}

		}

	}
?>