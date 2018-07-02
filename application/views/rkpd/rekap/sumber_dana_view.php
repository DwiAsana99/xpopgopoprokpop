<article class="module width_full">
	<?php $total_all = 0; ?>
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
						$data2 = $this->m_rkpd->sumber_dana_rekap($ta, 'skpd_detil', $row1->kode_skpd)->result();
						$total_all += $row1->total;
					 ?>
					 <tr style="background-color: #78cbfd; font-weight: bold;">
					 	<td><?php echo $row1->nama_skpd; ?></td>
					 	<td align="right"><?php echo Formatting::currency($row1->total, 2); ?></td>
					 </tr>
					 <?php foreach ($data2 as $key2 => $row2): ?>
					 	<tr>
					 		<td>&emsp;<?php echo $row2->sumber_dana; ?></td>
					 		<td align="right"><?php echo Formatting::currency($row2->total, 2); ?></td>
					 	</tr>
					 <?php endforeach ?>
				<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total</th>
						<td align="right"><b><?php echo Formatting::currency($total_all, 2); ?></b></td>
					</tr>
				</tfoot>
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
						$data2 = $this->m_rkpd->sumber_dana_rekap($ta, 'sumber_detil', NULL, 'AND ref1.kode_sumber_dana = '.$row1->id_sumber)->result();
						$total_all += $row1->total;
					 ?>
					 <tr style="background-color: #78cbfd; font-weight: bold;">
					 	<td><?php echo $row1->sumber_dana; ?></td>
					 	<td align="right"><?php echo Formatting::currency($row1->total, 2); ?></td>
					 </tr>
					 <?php foreach ($data2 as $key2 => $row2): ?>
					 	<tr>
					 		<td>&emsp;<?php echo $row2->nama_skpd; ?></td>
					 		<td align="right"><?php echo Formatting::currency($row2->total, 2); ?></td>
					 	</tr>
					 <?php endforeach ?>
				<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total</th>
						<td align="right"><b><?php echo Formatting::currency($total_all, 2); ?></b></td>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php endif ?>
</article>