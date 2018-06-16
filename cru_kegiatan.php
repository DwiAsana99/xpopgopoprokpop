<?php
	if (!empty($revisi_rpjmd)) {
		$nominal_1_pro = 0;
		$nominal_2_pro = 0;
		$nominal_3_pro = 0;
		$nominal_4_pro = 0;
		$nominal_5_pro = 0;

		if (!empty($nominal_banding->nominal_1_pro)) {
			$nominal_1_pro = $nominal_banding->nominal_1_pro;
		}
		if (!empty($nominal_banding->nominal_2_pro)) {
			$nominal_2_pro = $nominal_banding->nominal_2_pro;
		}
		if (!empty($nominal_banding->nominal_3_pro)) {
			$nominal_3_pro = $nominal_banding->nominal_3_pro;
		}
		if (!empty($nominal_banding->nominal_4_pro)) {
			$nominal_4_pro = $nominal_banding->nominal_4_pro;
		}
		if (!empty($nominal_banding->nominal_5_pro)) {
			$nominal_5_pro = $nominal_banding->nominal_5_pro;
		}

		$sisa1 = $revisi_rpjmd->nominal_1 - $nominal_1_pro;
		$sisa2 = $revisi_rpjmd->nominal_2 - $nominal_2_pro;
		$sisa3 = $revisi_rpjmd->nominal_3 - $nominal_3_pro;
		$sisa4 = $revisi_rpjmd->nominal_4 - $nominal_4_pro;
		$sisa5 = $revisi_rpjmd->nominal_5 - $nominal_5_pro;
	}
