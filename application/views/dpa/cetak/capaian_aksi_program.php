<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=".$filenameEX.".xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");

 ?>
<div align="center"><font size="5">Tabel Capaian Aksi Program</font></div>
<table border="1" style="border-collapse: collapse">
	<thead>
		<tr>
			<th>NO.</th>
			<th>PROGRAM/KEGIATAN</th>
			<th>UKURAN KEBERHASILAN</th>
			<th>BULAN</th>
			<th>URAIAN TARGET KINERJA</th>
			<th>TARGET</th>
			<th>TARGET KOMULATIF</th>
			<th>BOBOT (%)</th>
			<th>BOBOT KOMULATIF (%)</th>
			<th>REALISASI TARGET</th>
			<th>REALISASI TARGET KOMULATIF</th>
			<th>CAPAIAN KINERJA TARGET (%)</th>
			<th>CAPAIAN KINERJA BULANAN (%)</th>
			<th>PROGRES FISIK (%)</th>
			<th>ANGGARAN KOMULATIF (Rp.)</th>
			<th>REALISASI ANGGARAN KOMULATIF (Rp.)</th>
			<th>CAPAIAN KEUANGAN BULANAN (%)</th>
		</tr>
		<tr>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			<th>7</th>
			<th>8</th>
			<th>9</th>
			<th>10</th>
			<th>11</th>
			<th>12 (11/7x100)</th>
			<th>13</th>
			<th>14 (13x9/100)</th>
			<th>15</th>
			<th>16</th>
			<th>17 (16/15x100)</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach ($aksi as $key => $row) {
				$rencana_aksi = $this->m_dpa->get_cetak_rencana_program($row->id, 0)->result();
				$count_for_number = $this->db->query('SELECT id FROM 
					tx_dpa_rencana_aksi
					WHERE id_dpa_prog_keg
					IN (SELECT id FROM tx_dpa_prog_keg WHERE parent = '.$row->id.')')->num_rows();
				$count_per_aksi = $this->m_dpa->get_cetak_rencana_program($row->id)->num_rows();
				$indikator = $this->m_dpa->get_indikator_prog_keg($row->id, true, true);

				foreach ($rencana_aksi as $key2 => $row2) {
					 $aksi_per_bulan = $this->m_dpa->get_cetak_rencana_program($row2->id_dpa_prog_keg,$row2->bulan)->result();
					 $count_per_bulan = count($aksi_per_bulan);

					$cap_bulanan_prog = $this->db->query('SELECT SUM(capaian/target*100)/COUNT(capaian) AS progres 
						FROM tx_dpa_rencana_aksi 
						WHERE id_dpa_prog_keg = "'.$row2->id_dpa_prog_keg.'" AND bulan="'.$row2->bulan.'" 
						ORDER BY bulan,get_date')->row()->progres;

					 foreach ($aksi_per_bulan as $key3 => $row3) {

					 	$bobot_kom_prog = $this->db->query('SELECT SUM(bobot) AS bobot FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = "'.$row3->id_dpa_prog_keg.'" AND bulan<='.$row3->bulan)->row()->bobot;
					 	$target_kom_prog = $this->db->query('SELECT SUM(target) AS target FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = "'.$row3->id_dpa_prog_keg.'" AND bulan<='.$row3->bulan.' AND aksi = "'.$row3->aksi.'"')->row()->target;
					 	$capaian_kom_prog = $this->db->query('SELECT SUM(capaian) AS capaian FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = "'.$row3->id_dpa_prog_keg.'" AND bulan<='.$row3->bulan.' AND aksi = "'.$row3->aksi.'"')->row()->capaian;
					 	
		?>
			<tr>
				<?php if ( $key2 == 0 && $key3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_for_number+$count_per_aksi+1 ?>"><?php echo $i; ?></td>
					<td valign="top" rowspan="<?php echo $count_per_aksi ?>"><?php echo $row->kd_urusan.".".$row->kd_bidang.".".$row->kd_program." ".$row->nama_prog_or_keg; ?></td>
					<td valign="top" rowspan="<?php echo $count_per_aksi; ?>">
						<?php 
							foreach ($indikator as $row_indik) {
								echo $row_indik->indikator." ".$row_indik->target." ".$row_indik->nama_value."<br><br>";
							}
						?>
					</td>
				<?php } ?>
				<?php if ( $key3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_per_bulan ?>">BLN <?php echo $row2->bulan; ?></td>
				<?php } ?>
					<td valign="top"><?php echo $row3->aksi; ?></td>
					<td valign="top"><?php echo number_format($row3->target, 2, ',', '.').' '.$row3->satuan; ?></td>
					<td valign="top"><?php echo number_format($target_kom_prog, 2, ',', '.').' '.$row3->satuan; ?></td>
					<td valign="top" align="right"><?php echo number_format($row3->bobot, 2, ',', '.'); ?></td>
					<td valign="top" align="right"><?php echo number_format($bobot_kom_prog, 2, ',', '.'); ?></td>
					<td valign="top" align="right"><?php echo number_format($row3->capaian, 2, ',', '.'); ?></td>
					<td valign="top" align="right"><?php echo number_format($capaian_kom_prog, 2, ',', '.'); ?></td>
					<td valign="top" align="right"><?php echo formatting::currency($row3->for_capaian, 2); ?></td>
				<?php if ( $key3 == 0) { ?>
					<td valign="top" align="right"><?php echo formatting::currency($cap_bulanan_prog, 2); ?></td>
					<td valign="top" align="right"><?php echo formatting::currency($cap_bulanan_prog*$bobot_kom_prog/100, 2); ?></td>
				<?php } ?>
				<?php if ( $key2 == 0 && $key3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_per_aksi; ?>" colspan="3">&nbsp;</td>
				<?php } ?>
			</tr>	
		<?php }} ?>
			<tr>
				<td colspan="10">&nbsp;</td>
			</tr>
		<?php

			$keg = $this->m_dpa->get_all_kegiatan($row->id, $row->id_skpd, $row->tahun);
			foreach ($keg as $key_keg => $row_keg) { 
				$rencana_aksi_keg = $this->m_dpa->get_cetak_rencana_program($row_keg->id, 0)->result();
				$count_per_aksi_keg = $this->m_dpa->get_cetak_rencana_program($row_keg->id)->num_rows();
				$indikator_keg = $this->m_dpa->get_indikator_prog_keg($row_keg->id, true, true);
				$sum_anggaran_komulatif_keg = 0;
				$realisasi_komulatif_keg = 0;

				foreach ($rencana_aksi_keg as $key_keg2 => $row_keg2) {
					 $aksi_per_bulan_keg = $this->m_dpa->get_cetak_rencana_program($row_keg2->id_dpa_prog_keg,$row_keg2->bulan)->result();
					 $count_per_bulan_keg = count($aksi_per_bulan_keg);
					 $sum_anggaran_aksi_keg = $this->m_dpa->sum_anggaran_rencana_aksi($row_keg2->id_dpa_prog_keg,$row_keg2->bulan)->row();
					 $realisasi_keg = $this->m_dpa->get_realisasi_dari_triwulan($row_keg2->id_dpa_prog_keg,$row_keg2->bulan)->row();

					 $sum_anggaran_komulatif_keg += $sum_anggaran_aksi_keg->sum_ang;
					 $realisasi_komulatif_keg = $realisasi_keg->realisasi;

					 $cap_bulanan_keg = $this->db->query('SELECT SUM(capaian/target*100)/COUNT(capaian) AS progres 
						FROM tx_dpa_rencana_aksi 
						WHERE id_dpa_prog_keg = "'.$row_keg2->id_dpa_prog_keg.'" AND bulan="'.$row_keg2->bulan.'" 
						ORDER BY bulan,get_date')->row()->progres;

					foreach ($aksi_per_bulan_keg as $key_keg3 => $row_keg3) {

					 	$for_serapan_keg = number_format(0, 2, ',', '.');
					 	if ($sum_anggaran_aksi_keg->sum_ang > 0) {
					 		$for_serapan_keg = number_format(($realisasi_komulatif_keg/$sum_anggaran_komulatif_keg)*100, 2, ',', '.');
					 	}

					 	$bobot_kom_keg = $this->db->query('SELECT SUM(bobot) AS bobot FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = "'.$row_keg3->id_dpa_prog_keg.'" AND bulan<='.$row_keg3->bulan)->row()->bobot;
					 	$target_kom_keg = $this->db->query('SELECT SUM(target) AS target FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = "'.$row_keg3->id_dpa_prog_keg.'" AND bulan<='.$row_keg3->bulan.' AND aksi = "'.$row_keg3->aksi.'"')->row()->target;
					 	$capaian_kom_keg = $this->db->query('SELECT SUM(capaian) AS capaian FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = "'.$row_keg3->id_dpa_prog_keg.'" AND bulan<='.$row_keg3->bulan.' AND aksi = "'.$row_keg3->aksi.'"')->row()->capaian;
		?>
			<tr>
				<?php if ($key_keg3 == 0 && $key_keg2 == 0) { ?>
					
					<td valign="top" rowspan="<?php echo $count_per_aksi_keg; ?>"><?php echo $row_keg->kd_urusan.".".$row_keg->kd_bidang.".".$row_keg->kd_program.".".$row_keg->kd_kegiatan." ".$row_keg->nama_prog_or_keg; ?></td>
					<td valign="top" rowspan="<?php echo $count_per_aksi_keg; ?>">
						<?php 
							foreach ($indikator_keg as $row_indik_keg) {
								echo $row_indik_keg->indikator." ".$row_indik_keg->target." ".$row_indik_keg->nama_value."<br><br>";
							}
						?>
					</td>
				<?php } ?>
				<?php if ($key_keg3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_per_bulan_keg; ?>">BLN <?php echo $row_keg2->bulan; ?></td>
				<?php } ?>
					<td valign="top"><?php echo $row_keg3->aksi ?></td>
					<td valign="top"><?php echo number_format($row_keg3->target, 2, ',', '.').' '.$row_keg3->satuan; ?></td>
					<td valign="top"><?php echo number_format($target_kom_keg, 2, ',', '.').' '.$row_keg3->satuan; ?></td>
					<td valign="top" align="right"><?php echo number_format($row_keg3->bobot, 2, ',', '.'); ?></td>
				<?php if ($key_keg3 == 0){ ?>
					<td valign="top" align="right" rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo number_format($bobot_kom_keg, 2, ',', '.'); ?></td>
				<?php } ?>
					<td valign="top" align="right"><?php echo number_format($row_keg3->capaian, 2, ',', '.'); ?></td>
					<td valign="top" align="right"><?php echo number_format($capaian_kom_keg, 2, ',', '.'); ?></td>

					<td valign="top" align="right"><?php echo formatting::currency($row_keg3->for_capaian, 2); ?></td>
				<?php if ($key_keg3 == 0) { ?>
					<td valign="top" align="right" rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo formatting::currency($cap_bulanan_keg, 2); ?></td>
					<td valign="top" align="right" rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo formatting::currency($cap_bulanan_keg*$bobot_kom_keg/100, 2); ?></td>
					<td valign="top" align="right" rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo number_format($sum_anggaran_komulatif_keg, 2, ',', '.'); ?></td>
					<td valign="top" align="right" rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo number_format($realisasi_komulatif_keg, 2, ',', '.'); ?></td>
					<td valign="top" align="right" rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo $for_serapan_keg; ?></td>
				<?php } ?>
			</tr>
		<?php }}}$i++; } ?>
	</tbody>
</table>