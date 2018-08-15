<script type="text/javascript">

	$(document).ready(function(){


		$("#cetak-kegiatanPilih").click(function(){

			var is_tahun = $('#tahun_anggaran').val();
			var ta = $('#tahun_anggaran').find(":selected").text();
			var idK = $("#idK").val();
			var status = $('#status').val();
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = "<?php echo site_url("rka/cetak_kegiatan"); ?>/" + ta + "/" + is_tahun + "/" + idK + "/" + status;
			// $(location).attr('href',link);
			window.open(link);
		});

		$("#preview-kegiatanPilih").click(function() {
			var is_tahun = $('#tahun_anggaran').val();
			var ta = $('#tahun_anggaran').find(":selected").text();
			var idK = $("#idK").val();
			var status = $('#status').val();
			$.blockUI({
				message: 'Preview dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = "<?php echo site_url("rka/cetak_kegiatan"); ?>/" + ta + "/" + is_tahun + "/" + idK + "/" + status;
			// $(location).attr('href',link);
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
					$ta 		= $this->m_settings->get_tahun_anggaran();
        ?>
				<input type="hidden" name="status" id="status" value="<?php echo $status ?>">
						<input type="hidden" name="idK" id="idK" value="<?php echo $id_keg; ?>">
         		<div class="form-group">
							<select class="form-control"  id="tahun_anggaran">
								<option id="ta" value="1" ><?php echo $ta + 0; ?></option>
								<option id="ta" value="0" ><?php echo $ta + 1; ?></option>
              </select>
            </div>

              <div class="submit_link">
			  <?php if($status === 'cetak') { ?>
				<input id="cetak-kegiatanPilih" type="button" value="Cetak">
			  <?php } else { ?>
			  	<input type="button" value="Preview" id="preview-kegiatanPilih">
			  <?php } ?>
						    </div>
              </div>
