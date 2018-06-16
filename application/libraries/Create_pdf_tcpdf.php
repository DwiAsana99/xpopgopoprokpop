<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('asset/tcpdf/tcpdf.php');

// if (!defined('_MPDF_PATH')) define('_MPDF_PATH', APPPATH . 'libraries/mpdf/');

class Create_pdf_tcpdf {


	function Create_pdf_tcpdf(){
		$this->ci =& get_instance();
		//$this->ci->load->library('session');
	}

	function tes(){
		return true;
	}

	function load($html,$namafile,$ukuran,$orientasi){
		$mpdf =  new TCPDF($orientasi, PDF_UNIT, $ukuran, true, 'UTF-8', false);
	

		$mpdf->SetTitle($namafile);
		$mpdf->SetHTMLFooter('
					<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
					<td width="33%"> <img style="margin-top:-2px; margin-left: -2px;" height="18" width="18" src="' . base_url() . 'asset/images/S_4_sirenbangda_black.png"><font size="3">I</font>RENBANGDA </td>
					<td width="33%" align="right" style="font-weight: bold; ">{PAGENO}/{nbpg}</td>
					</tr></table>');

		$mpdf->WriteHTML($html);
		if ($metode=='Print') {
			$mpdf->Output($namafile.'.pdf','D');
		}else{
			$mpdf->Output(); 
		}

	}

	function load_ng($html,$namafile,$ukuran,$metode){

		// $mpdf =  new mPDF('utf-8', $ukuran);
		$mpdf =  new TCPDF('utf-8', $ukuran, '', '', '10', '10', '10', '18');
	

		$mpdf->SetTitle($namafile);
		$mpdf->SetHTMLFooter('
					<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
					<td width="33%"> <img style="margin-top:-2px; margin-left: -2px;" height="18" width="18" src="' . base_url() . 'asset/images/S_4_sirenbangda_black.png"><font size="3">I</font>RENBANGDA </td>
					<td width="33%" align="right" style="font-weight: bold; ">{PAGENO}/{nbpg}</td>
					</tr></table>');

		$mpdf->WriteHTML($html);
		if ($metode=='Print') {
			$mpdf->Output($namafile.'.pdf','D');
		}else{
			$mpdf->Output(); 
		}

	}

}