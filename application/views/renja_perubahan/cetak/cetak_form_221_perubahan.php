
    <style type="text/css">
      table {
        color: black;
        top: 0;
        border-collapse: collapse;
        font-family: sans-serif;
        font-size: 11px;
      }
      td {
        padding-left: 5px;
        padding-right: 5px;
      }
      td.nopadding {
        padding: 1px;
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
        border-top: 0px solid black;
      }
      td.left {
        border-left: 0px solid black;
      }
      td.mid {
        border: 0px solid black;
      }
      td.right {
        border-right: 0px solid black;
      }
      td.bottom {
        border-bottom: 0px solid black;
      }
      td.bolder{
        font-weight: bold;
      }
      .topAlign{
        vertical-align: top;
      }

    </style>

  <?php 
    $kode_skpd_unit = $this->db->query('SELECT m_skpd.*, MID(kode_skpd, 1, 1) AS urusan ,MID(kode_skpd, 3, 2) AS bidang ,MID(kode_skpd, 6, 2) AS unit ,MID(kode_skpd, 9, 2) AS sub_unit FROM m_skpd WHERE id_skpd = (SELECT kode_unit FROM m_skpd WHERE id_skpd = "'.$this->session->userdata("id_skpd").'")')->row();
    $kode_skpd = $this->db->query('SELECT m_skpd.*, MID(kode_skpd, 1, 1) AS urusan ,MID(kode_skpd, 3, 2) AS bidang ,MID(kode_skpd, 6, 2) AS unit ,MID(kode_skpd, 9, 2) AS sub_unit FROM m_skpd WHERE id_skpd = "'.$this->session->userdata("id_skpd").'"')->row();

