<?php 
	$tahun_1 = 0;
	$tahun_2 = 0;
?>

<?php if (!$cetak): ?>
	<article class="module width_full">
		<header>
		  <h3>Rekap Sumber Dana DPA</h3>
		</header>
		<div class="module_content"; style="overflow:auto">
		  	<button class="pull-right" style="margin-bottom: 15px !important;" id="cetak">CETAK</button>
			<table class="table-common" style="width:100%">
				<thead>
					<tr>
						<th>NAMA SUMBER DANA</th>
						<th>TAHUN <?php echo $tahun; ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($data1 as $key1 => $row1): ?>
					<?php 
						$tahun_1 += $row1->tahun1;
					 ?>
					 <tr>
					 	<td><?php echo $row1->sumber_dana; ?></td>
					 	<td align="right"><?php echo Formatting::currency($row1->tahun1, 2); ?></td>
					 </tr>
				<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total</th>
						<td align="right"><b><?php echo Formatting::currency($tahun_1, 2); ?></b></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</article>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#cetak').on('click', function() {
				window.location = '<?php echo site_url("dpa/rekap_sumber_dana/1"); ?>';
			})
		})
	</script>
<!-- FOR PRINT -->
<?php else: ?>
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
					REKAP SUMBER DANA DPA
					<p style="font-size: 14px">
						<?php echo $this->session->userdata('nama_skpd'); ?>
					</p>
				</th>
				<th rowspan="2" width="10%">
					<?php echo $qr['qrcode']; ?>
				</th>
			</tr>
			<tr>
				<td align="center" style="vertical-align: middle !important;">
					<strong>Pemerintah Kabupaten Klungkung</strong>
				</td>
			</tr>
		</tbody>
	</table>
	<table border="1" style="border-collapse: collapse;"  width="100%">
		<thead>
			<tr style="background-color: #dcdcdc;">
				<th>NAMA SUMBER DANA</th>
				<th>TAHUN <?php echo $tahun; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data1 as $key1 => $row1): ?>
			<?php 
				$tahun_1 += $row1->tahun1;
			 ?>
			 <tr>
			 	<td><?php echo $row1->sumber_dana; ?></td>
			 	<td align="right"><?php echo Formatting::currency($row1->tahun1, 2); ?></td>
			 </tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<th>Total</th>
				<td align="right"><b><?php echo Formatting::currency($tahun_1, 2); ?></b></td>
			</tr>
		</tfoot>
	</table>
<?php endif ?>