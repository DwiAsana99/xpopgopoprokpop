<script type="text/javascript">
	$(function() {
		prepare_chosen();

        $("form#bidang").validate({
            rules: {
			    kd_bidang : "required",
			    nm_bidang : "required",
			    kd_fungsi : "required"
            },
            messages: {
                kd_bidang : "Mohon diisi",
                nm_bidang : "Mohon diisi",
                kd_fungsi : "Mohon memilih kode fungsi"
            },
            ignore: ":hidden:not(select)"
        });

        $("#simpan").click(function(){
		    var valid = $("form#bidang").valid();
		    if (valid) {
		    	element_urusan.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#bidang").attr("action"),
					data: $("form#bidang").serialize(),
					dataType: "json",
					success: function(msg){
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						location.reload();
					},
					error: function(err){
						$.blockUI({
							message: "ERROR! Data Urusan gagal dibuat, mohon menghubungi administrator.",
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element_urusan.trigger('click');	
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
			    echo "Edit Data Bidang";
			} else{
			    echo "Input Data Bidang";
			}
		?>
		</h3>
 	</header>
  <div class="module-content">
	<form method="post" name='bidang' id='bidang' action="<?php echo site_url('data/save_bidang')?>" enctype="multipart/form-data" >
	<input type="hidden" name="id" value="<?php if(!empty($id)){echo $id;} ?>" />
	<input type="hidden" name="kd_urusan" value="<?php echo $urusan->id; ?>" />
	<table class="fcari" width="100%">
	  	<tr>
	        <td style="width:20%">Urusan</td>
	        <td style="width:80%">
	        	<?php echo $urusan->id.' - '.$urusan->nama; ?>
	        </td>
    	</tr>
		<tr>
			<td>Kode Bidang</td>
			<td>
				<input type="text" name="kd_bidang" id="kd_bidang" placeholder="Kode Bidang"
				value="<?php echo isset($bidang->Kd_Bidang) ? $bidang->Kd_Bidang : ''; ?>" />
			</td>
		</tr>
		<tr>
			<td>Nama Bidang</td>
			<td>
				<input type="text" name="nm_bidang" id="nm_bidang" placeholder="Nama Bidang"
				value="<?php echo isset($bidang->Nm_Bidang) ? $bidang->Nm_Bidang : ''; ?>" />
			</td>
		</tr>
		<tr>
			<td>Kode Fungsi</td>
			<td>
				<?php echo $fungsi; ?>
			</td>
		</tr>
	</table>
	</div>
  	</form>
	<footer>
	<div class="submit_link">
		<input type="submit" name="simpan" id="simpan" value='Simpan'/>
	</div>
	</footer>
</div>
