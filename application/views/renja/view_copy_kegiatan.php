<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();
		$("#simpan").click(function(){
			var keg_tujuan = $("#keg_tujuan").val();
			if (keg_tujuan == '') {
				alert('Mohon pilih Kegiatan tujuan');
			}else{
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: '<?php echo site_url("renja/do_copy_belanja_kegiatan"); ?>',
					data: {
						id: $("#id_keg").val(),
						keg_tujuan: $("#keg_tujuan").val()
					},
					dataType: "json",
					success: function(msg){
						$.blockUI({
							message: msg,
							css: window._css,
							overlayCSS: window._ovcss
						});
						location.reload();
					},
					error: function(err) {
						$.blockUI({
							message: "Data belanja gagal di copy. Mohon hubungi Administrator",
							css: window._css,
							overlayCSS: window._ovcss
						});
						location.reload();
					}
				});
			}
		});
	});
</script>
<div style="width:900">
	<header>
		<h3>
			Copy Belanja Kegiatan Tahun <?php echo $tahun; ?>
			
		</h3>

	</header>
	<div class="module_content">
		<input type="hidden" id="id_keg" value="<?php echo $id_keg; ?>">
		<table class="table-common">
			<tr>
				<td colspan="2">
					<br><?php echo $keg_lama; ?>
				</td>
			</tr>
			<tr>
				<th width="20%">Kegiatan yang dituju</th>
				<td>
					<?php echo $keg_tujuan; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input id="simpan" type="button" value="Simpan">
				</td>
			</tr>
		</table>
	</div>
</div>
