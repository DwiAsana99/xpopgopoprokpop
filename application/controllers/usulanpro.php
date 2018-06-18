<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usulanpro extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance(); 
        parent::__construct();        
        $this->load->model(array('m_usulanpro_trx', 'm_groups', 'm_musrenbang', 'm_template_cetak', 'm_kecamatan', 'm_desa'));
        //$this->load->model(array('m_renstra_trx', 'm_skpd', 'm_template_cetak'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
	
		$this->auth->restrict();
	
        $data['url_add_data'] = site_url('usulanpro/edit_data');
        $data['url_load_data'] = site_url('usulanpro/load_data');
        $data['url_delete_data'] = site_url('usulanpro/delete_data');
        $data['url_edit_data'] = site_url('usulanpro/edit_data');
        $data['url_save_data'] = site_url('usulanpro/save_data');
		$data['url_show_gallery'] = site_url('usulanpro/show_gallery');
        
		$this->template->load('template','usulanpro/usulan_view',$data);
	
	}

	function load_view_tujuan(){
		$this->auth->restrict();
	
        $data['url_add_data'] = site_url('usulanpro/edit_data');
        $data['url_load_data'] = site_url('usulanpro/load_data_tujuan');
        $data['url_delete_data'] = site_url('usulanpro/delete_data');
        $data['url_edit_data'] = site_url('usulanpro/edit_data');
        $data['url_save_data'] = site_url('usulanpro/save_data');
		$data['url_show_gallery'] = site_url('usulanpro/show_gallery');
        
		$this->template->load('template','usulanpro/usulan_view_tujuan',$data);
	}

	## --------------------------------------------- ##
	## Tambah, Edit, Delete View Renstra setiap SKPD ##
	## --------------------------------------------- ##	

	function load_data_tujuan(){
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
		$usulanpro = $this->m_usulanpro_trx->get_all_usulan_tujuan($search, $start, $length, $order["0"], $order_arr);		

		$alldata = $this->m_usulanpro_trx->count_all_usulan_tujuan($search);		
		
		$data = array();
		$no=0;
		foreach ($usulanpro as $row) {
			$no++;
			//$preview_action = '<a href="javascript:void(0)" onclick="preview_modal('. $row->id .')" class="icon-search" title="Lihat Usulan"/>';
			$edit_action = '<a href="javascript:void(0)" onclick="edit_usulan_table('. $row->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Usulan"/>';
			$delete_action = '<a href="javascript:void(0)" onclick="delete_usulan_table('. $row->id_musrenbang .')" class="icon2-delete" title="Hapus Usulan"/>';
			//$history_action = '<a href="javascript:void(0)" onclick="preview_history('. $row->id .')" title="Preview History">'. $row->status_usulanpro .'</a>';
			$galery = '<a href="javascript:void(0)" onclick="show_gallery('. $row->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>';

			$action =		$edit_action.
							$delete_action.
							$galery;
			$data[] = array(
							$no, 
							$row->nama_group,
							// $row->nama_dewan, 
							// $row->nama_skpd,
							$row->nama_kec,
							$row->nama_desa,							
							$row->jenis_pekerjaan,
							$row->volume,
							Formatting::currency($row->jumlah_dana,2),
							$row->lokasi,
							$row->catatan,
							$action,
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
    }
	
	function load_data(){
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$order_arr = array('id_musrenbang', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan');
		$usulanpro = $this->m_usulanpro_trx->get_all_usulan($search, $start, $length, $order["0"], $order_arr);		

		$alldata = $this->m_usulanpro_trx->count_all_usulan($search);		
		
		$data = array();
		$no=0;
		foreach ($usulanpro as $row) {
			$no++;
			//$preview_action = '<a href="javascript:void(0)" onclick="preview_modal('. $row->id .')" class="icon-search" title="Lihat Usulan"/>';
			$edit_action = '<a href="javascript:void(0)" onclick="edit_usulan_table('. $row->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Usulan"/>';
			$delete_action = '<a href="javascript:void(0)" onclick="delete_usulan_table('. $row->id_musrenbang .')" class="icon2-delete" title="Hapus Usulan"/>';
			//$history_action = '<a href="javascript:void(0)" onclick="preview_history('. $row->id .')" title="Preview History">'. $row->status_usulanpro .'</a>';
			$galery = '<a href="javascript:void(0)" onclick="show_gallery('. $row->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>';

			$action =		$edit_action.
							$delete_action.
							$galery;
			$data[] = array(
							$no, 
							$row->nama_group,
							// $row->nama_dewan, 
							$row->nama_skpd,
							$row->nama_kec,
							$row->nama_desa,							
							$row->jenis_pekerjaan,
							$row->volume,
							Formatting::currency($row->jumlah_dana,2),
							$row->lokasi,
							$row->catatan,
							$action,
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
    }
    
	function edit_data($id=NULL){
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        $data['url_save_data'] = site_url('usulanpro/save_data');

		$data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_usulanpro_trx->get_data_with_rincian($id);
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data usulan tidak ditemukan.');
				redirect('usulanpro');
			}
			
            $data['id_musrenbang']			= $result->id_musrenbang;
            $data['id_groups']	= $result->id_groups;
    		$data['id_skpd'] 	= $result->id_skpd;
    		$data['id_kec'] 	= $result->id_kecamatan;
    		$data['id_desa'] 	= $result->id_desa;
    		$data['jenis_pekerjaan'] = $result->jenis_pekerjaan;
			
			$data['nama_group'] = $result->nama_group;
    		$data['nama_kec'] = $result->nama_kec;
    		$data['nama_skpd'] = $result->nama_skpd;
    		$data['nama_desa'] = $result->nama_desa;
			$data['nama_dewan'] = $result->nama_dewan;
    		
    		$data['volume'] = $result->volume;
    		$data['satuan'] = $result->satuan;
    		$data['lokasi'] = $result->lokasi;
    		$data['catatan'] = $result->catatan;
    		$data['jumlah_dana'] = $result->jumlah_dana;
			$data['isEdit']				= TRUE;
			$mp_filefiles				= $this->m_usulanpro_trx->get_file(explode( ',', $result->file), TRUE);		
			$data['mp_jmlfile']			= $mp_filefiles->num_rows();
			$data['mp_filefiles']		= $mp_filefiles->result();	
    
		}
        //var_dump($data);
    	$this->template->load('template','usulanpro/create', $data);
    }
	
	function get_data(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$id_group = $this->session->userdata('id_group');

		$order_arr = array('id','', 'nama_skpd','nama_kec','nama_desa', 'jenis_pekerjaan','status_usulanpro');
		$usulanpro = $this->m_usulanpro_trx->get_all_usulan($search, $start, $length, $order["0"], $id_group, $order_arr);					
		$alldata = $this->m_usulanpro_trx->count_all_usulan($search, $id_group);				
		$data = array();
		$no=0;
		
		foreach ($usulanpro as $row) {
			$no++;
			$preview_action = '<a href="javascript:void(0)" onclick="preview_modal('. $row->id .')" class="icon-search" title="Lihat Renstra"/>';
			$edit_action = '<a href="javascript:void(0)" onclick="edit_renstra('. $row->id .')" class="icon2-page_white_edit" title="Edit Renstra"/>';
			$delete_action = '<a href="javascript:void(0)" onclick="delete_renstra('. $row->id .')" class="icon2-delete" title="Hapus Renstra"/>';
			$history_action = '<a href="javascript:void(0)" onclick="preview_history('. $row->id .')" title="Preview History">'. $row->status_usulanpro .'</a>';

			if ($row->id_status == 1 || $row->id_status == 3) {
				//Baru dan Revisi			
				$action = 	$preview_action.
							$edit_action.
							$delete_action;
			}else{
				$action = $preview_action;
			}

			$data[] = array(
							$no, 
							$action, 
							$row->nama_skpd,
							$row->nama_kec,
							$row->nama_desa,							
							$row->jenis_pekerjaan,
							$row->voulme,
							$row->satuan,
							$row->lokasi,
							$history_action
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function cru($id_usulan=NULL){
		//$this->output->enable_profiler();
		$this->auth->restrict();

		$id_group = array('id_groups' => $this->session->userdata('id_group'));
		$group = $this->m_groups->get_groups_detail($id_group);		
		$data['group'] = $group->row();

		if (!empty($id)) {
			$id_group = $this->session->userdata('id_group');
			$result = $this->m_usulanpro_trx->get_one_usulan_detail($id_usulan, $id_group);
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data usulan tidak ditemukan.');
				redirect('usulanpro/usulan_view');
			}
			$data['usulanpro'] = $result;
		}

    	$this->template->load('template','usulanpro/create');
	}
	
	function delete_data(){
		$this->auth->restrict_ajax_login();

		$id = $this->input->post("id");
		$id_group = $this->session->userdata('id_group');
		$result = $this->m_usulanpro_trx->delete($id, $id_group);		
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data usulan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data usulan gagal dihapus, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}
	}
	
	function save(){
		$date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
		$id = $this->input->post('id_usulanpro');		
		 //action save 
        $call_from			= $this->input->post('call_from');
        $data_post = array(
            'tahun'             => $this->session->userdata('t_anggaran_aktif'),
            'id_groups'			=> $this->input->post('id_groups'),
    		// 'nama_dewan'	 	=> $this->input->post('id_groups')=='6'?$this->input->post('nama_dewan'):'',
    		'nama_dewan'	 	=> '',
    		'id_skpd'	 		=> $this->input->post('id_skpd'),
    		'id_kecamatan'		=> $this->input->post('id_kec'),
    		'id_desa'			=> $this->input->post('id_desa'),
    		'jenis_pekerjaan'	=> $this->input->post('jenis_pekerjaan'),
    		'volume'			=> $this->input->post('volume'),
    		'satuan'			=> $this->input->post('satuan'),
    		'lokasi'			=> $this->input->post('lokasi'),
            'catatan'			=> $this->input->post('catatan'),
            'jumlah_dana'		=> $this->input->post('jumlah_dana'),
            'id_asal_usulan'	=> $this->input->post('id_groups'),
            

        );

        if ($data_post['id_groups'] == '6') {
        	$is_pokir_ng = TRUE;
        }else{
        	$is_pokir_ng = FALSE;
        }
        
		if(strpos($call_from, 'usulanpro/edit_data') != FALSE) {
			$call_from = '';
		}
		
		$cekusulan = $this->m_usulanpro_trx->get_data(array('id_musrenbang'=>$id),'table_musrenbang');

        if(empty($cekusulan)) {
			$cekusulan = new stdClass();
			$id = '';
		}
		
		//Persiapan folder berdasarkan unit
		$dir_file_upload='file_upload/pokir';
		if (!file_exists($dir_file_upload)) {
		    mkdir($dir_file_upload, 0766, true);
		}
		//UPLOAD
		$this->load->library('upload');
		$config = array();
		$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size'] = '1024';
		$config['overwrite'] = FALSE;

		$id_userfile 	= $this->input->post("id_userfile");
		$name_file 	= $this->input->post("name_file");
		$ket_file	= $this->input->post("ket_file");		
	    $files = $_FILES;
	    $cpt = $this->input->post("upload_length");
	    	   	   
	    $hapus	= $this->input->post("hapus_file");	    
	    $name_file_arr = array();
	    $id_file_arr = array();		    

	    for($i=1; $i<=$cpt; $i++)
	    {	    	
	    	if (empty($files['userfile']['name'][$i]) && empty($id_userfile[$i])) {
	    		continue;
	    	}elseif (empty($files['userfile']['name'][$i]) && !empty($id_userfile[$i])) {
	    		$update_var = array('name'=> $name_file[$i],'ket'=>$ket_file[$i]);
	    		$this->m_usulanpro_trx->update_file($id_userfile[$i], $update_var);
	    		continue;
	    	}

	    	$file_name="pokir_".date("Ymd_His");	    	
	    	
	        $_FILES['userfile']['name']= $file_name."_".$files['userfile']['name'][$i];
	        $_FILES['userfile']['type']= $files['userfile']['type'][$i];
	        $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
	        $_FILES['userfile']['error']= $files['userfile']['error'][$i];
	        $_FILES['userfile']['size']= $files['userfile']['size'][$i];    

		    $this->upload->initialize($config);		    
		    $file = $this->upload->do_upload();
            //var_dump($this->upload->display_errors('<p>', '</p>'));	
            //var_dump($this->upload->data());	    
		    if ($file) {
		    	$file = $this->upload->data();
				$file = $file['file_name'];
				if (!empty($id_userfile[$i])) {
					$hapus[] = 	$id_userfile[$i];
				}
				$id_file_arr[] = $this->m_usulanpro_trx->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);
				$name_file_arr[] = $file;			
			} else {				
				// Error Occured in one of the uploads				
				if (empty($id) || (!empty($_FILES['userfile']['name']) && !empty($id))) {					
					foreach ($id_file_arr as $value) {
						$this->m_usulanpro_trx->delete_file($value);
					}
					foreach ($name_file_arr as $value) {
						unlink($directory.$value);
					}						
					$error_upload	= "Draft Usulan gagal disimpan, terdapat kesalahan pada upload file atau file upload tidak sesuai dengan ketentuan.";
					$this->session->set_userdata('msg_typ','err');
	            	$this->session->set_userdata('msg', $error_upload);				
					//var_dump($file);
                    redirect('home');			
				}
			}
		}

		if (!empty($cekusulan->file)) {
    		$id_file_arr_old = explode(",", $cekusulan->file);
    		if (!empty($hapus)) {
    			foreach ($hapus as $row) {			
					$key = array_search($row, $id_file_arr_old);
					unset($id_file_arr_old[$key]);

			    	$var_hapus = $this->m_usulanpro_trx->get_one_file($row);
			    	unlink(dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$var_hapus->location);
			    	$this->m_usulanpro_trx->delete_file($row);
			    }
    		}
		    foreach ($id_file_arr_old as $value) {
		    	$id_file_arr[] = $value;
		    }
	    }

	    if (!empty($id_file_arr)) {	    	
	    	$cekusulan->file = implode(",", $id_file_arr);
	    }
		
		$ret = TRUE;
		
		if(empty($id)) {
			//insert
            $data_post['created_by'] = $this->session->userdata('nama');
            $data_post['created_date'] = $date." ".$time;
			$data_post['file'] = $cekusulan->file;
			if ($is_pokir_ng) {
				$data_post['start_from'] = '2';
			}else{
				$data_post['start_from'] = '3';
			}
			$data_post['stat_skpd'] = '1';
			$data_post['stat_musren'] = '1';
			$data_post['stat_forum'] = '1';
			$data_post['stat_musrenkab'] = '1';
			$ret = $this->m_usulanpro_trx->insert($data_post,'table_musrenbang');
			//echo $this->db->last_query();
		} else {
			//update
            $data_post['changed_by'] = $this->session->userdata('nama');
            $data_post['changed_date'] = $date." ".$time;

			$ret = $this->m_usulanpro_trx->update($id,$data_post,'table_musrenbang','primary_musrenbang');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Usulan Gagal disimpan');						  
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Usulan Berhasil disimpan');
		}
        
        //var_dump($cekmusrenbang);
        
		if(!empty($call_from))
			redirect($call_from);
        
        redirect('usulanpro');
		//var_dump($cekbank);
		//print_r ($id_cek);
    }
	
	function preview_modal(){		
		$id_usulan = $this->input->post("id");

		$id_group = array('id_groups' => $this->session->userdata('id_group'));
		$group = $this->m_groups->get_groups_detail($id_group);		
		$data['group'] = $group->row();

		$result = $this->m_usulanpro_trx->get_one_usulan_detail($id_usulan);
		if (!empty($result)) {
			$data['usulanpro'] = $result;
			$this->load->view('usulanpro/preview', $data);	
		}		
	}
	
	function preview_history(){
		$id_usulan = $this->input->post("id");		
		$result = $this->m_usulanpro_trx->get_one_usulan($id_usulan);
		if (!empty($result)) {
			$data['usulanpro'] = $result;
			$this->load->view('usulanpro/preview_history', $data);
		}			
	}

	## -------------------------------------- ##
	## Pengiriman Renstra untuk Di Verifikasi ##
	## -------------------------------------- ##
	//KEBAWAH INI BELUM
	function send(){
		$this->auth->restrict();
		$id_skpd = $this->session->userdata('id_skpd');
		$data['json_id'] = $this->m_renstra_trx->get_all_id_renstra_veri_or_approved_to_json($id_skpd);
		$this->template->load('template','renstra/send/send', $data);
	}

	function get_data_send(){
		$id = $this->input->post("id");

		$id_skpd = array('id_skpd' => $this->session->userdata('id_skpd'));
		$skpd = $this->m_skpd->get_skpd_detail($id_skpd);
		$data['skpd'] = $skpd->row();
		
		$renstra = $this->m_renstra_trx->get_all_renstra_by_in($id, TRUE);
		$data['jml_data'] = $renstra->num_rows();
		$data['renstra'] = $renstra->result();
		$data['total_nominal_renstra'] = $this->m_renstra_trx->get_total_nominal_renstra_by_in($id);		
		$this->load->view('renstra/send/view', $data);
	}

	function pilih_renstra(){
		$this->load->view('renstra/send/pilih');
	}

	function get_data_pilih_renstra(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$renstras = $this->input->post("renstras");		

		$id_skpd = $this->session->userdata("id_skpd");		

		$order_arr = array('id','tujuan','sasaran','indikator_sasaran', '','nm_urusan','nm_bidang','ket_program','ket_kegiatan', 'status_renstra');
		$renstra = $this->m_renstra_trx->get_all_renstra($search, $start, $length, $order["0"], $id_skpd, $order_arr, "BARU");
		$alldata = $this->m_renstra_trx->count_all_renstra($search, $id_skpd, "BARU");		
		
		$data = array();
		$no=0;
		foreach ($renstra as $row) {			
			$no++;
			$checked = (!empty($renstras) && in_array($row->id, $renstras))?"checked":"";
			$data[] = array(
							$no, 
							$row->tujuan,
							$row->sasaran,
							$row->indikator_sasaran,
							$row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program.". ".$row->kd_kegiatan,
							$row->nm_urusan,
							$row->nm_bidang,
							$row->ket_program,
							$row->ket_kegiatan,
							$row->status_renstra,
							'<input type="checkbox" class="pilih_renstra" title="Pilih Renstra" value="'. $row->id .'" '. $checked .'/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function save_sended_renstra(){
		$id = $this->input->post("id");
		$id_skpd = $this->session->userdata("id_skpd");
		$result = $this->m_renstra_trx->send_renstra($id, $id_skpd);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data renstra berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data renstra gagal dikirm, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}
	}

	function delete_sended_renstra(){
		$id = $this->input->post("id");
		$id_skpd = $this->session->userdata("id_skpd");
		$result = $this->m_renstra_trx->delete_sended_renstra($id, $id_skpd);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data renstra berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data renstra gagal dihapus, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}	
	}

	private function cetak_func($data=NULL, $id_skpd=NULL, $page=1, $site_url=NULL, $search_skpd=FALSE){		
		$data_per_page = 25;
		$total = $this->m_renstra_trx->count_all_renstra(NULL, $id_skpd, "APPROVED");
		$total_page = round(($total/$data_per_page), 0, PHP_ROUND_HALF_UP);
		
		if ($page > $total_page || $page < 1) {
			$page = 1;
		}

		$start = $page-1;		

		$data['first_page'] = 1;
		$data['page'] = $page;
		$data['last_page'] = $total_page;
		$data['site_url'] = $site_url;
		$data['search_skpd'] = $search_skpd;
		$data['renstra'] = $this->m_renstra_trx->get_all_renstra(NULL, $start, $data_per_page, NULL, $id_skpd, NULL, "APPROVED", TRUE);		
		$data['total_nominal_renstra'] = $this->m_renstra_trx->get_total_nominal_renstra($id_skpd, "APPROVED");
		$this->template->load('template','renstra/cetak/view', $data);
	}

	function cetak_renstra($page=1){
		$this->auth->restrict();

		$id_skpd = array('id_skpd' => $this->session->userdata('id_skpd'));
		$skpd = $this->m_skpd->get_skpd_detail($id_skpd);
		$data['skpd'] = $skpd->row();

		$id_skpd = $this->session->userdata('id_skpd');
		$this->cetak_func($data, $id_skpd, $page, "renstra/cetak_renstra");
	}

	function cetak_renstra_all($page=1, $id_skpd="all"){
		$this->auth->restrict();
		
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL, TRUE);
		$data['dd_skpd'] = form_dropdown('ss_skpd', $all_skpd, $id_skpd, 'id="ss_skpd"');
		$data['id'] = $id_skpd;

		$this->cetak_func($data, $id_skpd, $page, "renstra/cetak_renstra_all", TRUE);
	}

	function do_cetak($id_skpd=NULL){
		$this->auth->restrict();
		if (empty($id_skpd)) {
			$id_skpd = $this->session->userdata('id_skpd');
		}

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");		
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		$data['renstra'] = $this->m_renstra_trx->get_all_renstra(NULL, NULL, NULL, NULL, $id_skpd, NULL, "APPROVED", TRUE);
		$html = $this->template->load('template_cetak', 'renstra/cetak/cetak', $data, true);
        $filename='Renstra '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';        
	    pdf_create($html, $filename, "A4", "Landscape");
	}

	## --------------------------------------- ##
	## Verifikasi Renstra yang telah di kirim  ##
	## --------------------------------------- ##
	function veri_view(){
		$this->auth->restrict();
		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL, TRUE);
		$data['dd_skpd'] = form_dropdown('ss_skpd', $all_skpd, NULL, 'id="ss_skpd"');
		$this->template->load('template','renstra/verifikasi/view', $data);
	}

	function get_veri_data(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$id_skpd = $this->input->post("ss_skpd");

		$order_arr = array('id','','nama_skpd','nama_koor','tujuan','sasaran','indikator_sasaran', '','nm_urusan','nm_bidang','ket_program','ket_kegiatan');
		$renstra = $this->m_renstra_trx->get_all_renstra($search, $start, $length, $order["0"], $id_skpd, $order_arr, "VERIFIKASI", TRUE);		
		$alldata = $this->m_renstra_trx->count_all_renstra($search, $start, $length, $order["0"], $id_skpd, "VERIFIKASI");
		
		$data = array();
		$no=0;
		foreach ($renstra as $row) {
			$no++;
			$preview_action = '<a href="javascript:void(0)" onclick="preview_modal('. $row->id .')" class="icon-search" title="Lihat Renstra"/>';
			$veri_action = '<a href="javascript:void(0)" onclick="veri_renstra('. $row->id .')" class="icon-edit" title="Verifikasi Renstra"/>';
			$history_action = '<a href="javascript:void(0)" onclick="preview_history('. $row->id .')" title="Preview History">'. $row->status_renstra .'</a>';

			$data[] = array(
							$no, 
							$preview_action.$veri_action,
							$row->nama_skpd,
							$row->nama_koor,
							$row->tujuan,
							$row->sasaran,
							$row->indikator_sasaran,
							$row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program.". ".$row->kd_kegiatan,
							$row->nm_urusan,
							$row->nm_bidang,
							$row->ket_program,
							$row->ket_kegiatan,
							$history_action
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function veri($id_renstra=NULL){
		$this->auth->restrict();

		$result = $this->m_renstra_trx->get_one_renstra_detail($id_renstra, "VERIFIKASI");
		if (empty($result)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'Data renstra tidak ditemukan.');
			redirect('renstra/home');
		}
		$data['renstra'] = $result;	
		$this->template->load('template','renstra/verifikasi/veri', $data);
	}

	function save_veri(){
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_renstra_trx->approved_renstra($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_renstra_trx->not_approved_renstra($id, $ket);
		}

		if ($result) {
			$this->session->set_userdata('msg_typ','ok');
			$this->session->set_userdata('msg', 'Renstra berhasil diverifikasi.');
			redirect('renstra/veri_view');
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'ERROR! Renstra gagal diverifikasi, mohon menghubungi administrator.');
			redirect('renstra/veri_view');
		}

	}
	
	function show_gallery(){
        $id = $this->input->post('id');
        $result = $this->db->query("SELECT file FROM t_usulanpro WHERE id=?", array($id));
        $id_photo = $result->row();                
        
        $this->db->where_in("id", explode(',',$id_photo->file));
        $this->db->from("t_upload_file");
        $result = $this->db->get();
        $result = $result->result();
        //print_r($result);
        $arr = array();
        $i=0;
        foreach($result as $results){
            $arr[$i]['href'] = base_url().$results->location;
            $arr[$i]['title'] = $results->name;
            $i++;
        }
        //print_r($arr);
        /*$arr = array();
        $arr[0]['href'] = '1_b.jpg';
        $arr[1]['href'] = '2_b.jpg';
        $arr[2]['href'] = '3_b.jpg';
        */
        echo json_encode($arr);
    }

    function lihat_usulan_pokir(){
    	$ta = $this->session->userdata('t_anggaran_aktif');
    	$data['pokir'] = $this->m_usulanpro_trx->get_pokir_cetak($ta);

    	$this->template->load('template','usulanpro/view_pokir', $data);
    }

    function cetak_usulan_pokir(){
    	$ta = $this->session->userdata('t_anggaran_aktif');
    	$data['pokir'] = $this->m_usulanpro_trx->get_pokir_cetak($ta);
    	$data['ta'] = $ta;

    	$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);

		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Daftar Usulan Pokir '. date("d-m-Y H-i-s"), 2);

    	$html = $this->load->view('usulanpro/cetak/cetak_pokir', $data, TRUE);

    	// print_r($html);
	 	$this->create_pdf->load_ng($html,'Daftar Usulan Pokir_'.date("d-m-Y_H:i:s"), 'A4-L','');
    }


    ## ------------------------ ##
	## Untuk rekap semua usulan ##
	## ------------------------ ##

    function rekap_all_usulan(){
		$this->auth->restrict();
		$id_group = $this->session->userdata('id_group');
		// $ta = $this->session->userdata('t_anggaran_aktif');
		if (empty($id_group)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk melihat Rekap Usulan, mohon menghubungi administrator.');
			redirect('home');
		}

		$cb_kecamatan = array("all" => "Semua Kecamatan");
		foreach ($this->m_kecamatan->get_kec_dee() as $row) {
			$cb_kecamatan[$row->id] = $row->nama; 
		}

		$cb_desa = array("all" => "Semua Desa");
		foreach ($this->m_desa->get_desa_dee('all') as $row) {
			$cb_desa[$row->id] = $row->label;
		}

		$data['cb_kecamatan'] = form_dropdown('cb_kecamatan', $cb_kecamatan, NULL, 'data-placeholder="Pilih Kecamatan" class="common chosen-select" id="cb_kecamatan"');
		$data['cb_desa'] = form_dropdown('cb_desa', $cb_desa, NULL, 'data-placeholder="Pilih Desa" class="common chosen-select" id="cb_desa"');

		$this->template->load('template', 'usulanpro/preview_rekap_usulan', $data);
    }

    function isi_all_usulan($ta){
		$id_usulan = $this->input->post('id_usulan');
		$id_status = $this->input->post('id_status');
		$id_kec = $this->input->post('id_kec');
		$id_desa = $this->input->post('id_desa');

		//for jenis usulan
		if($id_usulan == "all") {
			$id_groups = NULL;
		}elseif($id_usulan == 1) {
			$id_groups = "AND id_groups = 6";
		}elseif ($id_usulan == 2) {
			$id_groups = "AND (id_groups = 4 OR id_groups = 5)";
		}elseif ($id_usulan == 3){
			$id_groups = "AND id_groups = 3";				
		}elseif($id_usulan == 4){
			$id_groups = "AND id_groups = 5";
		}
		//for status (belum ditentukan(1)/terakomodir(2)/tidak terakomodir(3))
		if ($id_status == "all") {
			$status = NULL;
		}else{
			$status = "AND id_keputusan = ".$id_status;
		}
		//for kecamatan
		if($id_kec == 'all'){
			$kec = NULL;
		}else{
			$kec = "AND id_kecamatan = ".$id_kec;
		}
		//for desa
		if($id_desa == 'all'){				
			$desa = NULL;
		}else{
			$desa = "AND id_desa = ".$id_desa;
		}

		// $data2['urusan'] = $this->m_usulanpro_trx->get_allusulan_urusan($ta,$id_groups,$status,$kec,$desa);
		// $data2['urusan'] = $this->db->query("SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
		// 	SELECT *, SUM(jumlah_dana) AS sum_jumlah_dana FROM t_musrenbang
		// 	WHERE tahun = ".$ta."
		// 		AND flag_delete = 0
		// 		".$id_groups."
		// 		".$status."
		// 		".$kec."
		// 		".$desa."
		// 	GROUP BY kd_urusan
		// 	ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
		// ) AS t
		// LEFT JOIN m_urusan AS u ON t.kd_urusan = u.Kd_Urusan");
// print($this->db->last_query());exit;

		$data2['ta'] = $ta;
		$data2['id_groups'] = $id_groups;
		$data2['status'] = $status;
		$data2['kec'] = $kec;
		$data2['desa'] = $desa;
		// $data['usulan'] = $this->load->view('usulanpro/cetak/usulan_tak_terakomodir_all', $data2);
		return $this->load->view('usulanpro/cetak/isi_rekap_usulan', $data2);
    }


}