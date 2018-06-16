<script type="text/javascript">
	$(function() {
		// prepare_chosen();

		// $("#codeigniter_profiler").hide();

        $("form#program").validate({
            rules: {
			    kd_prog 	: "required",
			    ket_program : "required",
            },
            messages: {
                kd_prog   : "Mohon diisi",
                ket_program : "Mohon diisi",
            },
			ignore: ":hidden:not(select)"
        });

        $("#simpan").click(function(){
		    var valid = $("form#program").valid();
		    if (valid) {
		    	close_all();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#program").attr("action"),
					data: $("form#program").serialize(),
					dataType: "json",
					success: function(msg){
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element_bidang.trigger('click');
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
			    echo "Edit Data Program";
			} else{
			    echo "Input Data Program";
			}
		?>
		</h3>
 	</header>
  <form method="post" name='program' id='program' action="<?php echo site_url('data/save_program')?>" enctype="multipart/form-data" >
 	<div class="module-content">
		<input type="hidden" name="id" id="id" value="<?php if(!empty($id)){echo $id;} ?>" />
		<input type="hidden" name="kd_urusan" value="<?php if(!empty($bidang->Kd_Urusan)){echo $bidang->Kd_Urusan;} ?>" />
		<input type="hidden" name="kd_bidang" value="<?php if(!empty($bidang->Kd_Bidang)){echo $bidang->Kd_Bidang;} ?>" />
	   	  	<table id="program_input" class="fcari" width="100%">
	   	  		<tr>
			        <td style="width:20%">Urusan</td>
			        <td style="width:80%">
			        	<?php echo $bidang->Kd_Urusan.' - '.$bidang->Nm_Urusan; ?>
			        </td>
		        </tr>
				<tr>
					<td>Bidang</td>
					<td id="cmb-bidang">
						<?php echo $bidang->Kd_Urusan.'.'.$bidang->Kd_Bidang.' - '.$bidang->Nm_Bidang; ?>
					</td>
				</tr>
				<tr>
					<td>Kode Program</td>
					<td>
						<input type="text" name="kd_prog" id="kd_prog" placeholder="Kode Program"
						value="<?php echo isset($program->Kd_Prog) ? $program->Kd_Prog : ''; ?>"/>
					</td>
				</tr>
				<tr>
					<td>Keterangan Program</td>
					<td>
						<input type="text" name="ket_program" id="ket_program" placeholder="Keterangan Program"
						value="<?php echo isset($program->Ket_Program) ? $program->Ket_Program : ''; ?>" />
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
</article>
