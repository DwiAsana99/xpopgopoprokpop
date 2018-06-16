<article class="module width_full">
	<?php if ($per == 1): ?>	
		<header>
		  <h3>Sumber Dana Per SKPD</h3>
		</header>
		<div class="module_content"; style="overflow:auto">
			<table class="table-common" style="width:100%">
				<thead>
					<tr>
						<th>Nama SKPD / Nama Sumber Dana</th>
						<th>Nominal</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($data1 as $key1 => $row1): ?>
					<?php 
						$total1 = $this->db->query("SELECT SUM(subtotal) as total FROM t_renja_belanja_kegiatan WHERE id_keg IN (
							SELECT id FROM t_renja_prog_keg WHERE 
							id_skpd = '$row1->id_skpd'
							AND tahun = '$ta') AND is_tahun_sekarang = 1")->row();
						$data2 = $this->db->query("SELECT kode_sumber_dana AS id_sumber
							,(SELECT sumber_dana FROM m_sumber_dana WHERE id = id_sumber) AS sumber_dana
							,SUM(subtotal) as total
							FROM t_renja_belanja_kegiatan WHERE 
							id_keg IN (
								SELECT id FROM t_renja_prog_keg WHERE 
								id_skpd IN (
									SELECT id_skpd FROM m_skpd WHERE 
									kode_unit IN (
										SELECT id_skpd FROM t_renja WHERE tahun = '$ta'
									)) AND tahun = '$ta' AND id_skpd = '$row1->id_skpd') AND tahun = '$ta'")->result();
					 ?>
					 <tr style="background-color: #78cbfd; font-weight: bold;">
					 	<td><?php echo $row1->nama_skpd; ?></td>
					 	<td align="right"><?php echo Formatting::currency($total1->total, 2); ?></td>
					 </tr>
					 <?php foreach ($data2 as $key2 => $row2): ?>
					 	<tr>
					 		<td>&emsp;<?php echo $row2->sumber_dana; ?></td>
					 		<td align="right"><?php echo Formatting::currency($row2->total, 2); ?></td>
					 	</tr>
					 <?php endforeach ?>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php elseif($per == 2): ?>
		<header>
		  <h3>SKPD per Sumber Dana</h3>
		</header>
		<div class="module_content"; style="overflow:auto">
			<table class="table-common" style="width:100%">
				<thead>
					<tr>
						<th>Nama Sumber Dana / Nama SKPD</th>
						<th>Nominal</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($data1 as $key1 => $row1): ?>
					<?php 
						$data2 = $this->db->query("SELECT id_skpd, nama_skpd
							FROM m_skpd 
							WHERE id_skpd IN
							(
								SELECT id_skpd 
								FROM t_renja_prog_keg
								WHERE id IN (
									SELECT id_keg
									FROM t_renja_belanja_kegiatan 
									WHERE kode_sumber_dana = '$row1->id_sumber'
									AND is_tahun_sekarang = 1
								)
								AND tahun = '$ta'
							)")->result();
					 ?>
					 <tr style="background-color: #78cbfd; font-weight: bold;">
					 	<td><?php echo $row1->sumber_dana; ?></td>
					 	<td align="right"><?php echo Formatting::currency($row1->total, 2); ?></td>
					 </tr>
					 <?php foreach ($data2 as $key2 => $row2): ?>
					 	<?php 
					 		$total2 = $this->db->query("SELECT SUM(subtotal) as total FROM t_renja_belanja_kegiatan WHERE id_keg IN (
								SELECT id FROM t_renja_prog_keg WHERE 
								id_skpd = '$row2->id_skpd'
								AND tahun = '$ta') AND is_tahun_sekarang = 1")->row();
					 	 ?>
					 	<tr>
					 		<td>&emsp;<?php echo $row2->nama_skpd; ?></td>
					 		<td align="right"><?php echo Formatting::currency($total2->total, 2); ?></td>
					 	</tr>
					 <?php endforeach ?>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php endif ?>
</article>