?>
<script type="text/javascript">
	prepare_chosen();
	$('input[name=nominal_1]').autoNumeric(numOptionsNotRound);
	$('input[name=nominal_2]').autoNumeric(numOptionsNotRound);
	$('input[name=nominal_3]').autoNumeric(numOptionsNotRound);
	$('input[name=nominal_4]').autoNumeric(numOptionsNotRound);
	$('input[name=nominal_5]').autoNumeric(numOptionsNotRound);

	$(document).on("change", "#kd_kegiatan", function () {
		var str = $(this).find('option:selected').text();
		var nm_kegiatan = str.substring(str.indexOf(".")+2);
		$("#nama_prog_or_keg").val(nm_kegiatan);
	});

	$.validator.addMethod('maxNominal',
		function(value, element, params) {
			try {
				value 		= parseFloat($(element).autoNumeric('get'));
				var nil1	= parseFloat(params);
			} catch(e) { alert(e) }

			return this.optional(element) || ( value >0 && value <= nil1);
		}, "Mohon masukan nilai yang agar program memiliki nilai yang sama dengan batas yang disetujui (nominal RPJMD), nominal tersisa yang dapat digunakan yaitu {0}."
	);

	$('form#kegiatan').validate({
		rules: {
			kd_kegiatan : "required",
			indikator_kinerja : "required",
			nominal_1 : {
				required : true,
			<?php
				if (!empty($sisa1)) {
			?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa1>0)?"'". $sisa1 ."'":"'0'"; ?>)
				}
			<?php
				};
			?>
			},
			nominal_2 : {
				required : true,
			<?php
				if (!empty($sisa2)) {
			?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa2>0)?"'". $sisa2 ."'":"'0'"; ?>)
				}
			<?php
				};
			?>
			},
			nominal_3 : {
				required : true,
			<?php
				if (!empty($sisa3)) {
			?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa3>0)?"'". $sisa3 ."'":"'0'"; ?>)
				}
			<?php
				};
			?>
			},
			nominal_4 : {
				required : true,
			<?php
				if (!empty($sisa4)) {
			?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa4>0)?"'". $sisa4 ."'":"'0'"; ?>)
				}
			<?php
				};
			?>
			},
			nominal_5 : {
				required : true,
			<?php
				if (!empty($sisa5)) {
			?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa5>0)?"'". $sisa5 ."'":"'0'"; ?>)
				}
			<?php
				};
			?>
			},
			uraian_kegiatan_1 : {
				required : true
			},
			uraian_kegiatan_2 : {
				required : true
			},
			uraian_kegiatan_3 : {
				required : true
			},
			uraian_kegiatan_4 : {
				required : true
			},
			uraian_kegiatan_5 : {
				required : true
			}
		},
		ignore: ":hidden:not(select)"
	});

	$("#simpan").click(function(){
		$('#indikator_frame_kegiatan .indikator_val').each(function () {
		    $(this).rules('add', {
		        required: true
		    });
		});

		$('#indikator_frame_kegiatan .target').each(function () {
		    $(this).rules('add', {
		        required:true,
				number:true
		    });
		});

	    var valid = $("form#kegiatan").valid();
	    if (valid) {
	    	$('input[name=nominal_1]').val($('input[name=nominal_1]').autoNumeric('get'));
			$('input[name=nominal_2]').val($('input[name=nominal_2]').autoNumeric('get'));
			$('input[name=nominal_3]').val($('input[name=nominal_3]').autoNumeric('get'));
			$('input[name=nominal_4]').val($('input[name=nominal_4]').autoNumeric('get'));
			$('input[name=nominal_5]').val($('input[name=nominal_5]').autoNumeric('get'));

			element_program.parent().next().hide();
	    	$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: $("form#kegiatan").attr("action"),
				data: $("form#kegiatan").serialize(),
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element_program.trigger( "click" );
						reload_jendela_kontrol();
					};
				}
			});
	    };
	});

	$("#tambah_indikator_kegiatan").click(function(){
		key = $("#indikator_frame_kegiatan").attr("key");
		key++;
		$("#indikator_frame_kegiatan").attr("key", key);

		var name = "indikator_kinerja["+ key +"]";
		var target_aw = "kondisi_awal["+ key +"]";
		var target_1 = "target_1["+ key +"]";
		var target_2 = "target_2["+ key +"]";
		var target_3 = "target_3["+ key +"]";
		var target_4 = "target_4["+ key +"]";
		var target_5 = "target_5["+ key +"]";
		var target_ah = "target_kondisi_akhir["+ key +"]";
		var satuan_target = "satuan_target["+ key +"]";

		$("#indikator_box_kegiatan textarea").attr("name", name);
		$("#indikator_box_kegiatan input#target_aw").attr("name", target_aw);
		$("#indikator_box_kegiatan input#target_1").attr("name", target_1);
		$("#indikator_box_kegiatan input#target_2").attr("name", target_2);
		$("#indikator_box_kegiatan input#target_3").attr("name", target_3);
		$("#indikator_box_kegiatan input#target_4").attr("name", target_4);
		$("#indikator_box_kegiatan input#target_5").attr("name", target_5);
		$("#indikator_box_kegiatan input#target_ah").attr("name", target_ah);
		$("#indikator_box_kegiatan select#satuan_target").attr("name", satuan_target);
		$("#indikator_frame_kegiatan").append($("#indikator_box_kegiatan").html());
	});

	$(document).on("click", ".hapus_indikator_kegiatan", function(){
		$(this).parent().parent().remove();
	});
</script>

