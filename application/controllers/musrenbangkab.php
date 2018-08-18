<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class musrenbangkab extends CI_Controller {

    var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model(array('m_musrenbang','m_lov','m_template_cetak','m_desa','m_skpd','m_kecamatan'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

    public function index()
    {
        $this->auth->restrict();
        $data['url_add_data'] = site_url('musrenbangkab/edit_data');
        $data['url_load_data'] = site_url('musrenbangkab/load_data');
        $data['url_delete_data'] = site_url('musrenbangkab/delete_data');
        $data['url_edit_data'] = site_url('musrenbangkab/edit_data');
        $data['url_save_data'] = site_url('musrenbangkab/save_data');
        $data['url_data_list_musrenbangkab'] = site_url('musrenbangkab/show_list_musrembangkab');
        $data['url_terima_usulan_musrenbang'] = site_url('musrenbangkab/terima_usulan_musrenbang');
        $data['url_tolak_usulan_musrenbang'] = site_url('musrenbangkab/tolak_usulan_musrenbang');
        $data['url_valid_usulan_musrenbang'] = site_url('musrenbangkab/valid_usulan_musrenbang');
        $data['url_load_keterangan'] = site_url('musrenbangkab/load_keterangan');
        $data['url_show_gallery'] = site_url('musrenbangkab/show_gallery');
        $data['url_summary_biaya'] = site_url('musrenbangkab/get_summary_biaya');
        $this->template->load('template','musrenbangkab/musrenbangkab',$data);
    }

    function show_gallery()
    {
        $id = $this->input->post('id_musrenbang');
        $result = $this->db->query("SELECT file FROM t_musrenbang WHERE id_musrenbang=?", array($id));
        $id_photo = $result->row();

        $this->db->where_in("id", explode(',',$id_photo->file));
        $this->db->from("t_upload_file");
        $result = $this->db->get();
        $result = $result->result();
        
        $arr = array();
        $i=0;
        foreach($result as $results){
            $arr[$i]['href'] = base_url().$results->location;
            $arr[$i]['title'] = $results->name;
            $i++;
        }
        
        echo json_encode($arr);
    }

    function load_data()
    {
        $search = $this->input->post("search");
        $start = $this->input->post("start");
        $length = $this->input->post("length");
        $order = $this->input->post("order");

        $renstra = $this->m_musrenbang->get_data_table_cam($search, $start, $length, $order["0"]);
        $alldata = $this->m_musrenbang->count_data_table_cam($search, $start, $length, $order["0"]);

        $data = array();
        $no=0;
        foreach ($renstra as $row) {
            $no++;
            $data[] = array(
                'no'                    => $no,
                'kode_kegiatan'         => $row->kode_kegiatan,
                'nama_program_kegiatan' => $row->nama_program_kegiatan,
                'total_jumlah_dana'     => $row->total_jumlah_dana,
                'nama_skpd'             => $row->nama_skpd,
                'id_skpd' 				=> $row->id_skpd
                );
        }
        $json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
        echo json_encode($json);
    }

    function edit_data($id_musrenbang=NULL)
    {
        $this->auth->restrict();

        $data['url_save_data'] = site_url('musrenbangkab/save_data');

        $data['isEdit'] = FALSE;
        $data['combo_skpd']      = $this->m_musrenbang->create_lov_skpd('');
        $data['combo_keputusan'] = $this->m_lov->create_lov('table_keputusan','id_keputusan','nama','');
        if (!empty($id_musrenbang)) {
            $data_ = array('id_musrenbang'=>$id_musrenbang);
            $result = $this->m_musrenbang->get_data_with_rincian($id_musrenbang,'table_musrenbang');
            if (empty($result)) {
                $this->session->set_userdata('msg_typ','err');
                $this->session->set_userdata('msg', 'Data musrenbangkab tidak ditemukan.');
                redirect('musrenbangkab');
            }

            $data['id_musrenbang']	  = $result->id_musrenbang;
            $data['jenis_pekerjaan']  = $result->jenis_pekerjaan;
            $data['lokasi']			  = $result->lokasi;
            $data['volume']			  = $result->volume;
            $data['satuan']		      = $result->satuan;
            $data['jumlah_dana']	  = $result->jumlah_dana;
            $data['id_asal_usulan']   = $result->id_asal_usulan;
            $data['alasan_prioritas'] = $result->alasan_prioritas;
            $data['id_kecamatan']     = $result->id_kecamatan;
            $data['isEdit']			  = TRUE;
            $data['combo_skpd']       = $this->m_musrenbang->create_lov_skpd($result->id_skpd);
            $data['combo_keputusan']  = $this->m_lov->create_lov('table_keputusan','id_keputusan','nama',$result->id_keputusan);

        }
    	$this->template->load('template','musrenbangkab/musrenbangkab_view', $data);
    }

    function valid_usulan_musrenbang()
    {
        $id_musrenbang = $this->input->post('id_musrenbang');
        $data_post = array(
            'id_status_usulan' => '4'
        );

        $result = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');

        if($result===FALSE){
            $arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
        }else{
            $arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
        }
        echo json_encode($arr);
    }

    function terima_usulan_musrenbang()
    {
        $id_musrenbang = $this->input->post('id_musrenbang');
        $data_post = array(
            'id_keputusan' => '2',
			'id_status_usulan' => '3',
            'alasan_keputusan' => ''
        );
        $result = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');

        if($result===FALSE){
            $arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
        }else{
            $arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
        }
        echo json_encode($arr);
    }

    function load_keterangan()
    {
        $data['id_musrenbang'] = $this->input->post('id_musrenbang');
        $this->load->view('musrenbang/keterangan_penolakan_usulan',$data);
    }

    function add_keterangan()
    {
		$id_musrenbang = $this->input->post('id_musrenbang');
		$keterangan = $this->input->post('keterangan');

		$data_post = array(
			'id_keputusan' => '3',
			'alasan_keputusan' => $keterangan,
			'id_status_usulan' => '3'
		);
		$result = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');
		if($result===FALSE){
				$arr = array('errno' => 0,'msg' => 'Proses perubahan status gagal!');
		}else{
				$arr = array('errno' => 1,'msg' => 'Proses perubahan status berhasil!');
		}
		echo json_encode($arr);
	}

    function save_data()
    {
        $date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
        //action save cekbank di table t_cmusrenbangdes
        $id_musrenbang 	 = $this->input->post('id_musrenbang');
        $call_from			    = $this->input->post('call_from');
        $data_post = array(
            'tahun'            => $this->session->userdata('t_anggaran_aktif'),
            'jenis_pekerjaan'  => $this->input->post('jenis_pekerjaan'),
            'volume'	       => $this->input->post('volume'),
            'lokasi'		   => $this->input->post('lokasi'),
            'satuan'		   => $this->input->post('satuan'),
            'jumlah_dana'	   => $this->input->post('jumlah_dana'),
            'id_skpd'		   => $this->input->post('id_skpd'),
            'id_kecamatan'     => $this->input->post('id_kecamatan') == '' ? $this->session->userdata('id_kecamatan'): $this->input->post('id_kecamatan'),
            'id_keputusan'     => $this->input->post('id_keputusan'),
            'id_asal_usulan'   => $this->input->post('id_asal_usulan') == ''? '3': $this->input->post('id_asal_usulan'),
            'alasan_prioritas' => $this->input->post('alasan_prioritas'),
            'start_from'       => '0',
            'stat_musrenkab'   => '1'
        );
    
            if(strpos($call_from, 'musrenbangdes/edit_data') != FALSE) {
                $call_from = '';
            }
    
            $cekmusrenbang = $this->m_musrenbang->get_data(array('id_musrenbang'=>$id_musrenbang),'table_musrenbang');
            if($cekmusrenbang === empty($cekmusrenbang)) {
                $cekmusrenbang = new stdClass();
                $id_musrenbang = '';
            }
    
            $ret = TRUE;
            if(empty($id_musrenbang)) {
                //insert
          $data_post['created_by'] = $this->session->userdata('id_user');
          $data_post['created_date'] = $date." ".$time;
                $ret = $this->m_musrenbang->insert($data_post,'table_musrenbang');
                //echo $this->db->last_query();
            } else {
                //update
          $data_post['changed_by'] = $this->session->userdata('id_user');
          $data_post['changed_date'] = $date." ".$time;
                $ret = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');
                echo $this->db->last_query();
            }
            if ($ret === FALSE){
          $this->session->set_userdata('msg_typ','err');
          $this->session->set_userdata('msg', 'Data musrenbang Gagal disimpan');
            } else {
          $this->session->set_userdata('msg_typ','ok');
          $this->session->set_userdata('msg', 'Data musrenbang Berhasil disimpan');
            }
    
            if(!empty($call_from))
                redirect($call_from);
    
        redirect('musrenbangkab');
            //var_dump($cekbank);
            //print_r ($id_cek);
      }

    function formatRupiah($rupiah)
    {
        return "Rp".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
    }

    function show_list_musrembangkab()
    {
        $kode_kegiatan = $this->input->post('kode_kegiatan');
        $id_skpd = $this->input->post('id_skpd');


        $results = $this->m_musrenbang->get_list_musrenbangcam($kode_kegiatan,$id_skpd);
        $data = "";
        foreach ($results as $result) {
          //  $action = '<a href="javascript:void(0)" onclick="edit_musrenbangcam('. $result->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Usulan Musrenbang"/>';
            $action = '<a href="javascript:void(0)" onclick="terima_usulan_musrenbangkab('. $result->id_musrenbang .')" class="icon2-accept" title="Terima Usulan Musrenbangkab"/>';
            $action .= '<a href="javascript:void(0)" onclick="tolak_usulan_musrenbangkab('. $result->id_musrenbang .')" class="icon2-cancel" title="Tolak Usulan Musrenbangkab"/>';
            $action .= '<a href="javascript:void(0)" onclick="valid_usulan_musrenbangkab('. $result->id_musrenbang .')" class="icon2-report_go" title="Sahkan Usulan Musrenbangkab"/>';
            $action .= '<a href="javascript:void(0)" onclick="show_gallery('. $result->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>';
            $data .= '<tr>';
            $data .= '<td>';
            $data .= $result->jenis_pekerjaan;
            $data .= '</td><td>';
            $data .= $result->volume;
            $data .= ' ';
            $data .= $result->satuan;
            $data .= '</td><td>';
            $data .= $this->formatRupiah($result->jumlah_dana);
            $data .= '</td><td>';
            $data .= $result->lokasi;
            $data .= '</td><td>';
            $data .= $result->nama_desa;
            $data .= ' - ';
            $data .= $result->nama_kec;
            $data .= '</td><td>';
            $data .= $result->asal_usulan;
            $data .= '</td><td>';
            $data .= $result->status_usulan;
            $data .= '</td><td>';
            $data .= $result->status_keputusan;
            $data .= '</td><td>';
            $data .= $action;
            $data .= '</td>';
            $data .= '</tr>';
        }
        //echo $data;
        echo $data;
    }

    private function cetak_musrenbangkab_func($tahun,$id_skpd)
    {
        $data['musrenbangkab_type'] = "MUSRENBANGKAB";
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $header = $this->m_template_cetak->get_value("GAMBAR");
        $data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
        //$desa_detail = $this->m_desa->get_one_desa(array('id_desa' => $id_desa));
        $skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
        $data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd)."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>No Telp. ".$skpd_detail->telp_skpd."<p>";

        $id_keputusan1 = 1;
        $id_keputusan2 = 2;
        $id_keputusan3 = 3;
        $data1['musrenbangkab1'] = $this->m_musrenbang->get_musrenbangcam_cetak($id_skpd,$tahun,$id_keputusan1);
        $data1['musrenbangkab2'] = $this->m_musrenbang->get_musrenbangcam_cetak($id_skpd,$tahun,$id_keputusan2);
        $data1['musrenbangkab3'] = $this->m_musrenbang->get_musrenbangcam_cetak($id_skpd,$tahun,$id_keputusan3);
        $data['musrenbangkab'] = $this->load->view('musrenbangkab/cetak/isi_musrenbangkab', $data1, TRUE);
        return $data;
    }

    function do_cetak_musrenbangkab($id_skpd=NULL){
        ini_set('memory_limit', '-1');

        $this->auth->restrict();
        if (empty($id_skpd)) {
            $tahun = $this->session->userdata('t_anggaran_aktif');
            $id_skpd = $this->session->userdata('id_skpd');
        }

        $skpd = $this->m_musrenbang->get_one_musrenbangcam($id_skpd,TRUE);
        if (!empty($skpd)) {
            $data = $this->cetak_musrenbangkab_func($tahun,$id_skpd);
            $html = $this->template->load('template_cetak', 'musrenbangkab/cetak/cetak', $data, true);
            $filename='Musrenbangkab '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s") .'.pdf';
        }else{
            $html = "<center>Data Tidak Tersedia . . .</center>";
            $filename='Musrenbangkab '. date("d-m-Y_H-i-s") .'.pdf';
        }
        //echo $html;
        pdf_create($html, $filename, "A4", "Landscape", FALSE);
    }

    function preview_musrenbangkab()
    {
        $this->auth->restrict();
        $tahun = $this->session->userdata('t_anggaran_aktif');
        $id_skpd = $this->session->userdata('id_skpd');

        $skpd = $this->m_musrenbang->get_one_musrenbangcam($id_skpd,TRUE);
        if (!empty($skpd)) {
            $data = $this->cetak_musrenbangkab_func($tahun,$id_skpd);
            $this->template->load('template', 'musrenbangkab/cetak/preview_cetak', $data);
        }else{
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Musrenbangkab tidak tersedia.');
            redirect('home');
        }
    }

    function get_summary_biaya()
    {
        $arr = array(
            'total_biaya' => $this->m_musrenbang->get_summary_biaya_kecamatan()
        );

        echo json_encode($arr);
    }

}

/* End of file musrenbangkab.php */
