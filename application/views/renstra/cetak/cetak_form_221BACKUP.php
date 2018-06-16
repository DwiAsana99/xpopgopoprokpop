<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<html>
<head>
    <title>Cetak File Sirenbangda</title>
    <style type="text/css">
    #header1 {
            color: black;

            top: 0;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 0.1pt solid #aaa;
        }
    #header2 {
            color: black;
            top: 0;
            font-size: 12px;
            border-bottom: 0.1pt solid #aaa;

        }
     #header3 {
            color: black;
            top: 0;
            font-size: 11px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;

        }
        #footer1 {
            color: black;
            top: 0;
            font-size: 7px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;
        }
         #header4 {
            color: black;
            top: 0;
            font-size: 11px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;
        }
         #tabelhead2 {
            color: black;
            top: 0;
            font-size: 10px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;
        }
        #algtop{
          vertical-align: top;
        }


    table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}
    </style>

</head>
<body>


  <table width="100%" style=" font-family:'Droid Sans', Helvetica, Arial, sans-serif;" >
  <tbody>
    <tr>
      <td  width="8%" width="8%" rowspan="2" align="center"><?php echo $logo; ?></td>
      <td id="header1" align="center" colspan="8"><strong>RINCIAN KEGIATAN BELANJA RENJA <br>
       SATUAN KERJA PERANGKAT DAERAH</strong></td>
      <td align="center" rowspan="2"> <strong> Formulir <br>
        BELANJA  <br>
      SKPD </strong></td>
    </tr>
    <tr>
      <td  id="header2" align="center" height="30" colspan="8"><strong>PEMERINTAH KABUPATEN KLUNGKUNG</strong><br>
      Tahun Anggaran : <?php echo $tahun1[0]->tahun; ?></td>
    </tr>

    <?php
      $kode_skpd = $this->db->query('SELECT MID(kode_skpd, 1, 1) AS urusan ,MID(kode_skpd, 3, 2) AS bidang ,MID(kode_skpd, 6, 2) AS unit ,MID(kode_skpd, 9, 2) AS sub_unit FROM m_skpd WHERE id_skpd = "'.$this->session->userdata("id_skpd").'"')->row();

    $NamaBidang =   $tahun1[0]->nama_bidang;
    $UrusanPemerintah = $tahun1[0]->kode_urusan;
    $KodeBidang =  $UrusanPemerintah." . ". $tahun1[0]->kode_bidang;
    $Organisasi = $KodeBidang." . ".$UrusanPemerintah." . ".$kode_skpd->sub_unit;
    $Program = $Organisasi." . ".$tahun1[0]->kode_program;
    $Kegiatan =  $Program." . ". $tahun1[0]->kode_kegiatan;

    function terbilang($nilai){
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
          $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
          $temp = terbilang($nilai - 10). " belas";
        } else if ($nilai < 100) {
          $temp = terbilang($nilai/10)." puluh". terbilang($nilai % 10);
        } else if ($nilai < 200) {
          $temp = " seratus" . terbilang($nilai - 100);
        } else if ($nilai < 1000) {
          $temp = terbilang($nilai/100) . " ratus" . terbilang($nilai % 100);
        } else if ($nilai < 2000) {
          $temp = " seribu" . terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
          $temp = terbilang($nilai/1000) . " ribu" . terbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
          $temp = terbilang($nilai/1000000) . " juta" . terbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
          $temp = terbilang($nilai/1000000000) . " milyar" . terbilang(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
          $temp = terbilang($nilai/1000000000000) . " trilyun" . terbilang(fmod($nilai,1000000000000));
        }     
        return $temp;
    };
   

    $ta =  $tahun1[0]->tahun_anggaran;
    $ta_min = $ta-1;
     $ta_plus = $ta+1;

     if ($ta_min =='1'){
      $terbilang_n_min1 = $tahun1[0]->nominal_1;
          $Nominal_n_min =  Formatting::currency($tahun1[0]->nominal_1, 2); 
           $terbilang_n_min = terbilang($terbilang_n_min1);
            $terbilang_n_min_tulis = "( ". $terbilang_n_min." "."rupiah"." ".")";
          }elseif ($ta_min =='2') {
            $terbilang_n_min1 = $tahun1[0]->nominal_2;
             $Nominal_n_min =  Formatting::currency($tahun1[0]->nominal_2, 2); 
             $terbilang_n_min = terbilang($terbilang_n_min1); 
              $terbilang_n_min_tulis = "( ". $terbilang_n_min." "."rupiah"." ".")";
          }elseif ($ta_min =='3') {
             $terbilang_n_min1 = $tahun1[0]->nominal_3;
             $Nominal_n_min =  Formatting::currency($tahun1[0]->nominal_3, 2); 
             $terbilang_n_min = terbilang($terbilang_n_min1);
              $terbilang_n_min_tulis = "( ". $terbilang_n_min." "."rupiah"." ".")";
          }elseif ($ta_min =='4') {
             $terbilang_n_min1 = $tahun1[0]->nominal_4;
             $Nominal_n_min =  Formatting::currency($tahun1[0]->nominal_4, 2); 
             $terbilang_n_min = terbilang($terbilang_n_min1);
              $terbilang_n_min_tulis = "( ". $terbilang_n_min." "."rupiah"." ".")";
          }elseif ($ta_min =='5') {
             $terbilang_n_min1 = $tahun1[0]->nominal_5;
           $Nominal_n_min =  Formatting::currency($tahun1[0]->nominal_5, 2);  
           $terbilang_n_min = terbilang($terbilang_n_min1);
            $terbilang_n_min_tulis = "( ". $terbilang_n_min." "."rupiah"." ".")";
          }else{
          $terbilang_n_min1 = "-";
          $Nominal_n_min =  "-";
          $terbilang_n_min = "-";
          $terbilang_n_min_tulis = "-";
      }

      if ($ta_plus =='1'){
            $Nominal_n_plus =  Formatting::currency($tahun1[0]->nominal_1, 2);
             $terbilang_n_plus1 = $tahun1[0]->nominal_1;
           $terbilang_n_plus = terbilang( $terbilang_n_plus1); 
            $terbilang_n_plus_tulis = "( ". $terbilang_n_plus." "."rupiah"." ".")";
          }elseif ($ta_plus =='2') {
             $Nominal_n_plus =  Formatting::currency($tahun1[0]->nominal_2, 2);
              $terbilang_n_plus1 = $tahun1[0]->nominal_2;
           $terbilang_n_plus = terbilang( $terbilang_n_plus1); 
            $terbilang_n_plus_tulis = "( ". $terbilang_n_plus." "."rupiah"." ".")";
          }elseif ($ta_plus =='3') {
            $Nominal_n_plus =  Formatting::currency($tahun1[0]->nominal_3, 2);
             $terbilang_n_plus1 = $tahun1[0]->nominal_3;
           $terbilang_n_plus = terbilang( $terbilang_n_plus1); 
            $terbilang_n_plus_tulis = "( ". $terbilang_n_plus." "."rupiah"." ".")";
          }elseif ($ta_plus =='4') {
            $Nominal_n_plus =  Formatting::currency($tahun1[0]->nominal_4, 2);
             $terbilang_n_plus1 = $tahun1[0]->nominal_4;
           $terbilang_n_plus = terbilang( $terbilang_n_plus1); 
            $terbilang_n_plus_tulis = "( ". $terbilang_n_plus." "."rupiah"." ".")";
          }elseif ($ta_plus =='5') {
          $Nominal_n_plus =  Formatting::currency($tahun1[0]->nominal_5, 2);
             $terbilang_n_plus1 = $tahun1[0]->nominal_5;
           $terbilang_n_plus = terbilang( $terbilang_n_plus1); 
            $terbilang_n_plus_tulis = "( ". $terbilang_n_plus." "."rupiah"." ".")";  
          }else{
             $terbilang_n_plus1 = "-";
              $Nominal_n_plus = "-" ;
             $terbilang_n_plus = "-";
              $terbilang_n_plus_tulis ="-" ;
      }

        if ($ta =='1'){
            $Nominal_n =  Formatting::currency($tahun1[0]->nominal_1, 2);
             $terbilang_n1 = $tahun1[0]->nominal_1; 
            $terbilang_n = terbilang($tahun1[0]->nominal_1);
             $terbilang_n = terbilang($terbilang_n1); 
            $terbilang_n_tulis = "( ". $terbilang_n." "."rupiah"." ".")";
          }elseif ($ta =='2') {
            $Nominal_n =  Formatting::currency($tahun1[0]->nominal_2, 2);
             $terbilang_n1 = $tahun1[0]->nominal_2; 
            $terbilang_n = terbilang($tahun1[0]->nominal_2);
             $terbilang_n = terbilang($terbilang_n1); 
            $terbilang_n_tulis = "( ". $terbilang_n." "."rupiah"." ".")";
          }elseif ($ta =='3') {
             $Nominal_n =  Formatting::currency($tahun1[0]->nominal_3, 2);
            $terbilang_n1 = $tahun1[0]->nominal_3; 
            $terbilang_n = terbilang($tahun1[0]->nominal_3);
             $terbilang_n = terbilang($terbilang_n1); 
            $terbilang_n_tulis = "( ". $terbilang_n." "."rupiah"." ".")";
          }elseif ($ta =='4') {
             $Nominal_n =  Formatting::currency($tahun1[0]->nominal_4, 2);
             $terbilang_n1 = $tahun1[0]->nominal_4;
            $terbilang_n = terbilang($tahun1[0]->nominal_1);
             $terbilang_n = terbilang($terbilang_n1); 
            $terbilang_n_tulis = "( ". $terbilang_n." "."rupiah"." ".")"; 
          }elseif ($ta =='5') {
           $Nominal_n =  Formatting::currency($tahun1[0]->nominal_5, 2);
            $terbilang_n1 = $tahun1[0]->nominal_5; 
            $terbilang_n = terbilang($tahun1[0]->nominal_5);
             $terbilang_n = terbilang($terbilang_n1); 
            $terbilang_n_tulis = "( ". $terbilang_n." "."rupiah"." ".")";
        }else{
            $terbilang_n1 = "-";
            $Nominal_n =  "-";
            $terbilang_n = "-"; 
            $terbilang_n_tulis = "-";

        }

        if ($Nominal_n_min == 0 || $Nominal_n_min == '-') {
          $terbilang_n_min_tulis = "( nol rupiah ) ";
        }
        if ($Nominal_n == 0 || $Nominal_n == '-') {
          $terbilang_n_tulis = "( nol rupiah ) ";
        }
        if ($Nominal_n_plus == 0 || $Nominal_n_plus == '-') {
          $terbilang_n_plus_tulis = "( nol rupiah ) ";
        }
   
    ?>

    <tr>
      <td id="header3" colspan="3" width="7%" style="border-right:none"><strong>Urusan Pemerintahan  <br> Bidang  <br> Organisasi <br> Program <br> Kegiatan</strong></td>
      <td id="header3" width="20%" style="border-left:none ; border-right:none ; padding-left:10px">
        : <?php echo $UrusanPemerintah;?> <br> :  <?php echo $KodeBidang; ?> <br> : <?php echo $Organisasi ?>  <br>  : <?= $Program ?> <br>  : <?= $Kegiatan ?>
      </td>
      <td id="header3" width="60%" style="border-left:none ; border-right:none"  > <?php echo $tahun1[0]->nama_urusan;?>  <br> <?php echo $NamaBidang ;?>  <br> <?= $this->session->userdata('nama_skpd') ?>  <br> <?= $tahun1[0]->nama_program ;?> <br> <?= $tahun1[0]->nama_kegiatan ;?></td>
      <td id="header3" colspan="5" style="border-left:none">&nbsp;</td>
    </tr>
    <tr>
      <td id="header3" colspan="3" style="border-right:none"><strong>Lokasi Kegiatan</strong></td>
      <td id="header3" colspan="7" style="border-left:none"> <strong>:</strong> <?php echo $data_keg->lokasi; ?></td>
    </tr>
    <tr>
      <td id="header3" colspan="3" style="border-right:none"> <strong>Jumlah Tahun n-1 <br> Jumlah Tahun n <br> Jumlah Tahun n+1</strong></td>
      <td id="header3" style="border-left:none ; border-right:none"><strong>: </strong>Rp<strong><br>
        : </strong>Rp<strong><br>
      :</strong> Rp</td>
      <td id="header3" style="border-left:none ;  " colspan="6"> 
        <?php echo $Nominal_n_min; ?> &nbsp; <em><?php echo $terbilang_n_min_tulis; ?></em> <br>  
        <?php echo $Nominal_n; ?>  &nbsp; <em><?php echo  $terbilang_n_tulis ?> </em>  <br> 
        <?php echo $Nominal_n_plus; ?> &nbsp; <em><?php echo  $terbilang_n_plus_tulis; ?> </em>  
      </td>
        <!-- <td  style="border-left:none" colspan="4">&nbsp;</td> -->
    </tr>
    <tr>
      <td colspan="10" align="center"><strong>INDIKATOR &amp; TOLAK UKUR KINERJA BELANJA LANGSUNG</strong></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><strong>INDIKATOR</strong></td>
      <td colspan="3" align="center"><strong>TOLAK UKUR KINERJA</strong></td>
      <td colspan="4" align="center"><strong>TARGET KINERJA</strong></td>

    </tr>

    <?php $tot_row_capaian = count($capaian);?>
    <?php foreach ($capaian as $key_cap => $cap): ?>
      <tr>
        <?php if ($key_cap == 0): ?>
          <td id="header4" colspan="3" rowspan="<?php echo $tot_row_capaian; ?>"><strong>CAPAIAN PROGRAM</strong></td>
        <?php endif ?>
          <td id="header4" colspan="3" style="border-bottom:0px;border-top:0px;" ><?php echo $cap->indikator; ?></td>
          <td id="header4" align="right" colspan="4" style="padding-right:10px; border:0px;">
            <?php
              if ($ta =='1'){
                echo $cap->target_1." ".$cap->satuan_target ;
              }elseif ($ta =='2') {
                echo $cap->target_2." ".$cap->satuan_target ;
              }elseif ($ta =='3') {
                 echo $cap->target_3." ".$cap->satuan_target ;
              }elseif ($cap =='4') {
                echo $cap->target_4." ".$cap->satuan_target ;
              }elseif ($ta =='5') {
               echo $cap->target_5." ".$cap->satuan_target ;
              }
            ?>
          </td>
      </tr>
    <?php endforeach ?>

    <tr>
      <td id="header4" colspan="3"><strong>MASUKAN</strong></td>
      <td id="header4" colspan="3" style="border-bottom: 1px solid black">Uang</td>
      <td id="header4" align="right" colspan="4" style="padding-right:10px; border-bottom: 1px solid black" >
      <?php  foreach ($tahun1 as $tah ) {
          
          if ($ta =='1'){
            // echo "Rp."." ". Formatting::currency($tah->nominal_1)  ;
          }elseif ($ta =='2') {
            // echo "Rp."." ".Formatting::currency($tah->nominal_2) ;
          }elseif ($ta =='3') {
             // echo "Rp."." ".Formatting::currency($tah->nominal_3) ;
          }elseif ($ta =='4') {
            // echo "Rp."." ".Formatting::currency($tah->nominal_4) ;
          }elseif ($ta =='5') {
           // echo "Rp."." ".Formatting::currency($tah->nominal_5) ;
          }
            
            ?>
            <?php
          }

          echo "Rp ".$Nominal_n;
            ?>
        
      </td>

    </tr>

    <?php $tot_row_keluaran = count($keluaran); ?>
    <?php foreach ($keluaran as $key_kel => $kel): ?>
      <tr>
        <?php if ($key_kel == 0): ?>
          <td id="header4" colspan="3" rowspan="<?php echo $tot_row_keluaran; ?>"><strong>KELUARAN</strong></td>
        <?php endif ?> 
          <td id="header4" colspan="3" style="border-bottom:0px;border-top:0px;" ><?php echo $kel->indikator; ?></td>
          <td id="header4" align="right" colspan="4" style="padding-right:10px; border:0px;">
            <?php 
              if ($ta =='1'){
                echo $kel->target_1." ".$kel->satuan_target ;
              }elseif ($ta =='2') {
                echo $kel->target_2." ".$kel->satuan_target ;
              }elseif ($ta =='3') {
                 echo $kel->target_3." ".$kel->satuan_target ;
              }elseif ($ta =='4') {
                echo $kel->target_4." ".$kel->satuan_target ;
              }elseif ($ta =='5') {
               echo $kel->target_5." ".$kel->satuan_target ;
              }
            ?>
          </td> 
      </tr>
    <?php endforeach ?>
    
    <tr>
      <td id="header4" colspan="10"><strong>Kelompok Sasaran Kegiatan : </strong></td>
    </tr>
    <tr>
      <td colspan="10" align="center">
        <strong>RINCIAN ANGGARAN BELANJA LANGSUNG MENURUT PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH</strong>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="2" align="center"><strong>KODE <br> REKENING</strong></td>
      <td colspan="3" rowspan="2"  align="center"><strong>URAIAN</strong></td>
      <td colspan="3" align="center"> <strong>RINCIAN PERHITUNGAN</strong></td>
      <td  rowspan="2" align="center"> <strong>JUMLAH <br> (Rp)</strong></td>

    </tr>
    <tr>
      <td id="tabelhead2" width="7%" height="30" align="center"><strong>Volume</strong></td>
      <td id="tabelhead2" width="10%" align="center"><strong>Satuan</strong></td>
      <td id="tabelhead2" width="8%" width="50" align="center"><strong>Harga Satuan</strong></td>

    </tr>

    <tr>
      <td colspan="3" align="center"><strong>1</strong></td>
      <td colspan="3" align="center"><strong>2</strong></td>
      <td align="center"><strong>3</strong></td>
      <td align="center"><strong>4</strong></td>
      <td align="center"><strong>5</strong></td>
      <td align="center"><strong>6</strong></td>
    </tr>

                <?php

                $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
                $TotalBelanja = 0;$TotalJenisBelanja=0;$TotalKategoriBelanja=0;$TotalSubKategoriBelanja=0;$TotalBelanja1=0;

               
                foreach ($tahun1 as $rowth1  ) {
                    if ($rowth1->kode_jenis_belanja == $jenis) {
                        if ($rowth1->kode_kategori_belanja == $kategori) {
                            if ($rowth1->kode_sub_kategori_belanja == $subkategori) {
                                if ($rowth1->kode_belanja == $kdbelanja) {
                                    if ($rowth1->uraian_upper == $uraianbelanja) {
                                      echo "<tr id='algtop'>
                                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'>- $rowth1->detil_uraian_belanja </td>
                                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->volume, 2)."</td>
                                          <td align='right' style='padding-right:10px;'> $rowth1->satuan </td>
                                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->nominal_satuan, 2)."</td>
                                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->subtotal, 2)."</td>
                                        </tr>";
                                    }else{
                                      $uraianbelanja = $rowth1->uraian_upper;
                                      $uraianbelanja2 = '"'.$rowth1->uraian_belanja.'"';
                                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                                      echo "<tr id='algtop'>
                                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'> $rowth1->uraian_belanja </td>
                                          <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                          <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                          <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                          <td align='right' style='padding-right:10px; border-bottom:none; border-bottom-style:dashed;'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                                        </tr>";
                                      echo "<tr id='algtop'>
                                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'>- $rowth1->detil_uraian_belanja </td>
                                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->volume, 2)."</td>
                                          <td align='right' style='padding-right:10px;'> $rowth1->satuan </td>
                                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->nominal_satuan, 2)."</td>
                                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->subtotal, 2)."</td>
                                        </tr>";
                                    }
                                }else{
                                  $kdbelanja = $rowth1->kode_belanja;
                                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
                                  echo "<tr id='algtop'>
                                      <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                                      <td colspan='3' style='border-bottom:none; border-top:none; padding-left:25px'> $rowth1->belanja </td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                                    </tr>";
                                  $uraianbelanja = $rowth1->uraian_upper;
                                  $uraianbelanja2 = '"'.$rowth1->uraian_belanja.'"';
                                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                                  echo "<tr id='algtop'>
                                      <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                                      <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'> $rowth1->uraian_belanja </td>
                                      <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                      <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                      <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                      <td align='right' style='padding-right:10px; border-bottom:none; border-bottom-style:dashed;'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                                    </tr>";
                                  echo "<tr id='algtop'>
                                      <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                                      <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'>- $rowth1->detil_uraian_belanja </td>
                                      <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->volume, 2)."</td>
                                      <td align='right' style='padding-right:10px;'> $rowth1->satuan </td>
                                      <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->nominal_satuan, 2)."</td>
                                      <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->subtotal, 2)."</td>
                                      </tr>";
                                }
                            }else{     
                              $subkategori = $rowth1->kode_sub_kategori_belanja;
                              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
                              echo "<tr id='algtop'>
                                  <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori . $subkategori</td>
                                  <td colspan='3' style='border-bottom:none; border-top:none; padding-left:20px'> $rowth1->subkategori </td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                                </tr>";
                              $kdbelanja = $rowth1->kode_belanja;
                              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
                              echo "<tr id='algtop'>
                                  <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                                  <td colspan='3' style='border-bottom:none; border-top:none; padding-left:25px'> $rowth1->belanja </td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                                </tr>";
                              $uraianbelanja = $rowth1->uraian_upper;
                              $uraianbelanja2 = '"'.$rowth1->uraian_belanja.'"';
                              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                              echo "<tr id='algtop'>
                                  <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                                  <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'> $rowth1->uraian_belanja </td>
                                  <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                  <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                  <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                                  <td align='right' style='padding-right:10px; border-bottom:none; border-bottom-style:dashed;'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                                </tr>";
                              echo "<tr id='algtop'>
                                  <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                                  <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'>- $rowth1->detil_uraian_belanja </td>
                                  <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->volume, 2)."</td>
                                  <td align='right' style='padding-right:10px;'> $rowth1->satuan </td>
                                  <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->nominal_satuan, 2)."</td>
                                  <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->subtotal, 2)."</td>
                                  </tr>";
                            }
                        }else{
                          $kategori = $rowth1->kode_kategori_belanja;
                          $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
                          echo "<tr id='algtop'>
                              <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori</td>
                              <td colspan='3' style='border-bottom:none; border-top:none; padding-left:15px'><b> $rowth1->kategori </b></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                            </tr>";
                          $subkategori = $rowth1->kode_sub_kategori_belanja;
                          $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
                          echo "<tr id='algtop'>
                              <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori . $subkategori</td>
                              <td colspan='3' style='border-bottom:none; border-top:none; padding-left:20px'> $rowth1->subkategori </td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                            </tr>";
                          $kdbelanja = $rowth1->kode_belanja;
                          $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
                          echo "<tr id='algtop'>
                              <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                              <td colspan='3' style='border-bottom:none; border-top:none; padding-left:25px'> $rowth1->belanja </td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                            </tr>";
                          $uraianbelanja = $rowth1->uraian_upper;
                          $uraianbelanja2 = '"'.$rowth1->uraian_belanja.'"';
                          $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                          echo "<tr id='algtop'>
                              <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                              <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'> $rowth1->uraian_belanja </td>
                              <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                              <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                              <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                              <td align='right' style='padding-right:10px; border-bottom:none; border-bottom-style:dashed;'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                            </tr>";
                          echo "<tr id='algtop'>
                              <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                              <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'>- $rowth1->detil_uraian_belanja </td>
                              <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->volume, 2)."</td>
                              <td align='right' style='padding-right:10px;'> $rowth1->satuan </td>
                              <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->nominal_satuan, 2)."</td>
                              <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->subtotal, 2)."</td>
                              </tr>";
                        }
                    }else{
                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng'")->row();
                      echo "<tr id='algtop'>
                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5</td>
                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:5px'><b> BELANJA </b></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                        </tr>";
                      $jenis = $rowth1->kode_jenis_belanja; 
                      $jenisText = substr_replace($jenis,"", 0, -1);
                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis'")->row();
                      echo "<tr id='algtop'>
                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText</td>
                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:10px'><b> $rowth1->jenis </b></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                        </tr>";
                      $kategori = $rowth1->kode_kategori_belanja;
                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
                      echo "<tr id='algtop'>
                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori</td>
                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:15px'><b> $rowth1->kategori </b></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                        </tr>";
                      $subkategori = $rowth1->kode_sub_kategori_belanja;
                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
                      echo "<tr id='algtop'>
                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori . $subkategori</td>
                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:20px'> $rowth1->subkategori </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                        </tr>";
                      $kdbelanja = $rowth1->kode_belanja;
                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
                      echo "<tr id='algtop'>
                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:25px'> $rowth1->belanja </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align='right' style='padding-right:10px'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                        </tr>";
                      $uraianbelanja = $rowth1->uraian_upper;
                      $uraianbelanja2 = '"'.$rowth1->uraian_belanja.'"';
                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                      echo "<tr id='algtop'>
                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'> $rowth1->uraian_belanja </td>
                          <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                          <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                          <td style='border-bottom:none; border-bottom-style:dashed;'></td>
                          <td align='right' style='padding-right:10px; border-bottom:none; border-bottom-style:dashed;'>". Formatting::currency($sum_tot->sumtot, 2)."</td>
                        </tr>";
                      echo "<tr id='algtop'>
                          <td colspan='3' align='left' width='90' style='border-bottom:none; border-top:none; padding-left:10px'></td>
                          <td colspan='3' style='border-bottom:none; border-top:none; padding-left:30px'>- $rowth1->detil_uraian_belanja </td>
                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->volume, 2)."</td>
                          <td align='right' style='padding-right:10px;'> $rowth1->satuan </td>
                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->nominal_satuan, 2)."</td>
                          <td align='right' style='padding-right:10px;'>".Formatting::currency($rowth1->subtotal, 2)."</td>
                          </tr>";
                    }
                }
                ?>






     <tr>
      <td id="footer1" colspan="10" ><strong>Formulir Belanja SKPD - <?= $this->session->userdata('nama_skpd') ?></strong></td>
    </tr>
  </tbody>
