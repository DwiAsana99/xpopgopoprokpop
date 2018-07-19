<script type="text/javascript">
	$(document).ready(function(){
		$('form#approve_cik').validate({
			rules: {
				ket : "required"				
			}			
		});
				   
		$(document).on("click", "#kirim", function(){			
			var valid = $("form#approve_cik").valid();
		    if (valid) {
				$.blockUI({				
					css: window._css,				
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("cik/do_approve_cik"); ?>',
					data: $("form#approve_cik").serialize(),
					dataType: "json",
					success: function(msg){
						catch_expired_session2(msg);
						if (msg.success==1) {
							$(location).attr('href', msg.href)
						};
					}
				});
			}
		});

		$(document).on("click", "#tidak", function(){
			$(".close").trigger("click");
		});
	});
</script>
<h3>Setujui Seluruh CIK</h3>
<br>
<form id="approve_cik" method="POST" name="approve_cik">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="bulan" value="<?php echo $bulan; ?>">
	Anda Yakin Menyetujui Seluruh CIK ini ?
</form>
<div class="submit_link">
	<input id="kirim" type="button" value="Setujui" />
	<input id="tidak" type="button" value="Batal" />
</div>