<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=$namafile.xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");

 ?>

<style type="text/css">
	table td{
		vertical-align: top;
	}
</style>

<div align="center" style="font-size: 24px;">Tabel 7.1 Program Prioritas</div>
<table border="1" style="border-collapse: collapse;" width="100%">
	<thead>
		<tr>
			<th width="55px">NO.</th>
			<th>TUJUAN</th>
			<th>SASARAN</th>
			<th>INDIKATOR SASARAN</th>
			<th width="15px">KONDISI AWAL</th>
			<th width="15px">KONDISI AKHIR</th>
			<th>PROGRAM SKPD</th>
			<th>SKPD PENANGGUNG JAWAB</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($rpjmd as $key_rpjmd => $row_rpjmd) {
				$tujuan = $this->m_rpjmd_trx->get_all_rpjmd_tujuan($row_rpjmd->id);
				foreach ($tujuan as $key_tujuan => $row_tujuan) {
					$sasaran = $this->m_rpjmd_trx->get_all_sasaran($row_rpjmd->id, $row_tujuan->id);
					$tot_ind_per_tujuan = $this->db->query("SELECT t_rpjmd_indikator_sasaran.id FROM t_rpjmd_indikator_sasaran
						INNER JOIN t_rpjmd_sasaran
						ON t_rpjmd_sasaran.id = t_rpjmd_indikator_sasaran.id_sasaran
						WHERE t_rpjmd_sasaran.id_tujuan = '".$row_tujuan->id."'")->num_rows();
					$tot_prog_per_tujuan = $this->db->query("SELECT DISTINCT t_renstra_prog_keg.nama_prog_or_keg, id_prog_rpjmd FROM t_renstra_prog_keg
						INNER JOIN t_rpjmd_sasaran
						ON t_renstra_prog_keg.id_prog_rpjmd = t_rpjmd_sasaran.id
						WHERE t_rpjmd_sasaran.id_tujuan = '".$row_tujuan->id."'")->num_rows();
					$tot_ind_per_tujuan = ($tot_ind_per_tujuan>0)?$tot_ind_per_tujuan:'1';
					$tot_prog_per_tujuan = ($tot_prog_per_tujuan>0)?$tot_prog_per_tujuan:'1'; 

					if ($tot_ind_per_tujuan >= $tot_prog_per_tujuan) {
						$tot_for_tujuan = $tot_ind_per_tujuan;
					}else{
						$tot_for_tujuan = $tot_prog_per_tujuan;
					}
					foreach ($sasaran as $key_sasaran => $row_sasaran) {
						$indikator = $this->m_rpjmd_trx->get_indikator_sasaran($row_sasaran->id, TRUE);
						$tot_indikator = (count($indikator)>0)?count($indikator):'1';
						
						$program = $this->m_rpjmd_trx->get_program_skpd_from_renstra($row_sasaran->id);
						$tot_prog = (count($program)>0)?count($program):'1';
						
						if ($tot_indikator >= $tot_prog) {
							$for_foreach = $indikator;
							$tot_for_sasaran = count($indikator);
						}else{
							$for_foreach = $program;
							$tot_for_sasaran = count($program);
						}

						foreach ($for_foreach as $key_each => $row_each) {
							// print_r($sasaran);
							// print_r($tot_prog);
							echo "<tr>";
							if ($key_sasaran == 0 && $key_each == 0) {
								$no = ($key_tujuan+1);
								echo "<td rowspan='$tot_for_tujuan'>$no.</td>";
								echo "<td rowspan='$tot_for_tujuan'>$row_tujuan->tujuan</td>";
							}
							if ($key_each == 0) {
								echo "<td rowspan='$tot_for_sasaran'>$row_sasaran->sasaran</td>";
							}

							if ($tot_indikator >= $tot_prog) {
								echo "<td>".$indikator[$key_each]->indikator."</td>";
								echo "<td>".Formatting::currency($indikator[$key_each]->kondisi_awal, 2)."</td>";
								echo "<td>".Formatting::currency($indikator[$key_each]->kondisi_akhir, 2)."</td>";
								if (($key_each + 1) == $tot_prog) {
									$skpd = $this->m_rpjmd_trx->get_skpd_by_kode($row_sasaran->id, $program[$key_each]->kd_urusan, $program[$key_each]->kd_bidang, $program[$key_each]->kd_program);
									echo "<td rowspan='".(($tot_indikator-$tot_prog>0)?$tot_indikator-$tot_prog+1:'1')."'>".$program[$key_each]->nama_prog_or_keg."</td>";
									echo "<td rowspan='".(($tot_indikator-$tot_prog>0)?$tot_indikator-$tot_prog+1:'1')."'>"; 
										foreach ($skpd as $row_skpd) {
											echo $row_skpd->nama_skpd.';<br>';
										}
									echo "</td>";
								}elseif(($key_each + 1) <= $tot_prog){
									$skpd = $this->m_rpjmd_trx->get_skpd_by_kode($row_sasaran->id, $program[$key_each]->kd_urusan, $program[$key_each]->kd_bidang, $program[$key_each]->kd_program);
									echo "<td>".$program[$key_each]->nama_prog_or_keg."</td>";
									echo "<td>";
										foreach ($skpd as $row_skpd) {
											echo $row_skpd->nama_skpd.';<br>';
										}
									echo "</td>";
								}
							}else{
								if (($key_each + 1) == $tot_indikator) {
									echo "<td rowspan='".(($tot_prog-$tot_indikator>0)?$tot_prog-$tot_indikator+1:'1')."'>".$indikator[$key_each]->indikator."</td>";
									echo "<td rowspan='".(($tot_prog-$tot_indikator>0)?$tot_prog-$tot_indikator+1:'1')."'>".Formatting::currency($indikator[$key_each]->kondisi_awal, 2)."</td>";
									echo "<td rowspan='".(($tot_prog-$tot_indikator>0)?$tot_prog-$tot_indikator+1:'1')."'>".Formatting::currency($indikator[$key_each]->kondisi_akhir, 2)."</td>";
								}elseif(($key_each + 1) <= $tot_indikator){
									echo "<td>".$indikator[$key_each]->indikator."</td>";
									echo "<td>".Formatting::currency($indikator[$key_each]->kondisi_awal, 2)."</td>";
									echo "<td>".Formatting::currency($indikator[$key_each]->kondisi_akhir, 2)."</td>";
								}
								$skpd = $this->m_rpjmd_trx->get_skpd_by_kode($row_sasaran->id, $program[$key_each]->kd_urusan, $program[$key_each]->kd_bidang, $program[$key_each]->kd_program);
								echo "<td>".$program[$key_each]->nama_prog_or_keg."</td>";
								echo "<td>";
									foreach ($skpd as $row_skpd) {
										echo $row_skpd->nama_skpd.';<br>';
									}
								echo "</td>";
							}

							echo "</tr>";
						}
					}
				}
			}
		?>
	</tbody>

</table>