<div style="width: 1020px">
	<header>
		<h3>
	<?php
		if (!empty($kegiatan)){
		    echo "Edit Data Kegiatan";
		} else{
		    echo "Input Data Kegiatan";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('renstra/save_kegiatan');?>" method="POST" name="kegiatan" id="kegiatan" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_kegiatan" value="<?php if(!empty($kegiatan->id)){echo $kegiatan->id;} ?>" />
			<input type="hidden" name="id_renstra" value="<?php echo $id_renstra; ?>" />
			<input type="hidden" name="id_sasaran" value="<?php echo $id_sasaran; ?>" />
			<input type="hidden" name="id_program" value="<?php echo $id_program; ?>" />

			<input type="hidden" name="kd_urusan" value="<?php echo $tujuan_sasaran_n_program->kd_urusan; ?>" />
			<input type="hidden" name="kd_bidang" value="<?php echo $tujuan_sasaran_n_program->kd_bidang; ?>" />
			<input type="hidden" name="kd_program" value="<?php echo $tujuan_sasaran_n_program->kd_program; ?>" />
			<input type="hidden" id="nama_prog_or_keg" name="nama_prog_or_keg" value="<?php echo (!empty($kegiatan->nama_prog_or_keg))?$kegiatan->nama_prog_or_keg:''; ?>" />
		<?php
			if (!empty($revisi_rpjmd)) {
		?>
			<div style="margin-bottom: 10px;">
				<table class="table-common" width="99%">
					<tbody>
						<tr>
							<th colspan="5">Revisi dari RPJMD</th>
						</tr>
						<tr>
							<td colspan="1">Kode</td>
							<td colspan="4"><?php echo $revisi_rpjmd->kd_urusan.".".$revisi_rpjmd->kd_bidang.".".$revisi_rpjmd->kd_program; ?></td>
						</tr>
						<tr>
							<td colspan="1">Nama Program</td>
							<td colspan="4"><?php echo $revisi_rpjmd->nama_prog_or_keg; ?></td>
						</tr>
						<tr>
							<th width="20%">Nominal 1</td>
							<th width="20%">Nominal 2</th>
							<th width="20%">Nominal 3</th>
							<th width="20%">Nominal 4</th>
							<th width="20%">Nominal 5</th>
						</tr>
						<tr>
							<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_1); ?></td>
							<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_2); ?></td>
							<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_3); ?></td>
							<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_4); ?></td>
							<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_5); ?></td>
						</tr>
						<tr>
							<th width="20%">Keterangan 1</td>
							<th width="20%">Keterangan 2</th>
							<th width="20%">Keterangan 3</th>
							<th width="20%">Keterangan 4</th>
							<th width="20%">Keterangan 5</th>
						</tr>
						<tr>
							<td><?php echo $revisi_rpjmd->ket_revisi_1; ?></td>
							<td><?php echo $revisi_rpjmd->ket_revisi_2; ?></td>
							<td><?php echo $revisi_rpjmd->ket_revisi_3; ?></td>
							<td><?php echo $revisi_rpjmd->ket_revisi_4; ?></td>
							<td><?php echo $revisi_rpjmd->ket_revisi_5; ?></td>
						</tr>
						<tr>
							<th width="20%">Sisa 1</td>
							<th width="20%">Sisa 2</th>
							<th width="20%">Sisa 3</th>
							<th width="20%">Sisa 4</th>
							<th width="20%">Sisa 5</th>
						</tr>
						<tr>
							<td align="right" <?php echo ($sisa1<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa1); ?></td>
							<td align="right" <?php echo ($sisa2<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa2); ?></td>
							<td align="right" <?php echo ($sisa3<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa3); ?></td>
							<td align="right" <?php echo ($sisa4<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa4); ?></td>
							<td align="right" <?php echo ($sisa5<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa5); ?></td>
						</tr>
					</tbody>
				</table>
				<i>*Jumlah nominal semua kegiatan dalam 1 program tidak boleh melebihi jumlah yang terdapat pada baris nominal tabel diatas.</i>
				<hr>
			</div>
		<?php
			}
		?>

			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="20%">SKPD</td>
						<td width="80%"><?php echo $skpd->nama_skpd; ?></td>
					</tr>
					<tr>
						<td>Tujuan</td>
						<td><?php echo $tujuan_sasaran_n_program->tujuan; ?></td>
					</tr>
					<tr>
						<td>Sasaran</td>
						<td><?php echo $tujuan_sasaran_n_program->sasaran; ?></td>
					</tr>
					<tr>
						<td>Kode & Nama Program</td>
						<td><?php echo $tujuan_sasaran_n_program->kd_urusan.". ".$tujuan_sasaran_n_program->kd_bidang.". ".$tujuan_sasaran_n_program->kd_program." - ".$tujuan_sasaran_n_program->nama_prog_or_keg; ?></td>
					</tr>
					<tr>
						<td>Kegiatan</td>
						<td>
							<?php echo $kd_kegiatan; ?>
		    			</td>
					</tr>
					<tr>
						<td>Indikator Kinerja <a id="tambah_indikator_kegiatan" class="icon-plus-sign" href="javascript:void(0)"></a></td>
						<td id="indikator_frame_kegiatan" key="<?php echo (!empty($indikator_kegiatan))?$indikator_kegiatan->num_rows():'1'; ?>">
							<?php
								if (!empty($indikator_kegiatan)) {
									$i=0;
									foreach ($indikator_kegiatan->result() as $row) {
										$i++;
							?>
							<input type="hidden" name="id_indikator_kegiatan[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea style="width:95%" class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
							<?php
								if ($i != 1) {
							?>
								<a class="icon-remove hapus_indikator_kegiatan" href="javascript:void(0)" style="vertical-align: top;"></a>
							<?php
								}
							?>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr>
											<td colspan="2">Satuan</td>
											<td colspan="5"><?php echo form_dropdown('satuan_target['. $i .']', $satuan, $row->satuan_target, 'class="common indikator_val" id="satuan_target"'); ?></td>
										</tr>
										<tr>
											<th>Kondisi Awal</th>
											<th>Target 1</th>
											<th>Target 2</th>
											<th>Target 3</th>
											<th>Target 4</th>
											<th>Target 5</th>
											<th>Kondisi Akhir</th>
										</tr>
										<tr>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="kondisi_awal[<?php echo $i; ?>]" value="<?php echo (!empty($row->kondisi_awal))?$row->kondisi_awal:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_1[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_1))?$row->target_1:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_2[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_2))?$row->target_2:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_3[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_3))?$row->target_3:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_4[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_4))?$row->target_4:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_5[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_5))?$row->target_5:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_kondisi_akhir[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_kondisi_akhir))?$row->target_kondisi_akhir:''; ?>"></td>
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
									<textarea style="width:95%" class="common indikator_val" name="indikator_kinerja[1]"></textarea>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr>
											<td colspan="2">Satuan</td>
											<td colspan="5"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td>
										</tr>
										<tr>
											<th>Kondisi Awal</th>
											<th>Target 1</th>
											<th>Target 2</th>
											<th>Target 3</th>
											<th>Target 4</th>
											<th>Target 5</th>
											<th>Kondisi Akhir</th>
										</tr>
										<tr>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="kondisi_awal[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_1[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_2[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_3[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_4[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_5[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_kondisi_akhir[1]"></td>
										</tr>
									</table>
								</div>
							</div>
							<?php
								}
							?>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td></td>
						<td><hr class="no-padding no-margin"></td>
					</tr>
					<!-- // Tambahkan disini belanja kegiatan -->
					<?php
						//include_once "createBelanja.php";
					 ?>

</table>
<table>
		<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#Beranda" data-toggle="tab">Beranda</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
              <li><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
               <div class="tab-pane" id="Beranda">

              		<tr>
						<td>&nbsp;&nbsp;Nominal Tahun 1 (Rp.)</td>
						<td><input type="text" name="nominal_1" value="<?php if(!empty($kegiatan->nominal_1)){echo $kegiatan->nominal_1;} ?>"/></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Lokasi Tahun 1</td>
						<td>
							<textarea class="common" name="lokasi_1"><?php echo (!empty($kegiatan->lokasi_1))?$kegiatan->lokasi_1:''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Uraian Kegiatan Tahun 1</td>
						<td>
							<textarea class="common" name="uraian_kegiatan_1"><?php echo (!empty($kegiatan->uraian_kegiatan_1))?$kegiatan->uraian_kegiatan_1:''; ?></textarea>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td></td>
						<td><hr class="no-padding no-margin"></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal Tahun 2 (Rp.)</td>
						<td><input type="text" name="nominal_2" value="<?php if(!empty($kegiatan->nominal_2)){echo $kegiatan->nominal_2;} ?>"/></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Lokasi Tahun 2</td>
						<td>
							<textarea class="common" name="lokasi_2"><?php echo (!empty($kegiatan->lokasi_2))?$kegiatan->lokasi_2:''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Uraian Kegiatan Tahun 2</td>
						<td>
							<textarea class="common" name="uraian_kegiatan_2"><?php echo (!empty($kegiatan->uraian_kegiatan_2))?$kegiatan->uraian_kegiatan_2:''; ?></textarea>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td></td>
						<td><hr class="no-padding no-margin"></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal Tahun 3 (Rp.)</td>
						<td><input type="text" name="nominal_3" value="<?php if(!empty($kegiatan->nominal_3)){echo $kegiatan->nominal_3;} ?>"/></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Lokasi Tahun 3</td>
						<td>
							<textarea class="common" name="lokasi_3"><?php echo (!empty($kegiatan->lokasi_3))?$kegiatan->lokasi_3:''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Uraian Kegiatan Tahun 3</td>
						<td>
							<textarea class="common" name="uraian_kegiatan_3"><?php echo (!empty($kegiatan->uraian_kegiatan_3))?$kegiatan->uraian_kegiatan_3:''; ?></textarea>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td></td>
						<td><hr class="no-padding no-margin"></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal Tahun 4 (Rp.)</td>
						<td><input type="text" name="nominal_4" value="<?php if(!empty($kegiatan->nominal_4)){echo $kegiatan->nominal_4;} ?>"/></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Lokasi Tahun 4</td>
						<td>
							<textarea class="common" name="lokasi_4"><?php echo (!empty($kegiatan->lokasi_4))?$kegiatan->lokasi_4:''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Uraian Kegiatan Tahun 4</td>
						<td>
							<textarea class="common" name="uraian_kegiatan_4"><?php echo (!empty($kegiatan->uraian_kegiatan_4))?$kegiatan->uraian_kegiatan_4:''; ?></textarea>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td></td>
						<td><hr class="no-padding no-margin"></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal Tahun 5 (Rp.)</td>
						<td><input type="text" name="nominal_5" value="<?php if(!empty($kegiatan->nominal_5)){echo $kegiatan->nominal_5;} ?>"/></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Lokasi Tahun 5</td>
						<td>
							<textarea class="common" name="lokasi_5"><?php echo (!empty($kegiatan->lokasi_5))?$kegiatan->lokasi_5:''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Uraian Kegiatan Tahun 5</td>
						<td>
							<textarea class="common" name="uraian_kegiatan_5"><?php echo (!empty($kegiatan->uraian_kegiatan_5))?$kegiatan->uraian_kegiatan_5:''; ?></textarea>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td></td>
						<td><hr class="no-padding no-margin"></td>
					</tr>
					<tr>
						<td>Penanggung Jawab</td>
						<td><input class="common" name="penanggung_jawab" value="<?php echo (!empty($kegiatan->penanggung_jawab))?$kegiatan->penanggung_jawab:''; ?>"></td>
					</tr>
					<tr>
						<td>Lokasin</td>
						<td><input class="common" name="lokasi" value="<?php echo (!empty($kegiatan->lokasi))?$kegiatan->lokasi:''; ?>"></td>
					</tr>
				</tbody>
			</table>
			</div>  <!-- /Beranda -->
              </div>
              <!-- /.tab-pane -->
                            <div class="active tab-pane" id="activity">
               <div class="tab-pane" id="Beranda">
		<?php include_once "createBelanja.php";  ?>
              	
				</tbody>
			</table>
			</div>  <!-- /Beranda -->
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
          </div>
          </div>
          </div>



		</form>
	</div>
	<footer>
		<div class="submit_link">
  			<input id="simpan" type="button" value="Simpan">
		</div>
	</footer>
</div>
<div style="display: none" id="indikator_box_kegiatan">
	<div style="width: 100%; margin-top: 15px;">
		<hr>
		<div style="width: 100%;">
			<textarea class="common indikator_val" name="indikator_kinerja[]" style="width:95%"></textarea>
			<a class="icon-remove hapus_indikator_kegiatan" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;">
			<table class="table-common" width="100%">
				<tr>
					<td colspan="2">Satuan</td>
					<td colspan="5"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td>
				</tr>
				<tr>
					<th>Kondisi Awal</th>
					<th>Target 1</th>
					<th>Target 2</th>
					<th>Target 3</th>
					<th>Target 4</th>
					<th>Target 5</th>
					<th>Kondisi Akhir</th>
				</tr>
				<tr>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_aw" name="kondisi_awal[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_1" name="target_1[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_2" name="target_2[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_3" name="target_3[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_4" name="target_4[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_5" name="target_5[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_ah" name="target_kondisi_akhir[1]"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
