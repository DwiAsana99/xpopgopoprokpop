<style type="text/css">
	.custom1 table, .custom1 td, .custom1 th{
		border: 1px solid black;
		padding: 3px;
		vertical-align: top;
	}

	.custom2 {
		border-collapse: collapse;
		margin-bottom: -1px;
	}
</style>

<table width="100%" class="custom1 custom2">
	<tr>
		<th style="vertical-align: middle !important; padding: 8px;">
			DATA USULAN <?php echo $status_ng; ?>
		</th>
	</tr>
	<tr>
		<td style="font-size: 12px; vertical-align: middle !important; padding: 8px;" align="center">
			<strong>PEMERINTAH KABUPATEN KLUNGKUNG</strong><br>
			Tahun Anggaran : <?php echo $tahun ?><br>
			Jenis Hibah : <?php echo $jenis_hibah; ?>
		</td>
	</tr>
</table>

<table width="100%" class="custom1 custom2">
	<thead>
		<tr>
			<th>No</th>
			<th>Group</th>
			<th>Jenis Hibah</th>
			<th>SKPD Evaluator</th>
			<th>Kecamatan</th>
			<th>Desa</th>
			<th>Jenis Pekerjaan</th>
			<th>Volume</th>
			<th>Nilai Usulan (Rp)</th>
			<th>Lokasi</th>
			<th>Catatan</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($usulan as $key => $row): ?>
			<?php 
				$total_dana += $row->jumlah_dana;
				$jenis_hibah = "";
				if ($row->id_jenishibah == 1) {
					$jenis_hibah = "Uang";
				}elseif ($row->id_jenishibah == 2) {
					$jenis_hibah = "Barang";
				}elseif ($row->id_jenishibah == 3) {
					$jenis_hibah = "Jasa";
				}
			 ?>
			<tr>
				<td><?php echo ($key+1).'.'; ?></td>
				<td><?php echo $row->nama_group; ?></td>
				<td><?php echo $jenis_hibah; ?></td>
				<td><?php echo $row->nama_skpd; ?></td>
				<td><?php echo $row->nama_kec; ?></td>
				<td><?php echo $row->nama_desa; ?></td>
				<td><?php echo $row->jenis_pekerjaan; ?></td>
				<td><?php echo $row->volume; ?></td>
				<td align="right"><?php echo Formatting::currency($row->jumlah_dana, 2); ?></td>
				<td><?php echo $row->lokasi; ?></td>
				<td><?php echo $row->catatan; ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="8">TOTAL</th>
			<th align="right"><?php echo Formatting::currency($total_dana, 2); ?></th>
			<th colspan="2"></th>
		</tr>
	</tfoot>
</table>