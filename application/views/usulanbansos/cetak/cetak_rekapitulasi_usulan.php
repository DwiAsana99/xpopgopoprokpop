<style type="text/css">
	.custom1 table, .custom1 td, .custom1 th{
		border: 1px solid black;
		padding: 3px;
	}

	.custom1 td{
		vertical-align: top;
	}

	.custom2 {
		border-collapse: collapse;
		margin-bottom: -1px;
	}
</style>

<table width="100%" class="custom1 custom2">
	<tr>
		<th style="padding: 8px;">
			DATA REKAPITULASI USULAN <?php echo $status_ng; ?>
		</th>
	</tr>
	<tr>
		<td style="font-size: 12px; vertical-align: middle !important; padding: 8px;" align="center">
			<strong>PEMERINTAH KABUPATEN KLUNGKUNG</strong><br>
			Tahun Anggaran : <?php echo $tahun ?>
		</td>
	</tr>
</table>

<table width="100%" class="custom1 custom2">
	<thead>
		<tr>
			<th>No</th>
			<th>Pengusul</th>
			<th>Jenis Pekerjaan</th>
			<th>Nilai Usulan (Rp)</th>
			<th>SKPD</th>
			<th>Status RKPD</th>
			<th>Nominal Rekomendasi</th>
			<th>No. Rekomendasi</th>
			<th>Tgl. Rekomendasi</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($usulan as $key => $row): ?>
			<?php 
				$total_dana += $row->jumlah_dana;
				$total_rekom += $row->nominal_rekomendasi;
				$statusRKPD = "";
				if ($row->flag_masukRKPD=='0') {
					$statusRKPD = "Disetujui";
				}else {
					$statusRKPD = "Belum Ditentukan";
				}
				if ($row->pengusul=='3'){
					$pengusulbansos = $row->lainnya;
				}else{
					$pengusulbansos = $row->pengusulbansos;
				}
			 ?>
			<tr>
				<td><?php echo ($key+1).'.'; ?></td>
				<td><?php echo $pengusulbansos; ?></td>
				<td><?php echo $row->jenis_pekerjaan; ?></td>
				<td align="right"><?php echo Formatting::currency($row->jumlah_dana, 2); ?></td>
				<td><?php echo $row->nama_skpd; ?></td>
				<td><?php echo $statusRKPD; ?></td>
				<td align="right"><?php echo Formatting::currency($row->nominal_rekomendasi, 2); ?></td>
				<td><?php echo $row->norekomendasi; ?></td>
				<td><?php echo Formatting::date_format($row->tglrekomendasi, 'date'); ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="3">TOTAL</th>
			<th align="right"><?php echo Formatting::currency($total_dana, 2); ?></th>
			<th colspan="2"></th>
			<th align="right"><?php echo Formatting::currency($total_rekom, 2); ?></th>
			<th colspan="2"></th>
		</tr>
	</tfoot>
</table>