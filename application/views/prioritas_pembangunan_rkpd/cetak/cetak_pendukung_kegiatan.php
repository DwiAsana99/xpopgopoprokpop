<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=".$filenameEX.".xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");

?>
<style type="text/css">
	table, th, td {
		vertical-align: top;
	}
</style>
<div align="center" style="font-size: 18px; font-weight: bold; padding-bottom: 20px">
	<font size="5">Prioritas Pembangunan Daerah Tahun <?php echo $tahun; ?></font>
</div>
<table border="1" style="border-collapse: collapse;"  width="100%" >
	<thead>
		<tr>
			<th>No.</th>
			<th>Prioritas Pembangunan</th>
			<th>Sasaran Pembangunan</th>
			<th>Program Prioritas</th>
			<th>Kegiatan Prioritas</th>
			<th width="10%">Pagu</th>
			<th>Program / Kegiatan Perangkat Daerah</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$total_pagu = 0;
			foreach ($prioritas as $key_prioritas => $row_prioritas) {
				$sasaran = $this->m_prioritas_pembangunan_rkpd->get_all_sasaran($row_prioritas->id)->result();
				$tot_for_prioritas = $this->m_prioritas_pembangunan_rkpd->get_all_kegiatan($row_prioritas->id, NULL, NULL)->num_rows();
				foreach ($sasaran as $key_sasaran => $row_sasaran) {
					$program = $this->m_prioritas_pembangunan_rkpd->get_all_program($row_prioritas->id, $row_sasaran->id)->result();
					$tot_for_sasaran = $this->m_prioritas_pembangunan_rkpd->get_all_kegiatan($row_prioritas->id, $row_sasaran->id, NULL)->num_rows();
					foreach ($program as $key_program => $row_program) {
						$kegiatan = $this->m_prioritas_pembangunan_rkpd->get_all_kegiatan($row_prioritas->id, $row_sasaran->id, $row_program->id)->result();
						$tot_for_program = $this->m_prioritas_pembangunan_rkpd->get_all_kegiatan($row_prioritas->id, $row_sasaran->id, $row_program->id)->num_rows();
						foreach ($kegiatan as $key_kegiatan => $row_kegiatan) {
							$prog_keg = $this->m_prioritas_pembangunan_rkpd->get_prog_keg_by_prioritas($row_kegiatan->id, 2)->result();
							$pagu = $this->db->query('SELECT SUM(nominal) AS nom_renja FROM t_renja_prog_keg WHERE id_prioritas_daerah = "'.$row_kegiatan->id.'"')->row();
							$total_pagu += $pagu->nom_renja;
		?>
			<tr>
				<?php if ($key_sasaran == 0 && $key_program == 0 && $key_kegiatan == 0): ?>
					<td rowspan="<?php echo $tot_for_prioritas; ?>"><?php echo ($key_prioritas+1); ?></td>
					<td rowspan="<?php echo $tot_for_prioritas; ?>"><?php echo $row_prioritas->id_prioritas; ?></td>
				<?php endif ?>
				<?php if ($key_program == 0 && $key_kegiatan == 0): ?>
					<td rowspan="<?php echo $tot_for_sasaran ?>"><?php echo $row_sasaran->sasaran; ?></td>
				<?php endif ?>
				<?php if ($key_kegiatan == 0): ?>
					<td rowspan="<?php echo $tot_for_program ?>"><?php echo $row_program->id_prog_or_keg; ?></td>
				<?php endif ?>
				<td><?php echo $row_kegiatan->id_prog_or_keg; ?></td>
				<td align="right"><?php echo Formatting::currency($pagu->nom_renja, 2); ?></td>
				<td>
					<table border="1" style="border-collapse: collapse; margin: -2px -2px -1px -2px;" width="101.5%">					
						<?php
							foreach ($prog_keg as $row_prog_keg) {
								if ($parent != $row_prog_keg->parent) {
									$parent = $row_prog_keg->parent;
									$pro = $this->db->query('SELECT * FROM t_renja_prog_keg WHERE id = '.$parent)->row();
									echo "<tr>
											<td>".$pro->kd_urusan."</td>
											<td>".$pro->kd_bidang."</td>
											<td>".$pro->kd_program."</td>
											<td></td>
											<td>".$pro->nama_prog_or_keg."</td>
										</tr>";
								}
								echo "<tr>
										<td>".$row_prog_keg->kd_urusan."</td>
										<td>".$row_prog_keg->kd_bidang."</td>
										<td>".$row_prog_keg->kd_program."</td>
										<td>".$row_prog_keg->kd_kegiatan."</td>
										<td>".$row_prog_keg->nama_prog_or_keg."</td>
									</tr>";
							}
							$parent = '';
						?>
					</table>
				</td>
			</tr>
		<?php
						}
					}
				}
			}
		?>
		<tr>
			<th colspan="5">TOTAL PAGU : </th>
			<th align="right"><?php echo Formatting::currency($total_pagu, 2); ?></th>
			<th></th>
		</tr>
	</tbody>
</table>