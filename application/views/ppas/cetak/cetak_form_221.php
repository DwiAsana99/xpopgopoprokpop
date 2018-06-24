
    <style type="text/css">
      table {
        color: black;
        top: 0;
        border-collapse: collapse;
        font-family: sans-serif;
      }
      table.noborder td{
        font-size: 11px;
        padding-left: 10px;
        vertical-align: top;
      }
      td.header1 {
        font-size: 14px !important;
        font-weight: bold !important;
      }
      td.header2 {
        font-size: 12px !important;
      }
      td.header3{
        font-size: 11px;
        padding-left:10px;
      }
      td.header4{
        font-size: 12px !important;
        vertical-align: middle !important;
        padding-left: 0px !important;
      }
      td.top {
        border-top: 1px solid black;
      }
      td.left {
        border-left: 1px solid black;
      }
      td.mid {
        border: 1px solid black;
      }
      td.right {
        border-right: 1px solid black;
      }
      td.bottom {
        border-bottom: 1px solid black;
      }
      td.bolder{
        font-weight: bold;
      }

    </style>

  <?php

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
    }
    
    $kode_skpd = $this->db->query('SELECT m_skpd.*, MID(kode_skpd, 1, 1) AS urusan ,MID(kode_skpd, 3, 2) AS bidang ,MID(kode_skpd, 6, 2) AS unit ,MID(kode_skpd, 9, 2) AS sub_unit FROM m_skpd WHERE id_skpd = "'.$this->session->userdata("id_skpd").'"')->row();

    if ($is_tahun_sekarang == 1) {
      $target_n = 'target';
      $t_ke_n_min = 'dummy';
      $t_ke_n = 'nominal';
      $t_ke_n_plus = 'nominal_thndpn';
    }else{ 
      $target_n = 'target_thndpn';
      $t_ke_n_min = 'nominal';
      $t_ke_n = 'nominal_thndpn';
      $t_ke_n_plus = 'dummy';
    }

    // $ke_n = $th_anggaran->id;
    // $ke_n_min = $ke_n - 1;
    // $ke_n_plus = $ke_n + 1;

    // $target_n = 'target_'.$ke_n;

    // $t_ke_n_min = 'nominal_'.$ke_n_min;
    // $t_ke_n = 'nominal_'.$ke_n;
    // $t_ke_n_plus = 'nominal_'.$ke_n_plus;

    $nominal_n_min = $kegiatan->$t_ke_n_min;
    $nominal_n = $kegiatan->$t_ke_n;
    $nominal_n_plus = $kegiatan->$t_ke_n_plus;

    $terbilang_n_min = ($nominal_n_min <= 0) ? '<i>( nol rupiah )</i>' : '<i>( '.terbilang($nominal_n_min).' rupiah )</i>';
    $terbilang_n = ($nominal_n <= 0) ? '<i>( nol rupiah )</i>' : '<i>( '.terbilang($nominal_n).' rupiah )</i>';
    $terbilang_n_plus = ($nominal_n_plus <= 0) ? '<i>( nol rupiah )</i>' : '<i>( '.terbilang($nominal_n_plus).' rupiah )</i>';

