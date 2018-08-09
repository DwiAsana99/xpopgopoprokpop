<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();
		$("#simpan").click(function(){
			var keg_dari = $("#cmb_keg").val();
			if (keg_dari == '') {
				alert('Mohon pilih Kegiatan');
			}else{
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: '<?php echo site_url("rka/do_copy_belanja_kegiatan"); ?>',
					data: {
						keg_tujuan: $("#id_keg").val(),
						keg_dari: $("#cmb_keg").val()
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
					// error: function(err) {
					// 	$.blockUI({
					// 		message: "Data belanja gagal di copy. Mohon hubungi Administrator",
					// 		css: window._css,
					// 		overlayCSS: window._ovcss
					// 	});
					// 	location.reload();
					// }
				});
			}
		});

		$(document).on("change", "#cmb_urusan", function() {
			$.ajax({
			    type: "POST",
			    url: '<?php echo site_url("renja/combo_copy_belanja"); ?>',
			    data: {id_keg: $("#id_keg").val(), kd_urusan: $(this).val()},
			    success: function(msg){
			      $("#cb_bidang").html(msg);
			      $("#cmb_prog").val(" ");
			      $("#cmb_keg").val(" ");
			      $("#cmb_prog").trigger("chosen:updated");
			      $("#cmb_keg").trigger("chosen:updated");
			      prepare_chosen();
			    }
			});
		});

		$(document).on("change", "#cmb_bidang", function() {
			$.ajax({
			    type: "POST",
			    url: '<?php echo site_url("renja/combo_copy_belanja"); ?>',
			    data: {id_keg: $("#id_keg").val(), kd_urusan: $("#cmb_urusan").val(), kd_bidang: $(this).val()},
			    success: function(msg){
			      $("#cb_prog").html(msg);
			      $("#cmb_keg").val(" ");
			      $("#cmb_keg").trigger("chosen:updated");
			      prepare_chosen();
			    }
			});
		});

		$(document).on("change", "#cmb_prog", function() {
			$.ajax({
			    type: "POST",
			    url: '<?php echo site_url("renja/combo_copy_belanja"); ?>',
			    data: {id_keg: $("#id_keg").val(), kd_urusan: $("#cmb_urusan").val(), kd_bidang: $("#cmb_bidang").val(), kd_program: $(this).val()},
			    success: function(msg){
			      $("#cb_keg").html(msg);
			      prepare_chosen();
			    }
			});
		});
	});
</script>
<?php  
	$urusan = $this->db->query("SELECT * FROM tx_rka_prog_keg 
		INNER JOIN m_urusan 
		ON tx_rka_prog_keg.kd_urusan = m_urusan.kd_urusan
		WHERE id_skpd = '$id_skpd' AND tahun = '$tahun' AND id <> $id_keg 
		GROUP BY tx_rka_prog_keg.kd_urusan")->result();
	$data_urusan = array('' => '');
	foreach ($urusan as $key => $row) {
		$data_urusan[$row->kd_urusan] = $row->kd_urusan.' - '.$row->Nm_Urusan;
	}
	$combo_urusan = form_dropdown('cmb_urusan', $data_urusan, NULL, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="cmb_urusan"');
	$combo_bidang = form_dropdown('cmb_bidang', array('' => ''), NULL, 'data-placeholder="Pilih Bidang" class="common chosen-select" id="cmb_bidang"');
	$combo_prog = form_dropdown('cmb_program', array('' => ''), NULL, 'data-placeholder="Pilih Program" class="common chosen-select" id="cmb_program"');
	$combo_keg = form_dropdown('cmb_kegiatan', array('' => ''), NULL, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="cmb_kegiatan"');


?>
<div style="width:900">
	<header>
		<h3>
			Copy Belanja Kegiatan RKA Tahun <?php echo $tahun; ?>
		</h3>

	</header>
	<div class="module_content">
		<input type="hidden" id="id_keg" value="<?php echo $id_keg; ?>">
		<table class="table-common">
			<tr>
				<td colspan="2">
					<br>Copy Belanja ke Kegiatan <i><?php echo $keg_tujuan; ?></i>
				</td>
			</tr>
			<tr>
				<th style="text-align: left;" width="20%">Urusan</th>
				<td>
					<?php echo $combo_urusan; ?>
				</td>
			</tr>
			<tr>
				<th style="text-align: left;" width="20%">Bidang</th>
				<td id="cb_bidang">
					<?php echo $combo_bidang; ?>
				</td>
			</tr>
			<tr>
				<th style="text-align: left;" width="20%">Program</th>
				<td id="cb_prog">
					<?php echo $combo_prog; ?>
				</td>
			</tr>
			<tr>
				<th style="text-align: left;" width="20%">Kegiatan</th>
				<td id="cb_keg">
					<?php echo $combo_keg; ?>
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
