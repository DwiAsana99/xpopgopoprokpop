<script type="text/javascript">
	$(function() {
		prepare_chosen();

		// $("#codeigniter_profiler").hide();

        $("form#kegiatan").validate({
            rules: {
			    kd_keg 		: "required",
			    ket_kegiatan : "required",
            },
            messages: {
                kd_keg	  : "Mohon diisi",
                ket_kegiatan : "Mohon diisi",
            }
        });

        $("#simpan").click(function(){
		    var valid = $("form#kegiatan").valid();
		    if (valid) {
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
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element_program.trigger('click');
					}
				});
		    };
		});
    });
</script>

<div style="width: 900px">
	<header>
 		<h3>
		<?php
			if (isset($id)){
			    echo "Edit Data Kegiatan";
			} else{
			    echo "Input Data Kegiatan";
			}
		?>
		</h3>
 	</header>
 	<div class="module-content">
  <form method="post" name='kegiatan' id='kegiatan' action="<?php echo site_url('data/save_kegiatan')?>" enctype="multipart/form-data" >
		<input type="hidden" name="id" id="id_keg" value="<?php if(!empty($id)){echo $id;} ?>" />
		<input type="hidden" name="kd_urusan" id="kd_urusan" value="<?php if(!empty($program)){echo $program->Kd_Urusan;} ?>" />
		<input type="hidden" name="kd_bidang" id="kd_bidang" value="<?php if(!empty($program)){echo $program->Kd_Bidang;} ?>" />
		<input type="hidden" name="kd_prog" id="kd_prog" value="<?php if(!empty($program)){echo $program->Kd_Prog;} ?>" />
	   	  	<table id="kegiatan" class="fcari" width="100%">
	   	  		<tr>
			        <td style="width:20%">Urusan</td>
			        <td style="width:80%">
			        	<?php echo $program->Kd_Urusan.' - '.$program->Nm_Urusan; ?>
			        </td>
		        </tr>
				<tr>
					<td>Bidang</td>
					<td id="cmb-bidang">
			        	<?php echo $program->Kd_Urusan.'.'.$program->Kd_Bidang.' - '.$program->Nm_Bidang; ?>
					</td>
				</tr>
				<tr>
					<td>Program</td>
					<td id="cmb-program">
						<?php echo $program->Kd_Urusan.'.'.$program->Kd_Bidang.'.'.$program->Kd_Prog.' - '.$program->Ket_Program; ?>
					</td>
				</tr>
				<tr>
					<td>Kode Kegiatan</td>
					<td>
						<input type="text" name="kd_keg" id="kd_keg" placeholder="Kode Kegiatan"
						value="<?php echo isset($kegiatan->Kd_Keg) ? $kegiatan->Kd_Keg : ''; ?>"/>
					</td>
				</tr>
				<tr>
					<td>Keterangan Kegiatan</td>
					<td>
						<input type="text" name="ket_kegiatan" id="ket_kegiatan" placeholder="Keterangan Kegiatan"
						value="<?php echo isset($kegiatan->Ket_Kegiatan) ? $kegiatan->Ket_Kegiatan : ''; ?>" />
					</td>
				</tr>
	   	  	</table>
	   	  	</div>
   		</form>
          <footer>
            <div class="submit_link">
      			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
    		    </div>
          </footer>
</div>

