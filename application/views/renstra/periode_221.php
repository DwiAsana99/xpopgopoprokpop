<script type="text/javascript">

	$(document).ready(function(){


		$("#cetak-kegiatanPilih").click(function(){

			var ta = $('#tahun_anggaran').val();
			var idK = $("#idK").val();
			var status = $("#status").val();
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 1000,
				overlayCSS: window._ovcss
			});

			var link = "<?php echo site_url('renstra/cetak_kegiatan');?>/" + ta + "/" + idK + "/" + status;
			// $(location).attr('href',link);
			window.open(link);
		});

		$("#preview-kegiatanPilih").click(function() {
			var ta = $('#tahun_anggaran').val();
			var idK = $("#idK").val();
			var status = $("#status").val();
			$.blockUI({
				message: 'Preview dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 1000,
				overlayCSS: window._ovcss
			});

			var link = "<?php echo site_url('renstra/cetak_kegiatan');?>/" + ta + "/" + idK + "/" + status;
			window.open(link);
		});
	});
</script>


<div style="width: 800px;">
<table class="fcari" width="800 px" >

	<th > Pilih Tahun Anggaran...</th>


</table>
<?php
          $user = $this->auth->get_user();
          $t_anggaran = $this->m_settings->get_tahun_anggaran_db();
        ?>

			    <input type="hidden" name="id_keg" id="idK" value="<?php echo $id_keg; ?>">
				<input type="hidden" name="status" id="status" value="<?php echo $status ?>">
         		<div class="form-group">
              <select class="form-control"  id="tahun_anggaran">
								<?php
	                    foreach ($t_anggaran as $row) {
	              ?>
									<option id="ta" value=<?php echo $row->tahun_anggaran; ?> ><?php echo $row->tahun_anggaran; ?></option>
	              <?php
	              }
	              ?>
            	</select>
              </div>

              <div class="submit_link">
			  	<?php if($status === 'cetak') {?>
					<input id="cetak-kegiatanPilih" type="button" value="Cetak">
				<?php } else { ?>
					<input type="button" id="preview-kegiatanPilih" value="Preview">
				<?php } ?>
				</div>
              </div>