// print_r($kegiatan);
  ?>

  <table width="100%" border="1">
    <tr>
      <td width="10%" rowspan="2" align="center"><?php echo $logo; ?></td>
      <td rowspan="1" colspan="7" align="center">
        <!-- <strong>RENCANA KERJA ANGGARAN PERUBAHAN<br>SATUAN KERJA PERANGKAT DAERAH</strong> -->
        <strong>REKAP BELANJA KEGIATAN PERUBAHAN<br>RENJA PERANGKAT DAERAH</strong>
      </td>
      <!-- <td align="center">
        <strong>NOMOR RKAP SKPD</strong>
      </td> -->
      <td width="9%" rowspan="2" align="center">
        <strong>Formulir<br>RKAP SKPD<br>2.2.1</strong>
      </td>
    </tr>
    <!-- <tr>
      <td class="nopadding">
        <table width="100%" border="1">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr> -->
    <tr>
      <td colspan="7" align="center">
        <strong>PEMERINTAH KABUPATEN KLUNGKUNG</strong>
        <br>Tahun Anggaran <?php echo $th_anggaran; ?>
      </td>
    </tr>
    <tr>
      <td colspan="3" class="bolder">Urusan Pemerintahan</td>
      <td colspan="2"><?php echo $kegiatan->kode_urusan; ?></td>
      <td colspan="4"><?php echo $kegiatan->nama_urusan; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="bolder">Bidang Pemerintahan</td>
      <td colspan="2"><?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang; ?></td>
      <td colspan="4"><?php echo $kegiatan->nama_bidang; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="bolder">Unit Organisasi</td>
      <td colspan="2"><?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang.' . '.$kode_skpd_unit->unit; ?></td>
      <td colspan="4"><?php echo $kode_skpd_unit->nama_skpd; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="bolder">Sub Unit Organisasi</td>
      <td colspan="2"><?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang.' . '.$kode_skpd->unit.' . '.$kode_skpd->sub_unit; ?></td>
      <td colspan="4"><?php echo $kode_skpd->nama_skpd; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="bolder">Program</td>
      <td colspan="2"><?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang.' . '.$kode_skpd->unit.' . '.$kode_skpd->sub_unit.' . '.$kegiatan->kd_program; ?></td>
      <td colspan="4"><?php echo $kegiatan->Ket_Program; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="bolder">Kegiatan</td>
      <td colspan="2"><?php echo $kegiatan->kode_urusan.' . '.$kegiatan->kode_bidang.' . '.$kode_skpd->unit.' . '.$kode_skpd->sub_unit.' . '.$kegiatan->kd_program.' . '.$kegiatan->kd_kegiatan; ?></td>
      <td colspan="4"><?php echo $kegiatan->nama_prog_or_keg; ?></td>
    </tr>
    <tr>
      <td colspan="3" class="bolder">Lokasi Kegiatan</td>
      <td colspan="6"><?php echo $kegiatan->lokasi; ?></td>
    </tr>
    <tr>
      <td colspan="4" class="bolder right">Latar belakang perubahan / dianggarkan dalam Perubahan APBD</td>
      <td colspan="5" class="left">: <?php echo (!empty($kegiatan->keterangan))?$kegiatan->keterangan:'-'; ?></td>
    </tr>
    <tr>
      <td colspan="9" align="center">
        <strong>INDIKATOR & TOLAK UKUR KINERJA BELANJA LANGSUNG</strong>
      </td>
    </tr>
    <tr>
      <td rowspan="2" colspan="2" class="bolder" align="center">INDIKATOR</td>
      <td colspan="4" class="bolder" align="center">TOLAK UKUR KINERJA</td>
      <td colspan="3" class="bolder" align="center">TARGET KINERJA</td>
    </tr>
    <tr>
      <td colspan="2" class="bolder" align="center">SEBELUM PERUBAHAN</td>
      <td colspan="2" class="bolder" align="center">SETELAH PERUBAHAN</td>
      <td class="bolder" align="center">SEBELUM PERUBAHAN</td>
      <td colspan="2" class="bolder" align="center">SETELAH PERUBAHAN</td>
    </tr>
    <tr>
      <td colspan="2" class="bolder" width="12%">CAPAIAN PROGRAM</td>
      <td colspan="2" width="22%">
        <?php foreach ($capaian as $cap): ?>
          <?php echo $cap->indikator."<br>" ?>
        <?php endforeach ?>
      </td>
      <td colspan="2" width="22%">
        <?php foreach ($capaian as $cap): ?>
          <?php echo $cap->indikator."<br>" ?>
        <?php endforeach ?>
      </td>
      <td width="22%" align="right">
        <?php foreach ($capaian as $cap): ?>
          <?php echo $cap->target." ".$cap->satuan_target."<br>" ?>
        <?php endforeach ?>
      </td>
      <td colspan="2" width="22%" align="right">
        <?php foreach ($capaian as $cap): ?>
          <?php echo $cap->target_thndpn." ".$cap->satuan_target_thndpn."<br>" ?>
        <?php endforeach ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="bolder">MASUKAN</td>
      <td colspan="2">
        Uang
      </td>
      <td colspan="2">
        Uang
      </td>
      <td align="right">
        <?php echo 'Rp. '.Formatting::currency($kegiatan->nominal); ?>
      </td>
      <td colspan="2" align="right">
        <?php echo 'Rp. '.Formatting::currency($kegiatan->nominal_thndpn); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="bolder">KELUARAN</td>
      <td colspan="2">
        <?php foreach ($keluaran as $kel): ?>
          <?php echo $kel->indikator."<br>" ?>
        <?php endforeach ?>
      </td>
      <td colspan="2">
        <?php foreach ($keluaran as $kel): ?>
          <?php echo $kel->indikator."<br>" ?>
        <?php endforeach ?>
      </td>
      <td align="right">
        <?php foreach ($keluaran as $kel): ?>
          <?php echo $kel->target." ".$kel->satuan_target."<br>" ?>
        <?php endforeach ?>
      </td>
      <td colspan="2" align="right">
        <?php foreach ($keluaran as $kel): ?>
          <?php echo $kel->target." ".$kel->satuan_target_thndpn."<br>" ?>
        <?php endforeach ?>
      </td>
    </tr>
    <tr>
      <td colspan="9" align="center" class="bolder">
        RINCIAN PERUBAHAN ANGGARAN BELANJA LANGSUNG PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH
      </td>
    </tr>
  </table>

  <table width="100%" border="1" style="margin-top: -2px;">
    <thead>
      <tr>
        <td rowspan="3" align="center" class="bolder" width="80px">KODE REKENING</td>
        <td rowspan="3" align="center" class="bolder" width="200px">RINCIAN</td>
        <td colspan="4" align="center" class="bolder">SEBELUM PERUBAHAN</td>
        <td colspan="4" align="center" class="bolder">SETELAH PERUBAHAN</td>
        <td colspan="2" align="center" class="bolder">Bertambah / (Berkurang)</td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="bolder">RINCIAN PERHITUNGAN</td>
        <td rowspan="2" align="center" class="bolder">Jumlah</td>
        <td colspan="3" align="center" class="bolder">RINCIAN PERHITUNGAN</td>
        <td rowspan="2" align="center" class="bolder">Jumlah</td>
        <td rowspan="2" align="center" class="bolder">(Rp)</td>
        <td rowspan="2" align="center" class="bolder">(%)</td>
      </tr>
      <tr>
        <td align="center" class="bolder">Volume</td>
        <td align="center" class="bolder">Satuan</td>
        <td align="center" class="bolder">Harga Satuan</td>
        <td align="center" class="bolder">Volume</td>
        <td align="center" class="bolder">Satuan</td>
        <td align="center" class="bolder">Harga Satuan</td>      
      </tr>
      <tr>
        <td align="center" class="bolder">1</td>
        <td align="center" class="bolder">2</td>
        <td align="center" class="bolder">3</td>
        <td align="center" class="bolder">4</td>
        <td align="center" class="bolder">5</td>
        <td align="center" class="bolder">6 = 3 x 5</td>
        <td align="center" class="bolder">7</td>
        <td align="center" class="bolder">8</td>
        <td align="center" class="bolder">9</td>
        <td align="center" class="bolder">10 = 7 x 9</td>
        <td align="center" class="bolder">11</td>
        <td align="center" class="bolder">12</td>
      </tr>
    </thead>
    <?php 
      $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
      $idk_ng = $id_kegiatan;
      $ta_ng = $th_anggaran;
      $table_belanja = 't_renja_perubahan_belanja_kegiatan';

      function whereText($jns=NULL, $kat=NULL, $sub=NULL, $bel=NULL, $uraian=NULL){
        $where = '';
        $where .= (!empty($jns))?" AND kode_jenis_belanja = '$jns'":"";
        $where .= (!empty($kat))?" AND kode_kategori_belanja = '$kat'":"";
        $where .= (!empty($sub))?" AND kode_sub_kategori_belanja = '$sub'":"";
        $where .= (!empty($bel))?" AND kode_belanja = '$bel'":"";
        $where .= (!empty($uraian))?" AND uraian_belanja = $uraian":"";
        return $where;
      }

      function selisih($sum_tot_sblm, $sum_tot_stlh){
        $selisihRp = $sum_tot_stlh - $sum_tot_sblm;
        $data['selisihRp'] = ($selisihRp>=0)?Formatting::currency($selisihRp):'('.Formatting::currency(abs($selisihRp)).')';
        if ($sum_tot_sblm <> 0) {
          $selisihPersen = ($selisihRp/$sum_tot_sblm)*100;
        }
        // elseif ($selisihRp < 0 && $sum_tot_stlh == 0) {
        //   $selisihPersen = ($sum_tot_sblm/$sum_tot_stlh)*100;
        // }
        else{ 
          $selisihPersen = 0;
        }
        $data['selisihPersen'] = ($selisihPersen>=0)?Formatting::currency($selisihPersen, 2):'('.Formatting::currency(abs($selisihPersen), 2).')';
        return (object) $data;
      }
      // print_r($tahun1[0]);
    ?>
    <?php foreach ($tahun1 as $key => $rowth): ?>
      <?php if ($rowth->kode_jenis_belanja == $jenis): ?>
        <?php if ($rowth->kode_kategori_belanja == $kategori): ?>
          <?php if ($rowth->kode_sub_kategori_belanja == $subkategori): ?>
            <?php if ($rowth->kode_belanja == $kdbelanja): ?>
              <?php if ($rowth->uraian_upper == $uraianbelanja): ?>
    <?php $hitung = selisih($rowth->subtotal, $rowth->subtotalperubahan); ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volume, 2); ?></td>
      <td align="center"><?php echo $rowth->satuan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominal_satuan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotal); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volumeperubahan, 2); ?></td>
      <td align="center"><?php echo $rowth->satuanperubahan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominalperubahan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotalperubahan); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
              <?php else: ?>
    <?php
      $uraianbelanja = $rowth->uraian_upper;
      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja, $uraianbelanja2);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;"><?php echo $rowth->uraian_belanja; ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihRp; ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php $hitung = selisih($rowth->subtotal, $rowth->subtotalperubahan); ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volume, 2); ?></td>
      <td align="center"><?php echo $rowth->satuan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominal_satuan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotal); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volumeperubahan, 2); ?></td>
      <td align="center"><?php echo $rowth->satuanperubahan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominalperubahan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotalperubahan); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
              <?php endif ?>
            <?php else: ?>
    <?php
      $kdbelanja = $rowth->kode_belanja; 
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
      <td class="top bottom" style="padding-left: 21px;"><?php echo $rowth->belanja; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $uraianbelanja = $rowth->uraian_upper;
      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja, $uraianbelanja2);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;"><?php echo $rowth->uraian_belanja; ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihRp; ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php $hitung = selisih($rowth->subtotal, $rowth->subtotalperubahan); ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volume, 2); ?></td>
      <td align="center"><?php echo $rowth->satuan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominal_satuan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotal); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volumeperubahan, 2); ?></td>
      <td align="center"><?php echo $rowth->satuanperubahan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominalperubahan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotalperubahan); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
            <?php endif ?>
          <?php else: ?>
    <?php
      $subkategori = $rowth->kode_sub_kategori_belanja; 
      $where = whereText($jenis, $kategori, $subkategori);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
      <td class="top bottom bolder" style="padding-left: 16px;"><?php echo $rowth->subkategori; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $kdbelanja = $rowth->kode_belanja; 
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
      <td class="top bottom" style="padding-left: 21px;"><?php echo $rowth->belanja; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $uraianbelanja = $rowth->uraian_upper;
      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja, $uraianbelanja2);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;"><?php echo $rowth->uraian_belanja; ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihRp; ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php $hitung = selisih($rowth->subtotal, $rowth->subtotalperubahan); ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volume, 2); ?></td>
      <td align="center"><?php echo $rowth->satuan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominal_satuan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotal); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volumeperubahan, 2); ?></td>
      <td align="center"><?php echo $rowth->satuanperubahan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominalperubahan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotalperubahan); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
          <?php endif ?>
        <?php else: ?>
    <?php
      $kategori = $rowth->kode_kategori_belanja; 
      $where = whereText($jenis, $kategori);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori; ?></td>
      <td class="top bottom bolder" style="padding-left: 11px;"><?php echo $rowth->kategori; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $subkategori = $rowth->kode_sub_kategori_belanja; 
      $where = whereText($jenis, $kategori, $subkategori);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
      <td class="top bottom bolder" style="padding-left: 16px;"><?php echo $rowth->subkategori; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $kdbelanja = $rowth->kode_belanja; 
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
      <td class="top bottom" style="padding-left: 21px;"><?php echo $rowth->belanja; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $uraianbelanja = $rowth->uraian_upper;
      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja, $uraianbelanja2);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;"><?php echo $rowth->uraian_belanja; ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihRp; ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php $hitung = selisih($rowth->subtotal, $rowth->subtotalperubahan); ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volume, 2); ?></td>
      <td align="center"><?php echo $rowth->satuan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominal_satuan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotal); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volumeperubahan, 2); ?></td>
      <td align="center"><?php echo $rowth->satuanperubahan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominalperubahan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotalperubahan); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
        <?php endif ?>
      <?php else: ?>
    <?php if ($key == 0): ?>
      <?php
        $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
          WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng'")->row()->sumtot;
        $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
          WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng'")->row()->sumtot;
        $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
      ?>
      <tr class="topAlign">
        <td class="bottom">5</td>
        <td class="bottom bolder">BELANJA</td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
        <td align="right"><?php echo $hitung->selisihRp; ?></td>
        <td align="right"><?php echo $hitung->selisihPersen; ?></td>
      </tr>
    <?php endif ?>
    <?php
      $jenis = $rowth->kode_jenis_belanja; 
      $jenisText = '5 . '.substr_replace($jenis,"", 0, -1);
      $where = whereText($jenis);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText; ?></td>
      <td class="top bottom bolder" style="padding-left: 6px;"><?php echo $rowth->jenis; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $kategori = $rowth->kode_kategori_belanja; 
      $where = whereText($jenis, $kategori);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori; ?></td>
      <td class="top bottom bolder" style="padding-left: 11px;"><?php echo $rowth->kategori; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $subkategori = $rowth->kode_sub_kategori_belanja; 
      $where = whereText($jenis, $kategori, $subkategori);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
      <td class="top bottom bolder" style="padding-left: 16px;"><?php echo $rowth->subkategori; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $kdbelanja = $rowth->kode_belanja; 
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"><?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
      <td class="top bottom" style="padding-left: 21px;"><?php echo $rowth->belanja; ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php
      $uraianbelanja = $rowth->uraian_upper;
      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
      $where = whereText($jenis, $kategori, $subkategori, $kdbelanja, $uraianbelanja2);
      $sum_tot_sblm = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 1 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $sum_tot_stlh = $this->db->query("SELECT sum(subtotal) as sumtot FROM $table_belanja 
        WHERE tahun = '$ta_ng' AND is_tahun_sekarang = 0 AND id_keg = '$idk_ng' $where")->row()->sumtot;
      $hitung = selisih($sum_tot_sblm, $sum_tot_stlh);
    ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;"><?php echo $rowth->uraian_belanja; ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_sblm); ?></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td style="border-bottom-style:dashed;"></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo Formatting::currency($sum_tot_stlh); ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihRp; ?></td>
      <td align="right" style="border-bottom-style:dashed;"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
    <?php $hitung = selisih($rowth->subtotal, $rowth->subtotalperubahan); ?>
    <tr class="topAlign">
      <td class="top bottom"></td>
      <td class="top bottom" style="padding-left: 26px;">- <?php echo $rowth->detil_uraian_belanja; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volume, 2); ?></td>
      <td align="center"><?php echo $rowth->satuan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominal_satuan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotal); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->volumeperubahan, 2); ?></td>
      <td align="center"><?php echo $rowth->satuanperubahan; ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->nominalperubahan); ?></td>
      <td align="right"><?php echo Formatting::currency($rowth->subtotalperubahan); ?></td>
      <td align="right"><?php echo $hitung->selisihRp; ?></td>
      <td align="right"><?php echo $hitung->selisihPersen; ?></td>
    </tr>
      <?php endif ?>
    <?php endforeach ?>
    <tr>
      <td colspan="12" style="font-size: 8px;">Formulir Belanja SKPD - <?php echo $this->session->userdata('nama_skpd'); ?></td>
    </tr>
  </table>
  <table width="100%" style="margin-top: 5px;">
    <tr>
      <td width="70%"></td>
      <td align="center">
        Semarapura, <?php echo Formatting::dateINA(date('Y-m-d')); ?> <br>
        <?php echo $kode_skpd->nama_jabatan; ?> <br>
        <br><br><br>
        <u><?php echo $kode_skpd->kaskpd_nama; ?></u> <br>
        <?php echo $kode_skpd->pangkat_golongan; ?> <br>
        NIP : <?php echo $kode_skpd->kaskpd_nip; ?>
      </td>
    </tr>
  </table>