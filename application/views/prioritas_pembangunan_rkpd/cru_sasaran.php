<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();
		$('.target').autoNumeric(numOptionsNotRound);

		$('form#sasaran').validate({
			rules: {
				id_sasaran : "required",
				indikator : "required",
				satuan : "required",
				status_indikator : "required",
				kategori_indikator : "required",
				target : "required",
			},
			ignore: ":hidden:not(select)"
		});

		$("#simpan").click(function(){
		    var valid = $("form#sasaran").valid();
		    if (valid) {
		    	element_prioritas.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

				$('.target').each(function(i){
			        var self = $(this);
			        try{
			            var v = self.autoNumeric('get');
			            self.autoNumeric('destroy');

			            self.val(v);
			        }catch(err){
			            console.log("Not an autonumeric field: " + self.attr("name"));
			        }
			    });

		    	$.ajax({
					type: "POST",
					url: $("form#sasaran").attr("action"),
					data: $("form#sasaran").serialize(),
					dataType: "json",
					success: function(msg){
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element_prioritas.trigger('click');
					}
				});
		    };
		});

		$("#tambah_indikator_sasaran").click(function(){
			key = $("#indikator_frame_sasaran").attr("key");
			key++;
			$("#indikator_frame_sasaran").attr("key", key);

			var name = "indikator["+ key +"]";
			var target = "target["+ key +"]";
			var satuan_target = "satuan["+ key +"]";
			var kode_positif_negatif = "status_indikator["+ key +"]";
			var kode_kategori_indikator = "kategori_indikator["+ key +"]";

			$("#indikator_box_sasaran textarea").attr("name", name);
			$("#indikator_box_sasaran input#target").attr("name", target);
			$("#indikator_box_sasaran input#satuan").attr("name", satuan_target);
			$("#indikator_box_sasaran select#status_indikator").attr("name", kode_positif_negatif);
			$("#indikator_box_sasaran select#kategori_indikator").attr("name", kode_kategori_indikator);

			$("#indikator_frame_sasaran").append($("#indikator_box_sasaran").html());
		});

		$(document).on("click", ".hapus_indikator_sasaran", function(){
			$(this).parent().parent().remove();
		});
	});
</script>
<div style="width: 900px">
	<header>
		<h3>
	<?php
		if (!empty($sasaran)){
		    echo "Edit Data Sasaran Pembangunan";
		} else{
		    echo "Tambah Data Sasaran Pembangunan";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('prioritas_pembangunan_rkpd/save_sasaran');?>" method="POST" name="sasaran" id="sasaran" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id" value="<?php if(!empty($sasaran->id)){echo $sasaran->id;} ?>" />
			<input type="hidden" name="id_rkpd_prioritas" value="<?php if(!empty($prioritas->id)){echo $prioritas->id;} ?>" />
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Prioritas Pembangunan</strong></td>
					<td width="80%"><?php echo $prioritas->id_prioritas; ?></td>
				</tr>
				<tr>
					<td width="20%"><strong>Sasaran Pembangunan</strong></td>
					<td width="80%">
						<textarea class="common" name="sasaran"><?php if(!empty($sasaran->sasaran)){echo $sasaran->sasaran;} ?></textarea>
					</td>
				</tr>
				<tr>
					<td><strong>Indikator Kinerja <a id="tambah_indikator_sasaran" class="icon-plus-sign" href="javascript:void(0)"></a></strong></td>
					<td id="indikator_frame_sasaran" key="<?php echo (!empty($indikator_sasaran))?$indikator_sasaran->num_rows():'1'; ?>">
						<?php
							if (!empty($indikator_sasaran->result())) {
								$i=0;
								foreach ($indikator_sasaran->result() as $row) {
									$i++;
						?>
						<input type="hidden" name="id_indikator_sasaran[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
						<div style="width: 100%; margin-top: 10px;">
							<div style="width: 100%;">
								<textarea style="width:95%" class="common indikator_val" name="indikator[<?php echo $i; ?>]"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
						<?php
							if ($i != 1) {
						?>
							<a class="icon-remove hapus_indikator_sasaran" href="javascript:void(0)" style="vertical-align: top;"></a>
						<?php
							}
						?>
							</div>
							<div style="width: 100%;">
								<table class="table-common" width="100%">
									<tr>
										<td>Satuan</td>
										<td><input class="common indikator_val" name="satuan[<?php echo $i; ?>]" id="satuan" value="<?php echo $row->satuan_target; ?>"></td>
									</tr>
									<tr>
										<td rowspan="2">Kategori Indikator</td>
										<td><?php echo form_dropdown('status_indikator['. $i .']', $status_indikator, $row->status_indikator, 'class="common indikator_val" id="status_indikator"'); ?></td>
									</tr>
									<tr>
										<td><?php echo form_dropdown('kategori_indikator['. $i .']', $kategori_indikator, $row->kategori_indikator, 'class="common indikator_val" id="kategori_indikator"'); ?></td>
									</tr>
									<tr>
										<td>Target</td>
										<td><input style="width: 100%;" type="text" class="target" name="target[<?php echo $i; ?>]" value="<?php echo (!empty($row->target))?$row->target:''; ?>"></td>
									</tr>
								</table>
							</div>
						</div>
						<?php
								}
							}else{
						?>
						<div style="width: 100%; margin-top: 10px;">
							<div style="width: 100%;">
								<textarea style="width:95%" class="common indikator_val" name="indikator[1]"></textarea>
							</div>
							<div style="width: 100%;">
								<table class="table-common" width="100%">
									<tr>
										<td>Satuan</td>
										<td><input class="common indikator_val" name="satuan[1]" id="satuan"></td>
									</tr>
									<tr>
										<td rowspan="2">Kategori Indikator</td>
										<td><?php echo form_dropdown('status_indikator[1]', $status_indikator, '', 'class="common indikator_val" id="status_indikator"'); ?></td>
									</tr>
									<tr>
										<td><?php echo form_dropdown('kategori_indikator[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_indikator"'); ?></td>
									</tr>
									<tr>
										<td>Target</td>
										<td><input style="width: 100%;" type="text" class="target" id="target[1]" name="target[1]"></td>
									</tr>	
								</table>
							</div>
						</div>
						<?php
							}
						?>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<footer>
		<div class="submit_link">
  			<input id="simpan" type="button" value="Simpan">
		</div>
	</footer>
</div>
<div style="display: none" id="indikator_box_sasaran">
	<div style="width: 100%; margin-top: 15px;">
		<hr>
		<div style="width: 100%;">
			<textarea class="common indikator_val" name="indikator[1]" style="width:95%"></textarea>
			<a class="icon-remove hapus_indikator_sasaran" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;">
			<table class="table-common" width="100%">
				<tr>
					<td>Satuan</td>
					<td><input class="common indikator_val" name="satuan[1]" id="satuan"></td>
				</tr>
				<tr>
					<td rowspan="2">Kategori Indikator</td>
					<td><?php echo form_dropdown('status_indikator[1]', $status_indikator, '', 'class="common indikator_val" id="status_indikator"'); ?></td>
				</tr>
				<tr>
					<td><?php echo form_dropdown('kategori_indikator[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_indikator"'); ?></td>
				</tr>
				<tr>
					<td>Target</td>
					<td><input style="width: 100%;" type="text" class="target" id="target" name="target[1]"></td>
				</tr>
			</table>
		</div>
	</div>
</div>