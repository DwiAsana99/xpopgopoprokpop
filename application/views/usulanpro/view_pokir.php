<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Usulan</h3>
	</header>
	<div style='float:right'>
		<a href="<?php echo base_url('usulanpro/cetak_usulan_pokir'); ?>" style="margin: 3px 10px 0px 0px; float: right;">
			<button type="button">Cetak</button>
		</a>
    </div>
	<div class="module_content"; style="overflow:auto">
		<table id="renstra" class="table-common tablesorter" style="width:150%">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode</th>
					<th>Nama Program | Nama Kegiatan</th>
					<th>Group</th>
					<th>Kecamatan</th>
					<th>Desa</th>
					<th>Jenis Pekerjaan</th>					
					<th>Volume</th>
					<th>Jumlah Dana (Rp.)</th>
					<th>Lokasi</th>
					<th>Status</th>
					<th>Catatan</th>
				</tr>				
			</thead>
			<tbody>
				<?php $total_all = 0; ?>
				<?php foreach ($pokir as $key => $row): ?>
					<?php $total_all += $row->jumlah_dana; ?>
					<tr>
						<td><?php echo ($key+1).'.'; ?></td>
						<td><?php echo $row->kd_urusan.'.'.$row->kd_bidang.'.'.$row->kd_program.'.'.$row->kd_kegiatan; ?></td>
						<td><?php echo ''; ?></td>
						<td><?php echo $row->nama_group; ?></td>
						<td><?php echo $row->nama_kec; ?></td>
						<td><?php echo $row->nama_desa; ?></td>
						<td><?php echo $row->jenis_pekerjaan; ?></td>
						<td><?php echo $row->volume; ?></td>
						<td align="right"><?php echo Formatting::currency($row->jumlah_dana,2); ?></td>
						<td><?php echo $row->lokasi; ?></td>
						<td><?php echo $row->nama_keputusan; ?></td>
						<td><?php echo $row->catatan; ?></td>
					</tr>
				<?php endforeach ?>
				<tr>
					<td colspan="6" align="center"><strong>TOTAL DANA (Rp.)</strong></td>
					<td align="right"><?php echo Formatting::currency($total_all,2); ?></td>
					<td colspan="2">&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</div>
</article>