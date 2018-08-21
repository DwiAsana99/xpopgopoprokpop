<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#cetak", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("ppas_preview/do_export"); ?>';
			$(location).attr('href',link);
		});
	});
</script>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Preview PPAS</h3>
	</header>
	<div class="module_content";>
		<table class="table-common">
			<thead>
				<tr>
					<th colspan="10" align="center"><?php echo $ppas_type; ?></th>
				</tr>
			</thead>		
			<?php
				echo $ppas;
			?>
		</table>		
	</div>		
	<footer>
		<div class="submit_link">
 			<input id="cetak" type="button" value="Cetak" />
			<input type="button" value="Back" onclick="history.go(-1)" />
		</div>
	</footer>
</article>