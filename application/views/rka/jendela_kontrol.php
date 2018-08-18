<style type="text/css">
	.r-ranwal{
		background-color: #D0BE2D;
		padding-left: 5px;
	}

	.r-akhir{
		background-color: #96CC3F;
		padding-left: 5px;
	}
</style>
<header>
	<h3>
	 
  <?php echo "Jendela Kontrol RKA ".$nama_skpd; ?></h3>
</header>
<div class="module_content"> 		
	<table class="fcari" width="100%">
		<tbody>
			<tr>
				<td align="center" colspan="6">Proses</td>					
			</tr>
			<tr align="center">					
				<td colspan="3" class="r-ranwal">
					Rencana Kerja & Anggaran</td>
			</tr>
			<tr>
				<td width="25%" class="r-ranwal">Program & Kegiatan Baru</td>
				<td colspan="2" width="25" class="r-ranwal"><?php echo $jendela_kontrol->baru; ?>
                Data</td>
			</tr>
			<tr>
			    <td class="r-ranwal">Program &amp; Kegiatan Diproses</td>
			    <td colspan="2" class="r-ranwal"><?php echo $jendela_kontrol->proses; ?>
			    Data</td>
		  </tr>
		</tbody>
	</table>
	<!--<table style="font-style: italic; color: #666;">
		<tr>
			<td colspan="2">*Keterangan:</td>				
		</tr>
		<tr>
			<td valign="top">1. </td>
			<td>bla bla</td>
		</tr>
		<tr>
			<td valign="top">2. </td>
			<td>bla bla</td>
		</tr>
		<tr>
			<td valign="top">2. </td>
			<td>bla bla.</td>
		</tr>		
	</table>	-->	
</div> 	
<footer>
	<div class="submit_link">
    <?php if (!$rka) {?>
    	<input type="button" class="button-action" id="get_renstra" value="Ambil Data PPAS" />
    <?php }
		else {
	?>
    	<input type="button" class="button-action" id="export_simda" value="Kirim Data Ke Simda" />
    	<a href="<?php echo site_url('rka/preview_rka'); ?>"><input type="button" value="Lihat RKA" /></a>
		<a href="<?php echo site_url('rka/rekap_sumber_dana'); ?>"><input type="button" value="Rekap Sumber Dana" /></a>
    <?php } ?>
	  	<!--<input type="button" class="button-action" id="cetak" value="Cetak" />-->
	 	<input type="button" value="Back" onclick="history.go(-1)" />
	</div> 
</footer>

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', '#export_simda', function() {
			$.ajax({
			    type: 'POST',
			    url: '<?php echo site_url("rka/export_to_simda"); ?>',
			    dataType: 'html',
			    beforeSend: function() {
					$.blockUI({
						message: '<center><i class="fa fa-refresh fa-spin fa-lg"></i></center><br>Proses kirim data sedang berjalan, mohon menunggu hingga proses selesai...<br>Proses ini akan memerlukan waktu yang cukup lama...',
						css: window._css,
						overlayCSS: window._ovcss
					});
				},
			    success: function(data){
					console.log(data);
					$.blockUI({
						message: data.msg,
						timeout: 2500,
						css: window._css,
						overlayCSS: window._ovcss
					});
			    },
			    error: function(data) {
			    	console.log(data);
					$.blockUI({
						message: 'Data gagal disingkron... Harap hubungi administrator',
						timeout: 2500,
						css: window._css,
						overlayCSS: window._ovcss
					});
			    }
			});
		});
	})
</script>