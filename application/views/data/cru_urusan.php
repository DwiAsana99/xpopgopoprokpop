<script type="text/javascript">
	$(function() {

        $("form#urusan").validate({
            rules: {
                id : "required",
			    nama : "required"
            },
            messages: {
                id : "Mohon diisi",
                nama : "Mohon diisi"
            },
            ignore: ":hidden:not(select)"
        });

        $("#simpan").click(function(){
		    var valid = $("form#urusan").valid();
		    if (valid) {
		    	// element.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#urusan").attr("action"),
					data: $("form#urusan").serialize(),
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
						location.reload();	
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
			if (!empty($urusan)){
			    echo "Edit Data Urusan";
			} else{
			    echo "Input Data Urusan";
			}
		?>
		</h3>
 	</header>
	 <div class="module-content">
		<form method="post" name='urusan' id='urusan' action="<?php echo site_url('data/save_urusan')?>" enctype="multipart/form-data" >
			<input type="hidden" name="id_counter" value="<?php if(!empty($urusan->id)){echo $urusan->id;} ?>">
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Kode Urusan</strong></td>
					<td width="80%">
						<input type="text" name="id" id="id" class="common" <?php if(!empty($urusan->id)){echo 'value="'.$urusan->id.'" readonly';} ?>>
					</td>
				</tr>
				<tr>
					<td><strong>Nama Urusan</strong></td>
					<td>
						<input type="text" name="nama" id="nama" class="common" value="<?php if(!empty($urusan->nama)){echo $urusan->nama;} ?>">
					</td>
				</tr>
			</table>
  		</form>   	
	</div>
  	<footer>
    	<div class="submit_link">
			<input type="submit" name="simpan" id="simpan" value='Simpan'/>
    	</div>
  	</footer>
</div>