<style type="text/css">
	table, td {
		font-size: 12px;
		vertical-align: top;
		padding: 2px 5px 2px 5px; 
	},
	th {
		font-size: 12px;
		vertical-align: middle;
		padding: 2px 5px 2px 5px; 
	}
</style>
<table border="1" style="border-collapse: collapse;"  width="100%">
	<tbody>
		<tr>
			<th rowspan="2" width="10%">
				<?php echo $logo; ?>
			</th>
			<th style="font-size: 18px;">
				DAFTAR USULAN POKIR
			</th>
			<th rowspan="2" width="10%">
					<?php echo $qr['qrcode']; ?>
			</th>
		</tr>
		<tr>
			<td align="center" style="vertical-align: middle !important;">
				<strong>Pemerintah Kabupaten Klungkung</strong><br>
				Tahun Anggaran : <?php echo $ta; ?>
			</td>
		</tr>
	</tbody>
</table>
<table border="1" style="border-collapse: collapse;"  width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th>Group</th>
			<th>Kecamatan</th>
			<th>Desa</th>
			<th>Jenis Pekerjaan</th>					
			<th>Volume</th>
			<th>Jumlah Dana (Rp.)</th>
			<th>Lokasi</th>
			<th>Catatan</th>
		</tr>				
	</thead>
	<tbody>
		<?php $total_all = 0; ?>
		<?php foreach ($pokir as $key => $row): ?>
			<?php $total_all += $row->jumlah_dana; ?>
			<tr>
				<td><?php echo ($key+1).'.'; ?></td>
				<td><?php echo $row->nama_group; ?></td>
				<td><?php echo $row->nama_kec; ?></td>
				<td><?php echo $row->nama_desa; ?></td>
				<td><?php echo $row->jenis_pekerjaan; ?></td>
				<td><?php echo $row->volume; ?></td>
				<td align="right"><?php echo Formatting::currency($row->jumlah_dana,2); ?></td>
				<td><?php echo $row->lokasi; ?></td>
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