</table>
<br style="padding-top: 30px">
<?php 
  $kepala_skpd = $this->db->query('SELECT * FROM m_skpd WHERE id_skpd ="'.$this->session->userdata("id_skpd").'"')->row();
?>
<table width="100%" style="border: 0px">
  <tr  style="border: 0px">
    <td width="60%" style="border: 0px"></td>
    <td align="center" style="border: 0px">Semarapura, <?php echo Formatting::dateINA(date('Y-m-d')); ?></td>
  </tr>
  <tr>
    <td style="border: 0px"></td>
    <td align="center" style="border: 0px"><?php echo $kepala_skpd->nama_jabatan; ?></td>
  </tr>
  <tr>
    <td style="padding-top: 60px; border: 0px;"></td>
    <td style="border: 0px"></td>
  </tr>
  <tr>
    <td style="border: 0px"></td>
    <td align="center" style="border: 0px"><u><?php echo $kepala_skpd->kaskpd_nama; ?></u></td>
  </tr>
  <tr>
    <td style="border: 0px"></td>
    <td align="center" style="border: 0px"><?php echo $kepala_skpd->pangkat_golongan; ?></td>
  </tr>
  <tr>
    <td style="border: 0px"></td>
    <td align="center" style="border: 0px">NIP : <?php echo $kepala_skpd->kaskpd_nip; ?></td>
  </tr>
</table>
</body>
</html>