// print_r($th_anggaran);

  ?>

    <!-- potongan table -->
    <table width="100%">
      <tr>
        <td rowspan="2" align="center" class="mid" width="10%">
          <?php echo $logo; ?>
        </td>
        <td align="center" class="header1 mid bolder">
          RINCIAN KEGIATAN BELANJA PPAS<br>
          SATUAN KERJA PERANGKAT DAERAH
        </td>
        <td rowspan="2" align="center" class="mid bolder" width="10%">
          FORMULIR <br>
          BELANJA <br>
          SKPD
        </td>
      </tr>
      <tr>
        <td align="center" class="mid" height="30">
          <strong>PEMERINTAH KABUPATEN KLUNGKUNG</strong><br>
          Tahun Anggaran : <?php echo $th_anggaran->tahun_anggaran; ?>
        </td>
      </tr>
    </table>
    <!-- potongan table -->
    <table width="100%" border="0" class="noborder" style="margin: 0px;">
      <tr>
        <td class="top left bolder" width="115px">Urusan Pemerintahan</td>
        <td class="top" width="125px">: <?php echo $kegiatan->kode_urusan; ?></td>
        <td class="top right"><?php echo $kegiatan->nama_urusan; ?></td>
      </tr>
      <tr>
        <td class="left bolder">Bidang</td>
        <td>: <?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang; ?></td>
        <td class="right"><?php echo $kegiatan->nama_bidang; ?></td>
      </tr>
      <tr>
        <td class="left bolder">Organisasi</td>
        <td>: <?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang.' . '.$kode_skpd->unit.' . '.$kode_skpd->sub_unit; ?></td>
        <td class="right"><?php echo $this->session->userdata('nama_skpd'); ?></td>
      </tr>
      <tr>
        <td class="left bolder">Program</td>
        <td>: <?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang.' . '.$kode_skpd->unit.' . '.$kode_skpd->sub_unit.' . '.$kegiatan->kode_program; ?></td>
        <td class="right"><?php echo $kegiatan->nama_program; ?></td>
      </tr>
      <tr>
        <td class="bottom left bolder">Kegiatan</td>
        <td class="bottom">: <?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang.' . '.$kode_skpd->unit.' . '.$kode_skpd->sub_unit.' . '.$kegiatan->kode_program.' . '.$kegiatan->kode_kegiatan; ?></td>
        <td class="bottom right"><?php echo $kegiatan->nama_kegiatan; ?></td>
      </tr>
      <tr>
        <td class="top bottom left bolder">Lokasi</td>
        <td colspan="2" class="top bottom right">: <?php echo $kegiatan->lokasi; ?></td>
      </tr>
      <tr>
        <td class="top left bolder">Jumlah Tahun n-1</td>
        <td class="top">: Rp.</td>
        <td class="right"><?php echo formatting::currency($nominal_n_min, 2).' '.$terbilang_n_min; ?></td>
      </tr>
      <tr>
        <td class="left bolder">Jumlah Tahun n</td>
        <td>: Rp.</td>
        <td class="right"><?php echo formatting::currency($nominal_n, 2).' '.$terbilang_n; ?></td>
      </tr>
      <tr>
        <td class="bottom left bolder">Jumlah Tahun n+1</td>
        <td class="bottom">: Rp.</td>
        <td class="bottom right"><?php echo formatting::currency($nominal_n_plus, 2).' '.$terbilang_n_plus; ?></td>
      </tr>
    </table>
    <!-- potongan table -->
    <table width="100%" border="0" class="noborder" style="margin: 0px;">
      <tr>
        <td class="mid bolder header2" colspan="3" align="center">INDIKATOR & TOLAK UKUR KINERJA BELANJA LANGSUNG</td>
      </tr>
      <tr>
        <td class="mid bolder header2" align="center">INDIKATOR</td>
        <td class="mid bolder header2" align="center">TOLAK UKUR KINERJA</td>
        <td class="mid bolder header2" align="center">TARGET KINERJA</td>
      </tr>
      <?php $tot_capaian = count($capaian); ?>
      <?php foreach ($capaian as $key => $val_capaian): ?>
        <tr>
          <?php if ($key == 0): ?>
            <td class="mid bolder header2" rowspan="<?php echo $tot_capaian; ?>">CAPAIAN PROGRAM</td>
          <?php endif ?>
            <td><?php echo $val_capaian->indikator; ?></td>
            <td class="left right" align="right" style="padding-right:10px;">
              <?php echo formatting::currency($val_capaian->$target_n, 2).' '.$val_capaian->satuan_target; ?>
            </td>
        </tr>
      <?php endforeach ?>
      <tr>
        <td class="mid bolder header2">MASUKAN</td>
        <td class="mid">Uang</td>
        <td class="mid" align="right" style="padding-right:10px;">Rp. <?php echo formatting::currency($nominal_n, 2); ?></td>
      </tr>
      <?php $tot_keluaran = count($keluaran); ?>
      <?php foreach ($keluaran as $key => $val_keluaran): ?>
        <tr>
          <?php if ($key == 0): ?>
            <td class="mid bolder header2" rowspan="<?php echo $tot_keluaran; ?>">KELUARAN</td>
          <?php endif ?>
            <td><?php echo $val_keluaran->indikator; ?></td>
            <td class="left right" align="right" style="padding-right:10px;">
              <?php echo formatting::currency($val_keluaran->$target_n, 2).' '.$val_keluaran->satuan_target; ?>
            </td>
        </tr>
      <?php endforeach ?>
      <tr>
        <td colspan="3" class="mid bolder header2">Kelompok Sasaran Kegiatan :</td>
      </tr>
      <tr>
        <td colspan="3" class="mid bolder header2" align="center">
          RINCIAN ANGGARAN BELANJA LANGSUNG MENURUT PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH
        </td>
      </tr>
    </table>
    <!-- potongan table -->
    <table width="100%" border="0" class="noborder" style="margin: 0px;">
      <thead>
        <tr>
          <td class="mid bolder header4" align="center" rowspan="2" width="10%">KODE <br>REKENING</td>
          <td class="mid bolder header4" align="center" rowspan="2" width="50%">URAIAN</td>
          <td class="mid bolder header4" align="center" colspan="3">RINCIAN PERHITUNGAN</td>
          <td class="mid bolder header4" align="center" rowspan="2">JUMLAH (Rp.)</td>
        </tr>
        <tr>
          <td class="mid bolder header4" align="center" height="30">Volume</td>
          <td class="mid bolder header4" align="center">Satuan</td>
          <td class="mid bolder header4" align="center">Harga Satuan</td>
        </tr>
        <tr>
          <td class="mid bolder header4" align="center">1</td>
          <td class="mid bolder header4" align="center">2</td>
          <td class="mid bolder header4" align="center">3</td>
          <td class="mid bolder header4" align="center">4</td>
          <td class="mid bolder header4" align="center">5</td>
          <td class="mid bolder header4" align="center">6</td>
        </tr>
      </thead>
      <tbody>
        <?php 
          $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
          $idk_ng = $id_keg;
          $ta_ng = $th_anggaran->tahun_anggaran;
        ?>
        <?php foreach ($belanja as $key_rowth => $rowth): ?>
          <?php if ($rowth->kode_jenis_belanja == $jenis): ?>
            <?php if ($rowth->kode_kategori_belanja == $kategori): ?>
              <?php if ($rowth->kode_sub_kategori_belanja == $subkategori): ?>
                <?php if ($rowth->kode_belanja == $kdbelanja): ?>
                  <?php if ($rowth->uraian_upper == $uraianbelanja): ?>
                    <tr>
                      <td class="left right"></td>
                      <td class="left right" style="padding-left: 30px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
                      <td class="mid" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->volume, 2); ?></td>
                      <td class="mid" align='center' style='padding-left:0px;'><?php echo $rowth->satuan; ?></td>
                      <td class="mid" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->nominal_satuan, 2); ?></td>
                      <td class="mid" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
                    </tr>
                  <?php else: ?>
                    <?php  
                      $uraianbelanja = $rowth->uraian_upper;
                      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
                      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                    ?>
                    <tr>
                      <td class="left right"></td>
                      <td class="left right" style="padding-left: 30px;"><?php echo $rowth->uraian_belanja; ?></td>
                      <td class="mid" style="border-bottom-style:dashed;"></td>
                      <td class="mid" style="border-bottom-style:dashed;"></td>
                      <td class="mid" style="border-bottom-style:dashed;"></td>
                      <td class="mid" align='right' style="border-bottom-style:dashed; padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
                    </tr>
                    <tr>
                      <td class="left right"></td>
                      <td class="left right" style="padding-left: 30px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
                      <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->volume, 2); ?></td>
                      <td class="left bottom right" align='center' style='padding-left:0px;'><?php echo $rowth->satuan; ?></td>
                      <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->nominal_satuan, 2); ?></td>
                      <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
                    </tr>
                  <?php endif ?>
                <?php else: ?>
                  <?php  
                    $kdbelanja = $rowth->kode_belanja;
                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
                  ?>
                  <tr>
                    <td class="left right">5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
                    <td class="left right bolder" style="padding-left: 25px;"><?php echo $rowth->belanja; ?></td>
                    <td class="mid"></td>
                    <td class="mid"></td>
                    <td class="mid"></td>
                    <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
                  </tr>
                  <?php  
                    $uraianbelanja = $rowth->uraian_upper;
                    $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
                    $uraianbelanja2 = '"'.$uraianbelanja2.'"';
                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                  ?>
                  <tr>
                    <td class="left right"></td>
                    <td class="left right" style="padding-left: 30px;"><?php echo $rowth->uraian_belanja; ?></td>
                    <td class="mid" style="border-bottom-style:dashed;"></td>
                    <td class="mid" style="border-bottom-style:dashed;"></td>
                    <td class="mid" style="border-bottom-style:dashed;"></td>
                    <td class="mid" align='right' style="border-bottom-style:dashed; padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
                  </tr>
                  <tr>
                    <td class="left right"></td>
                    <td class="left right" style="padding-left: 30px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
                    <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->volume, 2); ?></td>
                    <td class="left bottom right" align='center' style='padding-left:0px;'><?php echo $rowth->satuan; ?></td>
                    <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->nominal_satuan, 2); ?></td>
                    <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
                  </tr>
                <?php endif ?>
              <?php else: ?>
                <?php  
                  $subkategori = $rowth->kode_sub_kategori_belanja;
                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
                ?>
                <tr>
                  <td class="left right">5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
                  <td class="left right bolder" style="padding-left: 20px;"><?php echo $rowth->subkategori; ?></td>
                  <td class="mid"></td>
                  <td class="mid"></td>
                  <td class="mid"></td>
                  <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
                </tr>
                <?php  
                  $kdbelanja = $rowth->kode_belanja;
                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
                ?>
                <tr>
                  <td class="left right">5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
                  <td class="left right bolder" style="padding-left: 25px;"><?php echo $rowth->belanja; ?></td>
                  <td class="mid"></td>
                  <td class="mid"></td>
                  <td class="mid"></td>
                  <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
                </tr>
                <?php  
                  $uraianbelanja = $rowth->uraian_upper;
                  $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
                  $uraianbelanja2 = '"'.$uraianbelanja2.'"';
                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
                ?>
                <tr>
                  <td class="left right"></td>
                  <td class="left right" style="padding-left: 30px;"><?php echo $rowth->uraian_belanja; ?></td>
                  <td class="mid" style="border-bottom-style:dashed;"></td>
                  <td class="mid" style="border-bottom-style:dashed;"></td>
                  <td class="mid" style="border-bottom-style:dashed;"></td>
                  <td class="mid" align='right' style="border-bottom-style:dashed; padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
                </tr>
                <tr>
                  <td class="left right"></td>
                  <td class="left right" style="padding-left: 30px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
                  <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->volume, 2); ?></td>
                  <td class="left bottom right" align='center' style='padding-left:0px;'><?php echo $rowth->satuan; ?></td>
                  <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->nominal_satuan, 2); ?></td>
                  <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
                </tr>
              <?php endif ?>
            <?php else: ?>
              <?php  
                $kategori = $rowth->kode_kategori_belanja;
                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
              ?>
              <tr>
                <td class="left right">5 . <?php echo $jenisText.' . '.$kategori; ?></td>
                <td class="left right bolder" style="padding-left: 15px;"><?php echo $rowth->kategori; ?></td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
              </tr>
              <?php  
                $subkategori = $rowth->kode_sub_kategori_belanja;
                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
              ?>
              <tr>
                <td class="left right">5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
                <td class="left right bolder" style="padding-left: 20px;"><?php echo $rowth->subkategori; ?></td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
              </tr>
              <?php  
                $kdbelanja = $rowth->kode_belanja;
                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
              ?>
              <tr>
                <td class="left right">5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
                <td class="left right bolder" style="padding-left: 25px;"><?php echo $rowth->belanja; ?></td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
              </tr>
              <?php  
                $uraianbelanja = $rowth->uraian_upper;
                $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
                $uraianbelanja2 = '"'.$uraianbelanja2.'"';
                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
              ?>
              <tr>
                <td class="left right"></td>
                <td class="left right" style="padding-left: 30px;"><?php echo $rowth->uraian_belanja; ?></td>
                <td class="mid" style="border-bottom-style:dashed;"></td>
                <td class="mid" style="border-bottom-style:dashed;"></td>
                <td class="mid" style="border-bottom-style:dashed;"></td>
                <td class="mid" align='right' style="border-bottom-style:dashed; padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
              </tr>
              <tr>
                <td class="left right"></td>
                <td class="left right" style="padding-left: 30px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
                <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->volume, 2); ?></td>
                <td class="left bottom right" align='center' style='padding-left:0px;'><?php echo $rowth->satuan; ?></td>
                <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->nominal_satuan, 2); ?></td>
                <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
              </tr>
            <?php endif ?>
          <?php else: ?>
            <?php if ($key_rowth == 0): ?>
              <?php  
              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng'")->row();
              ?>
              <tr>
                <td class="left right">5</td>
                <td class="left right bolder" style="padding-left: 5px;">BELANJA</td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid"></td>
                <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
              </tr>
            <?php endif ?>
            <?php  
              $jenis = $rowth->kode_jenis_belanja; 
              $jenisText = substr_replace($jenis,"", 0, -1);
              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis'")->row();
            ?>
            <tr>
              <td class="left right">5 . <?php echo $jenisText; ?></td>
              <td class="left right bolder" style="padding-left: 10px;"><?php echo $rowth->jenis; ?></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
            </tr>
            <?php  
              $kategori = $rowth->kode_kategori_belanja;
              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
            ?>
            <tr>
              <td class="left right">5 . <?php echo $jenisText.' . '.$kategori; ?></td>
              <td class="left right bolder" style="padding-left: 15px;"><?php echo $rowth->kategori; ?></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
            </tr>
            <?php  
              $subkategori = $rowth->kode_sub_kategori_belanja;
              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
            ?>
            <tr>
              <td class="left right">5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
              <td class="left right bolder" style="padding-left: 20px;"><?php echo $rowth->subkategori; ?></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
            </tr>
            <?php  
              $kdbelanja = $rowth->kode_belanja;
              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
            ?>
            <tr>
              <td class="left right">5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
              <td class="left right bolder" style="padding-left: 25px;"><?php echo $rowth->belanja; ?></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid"></td>
              <td class="mid" align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
            </tr>
            <?php  
              $uraianbelanja = $rowth->uraian_upper;
              $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
              $uraianbelanja2 = '"'.$uraianbelanja2.'"';
              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_ppas_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
            ?>
            <tr>
              <td class="left right"></td>
              <td class="left right" style="padding-left: 30px;"><?php echo $rowth->uraian_belanja; ?></td>
              <td class="mid" style="border-bottom-style:dashed;"></td>
              <td class="mid" style="border-bottom-style:dashed;"></td>
              <td class="mid" style="border-bottom-style:dashed;"></td>
              <td class="mid" align='right' style="border-bottom-style:dashed; padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
            </tr>
            <tr>
              <td class="left right"></td>
              <td class="left right" style="padding-left: 30px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
              <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->volume, 2); ?></td>
              <td class="left bottom right" align='center' style='padding-left:0px;'><?php echo $rowth->satuan; ?></td>
              <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->nominal_satuan, 2); ?></td>
              <td class="left bottom right" align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
            </tr>
          <?php endif ?>
        <?php endforeach ?>
        <tr>
          <td class="mid bolder" colspan="6" style="font-size: 8px;">Formulir Belanja SKPD - <?php echo $this->session->userdata('nama_skpd'); ?></td>
        </tr>
        <tr>
          <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"></td>
          <td colspan="4" align="center" style='padding-left:0px;'>
            Semarapura, <?php echo Formatting::dateINA(date('Y-m-d')); ?> <br>
            <?php echo $kode_skpd->nama_jabatan; ?> <br>
            <br><br><br>
            <u><?php echo $kode_skpd->kaskpd_nama; ?></u> <br>
            <?php echo $kode_skpd->pangkat_golongan; ?> <br>
            NIP : <?php echo $kode_skpd->kaskpd_nip; ?>
          </td>
        </tr>
      </tbody>
    </table>

